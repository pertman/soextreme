<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PlanningController extends MY_Controller
{
    protected $_params;
    
    protected $_dayIndexLabelMapping = array(
          '1' => 'monday',
          '2' => 'tuesday',
          '3' => 'wednesday',
          '4' => 'thursday',
          '5' => 'friday',
          '6' => 'saturday',
          '7' => 'sunday'
        );

    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headData']['title'] = 'Planning activité';
        $this->_params['view'] = 'planningActivity';
    }

    public function seeActivityPlanning(){
        $actId = $this->input->get('id');

        if (!$actId){
            $_SESSION['messages'][] = "Aucun identifiant d'activité renseigné";

            $this->redirectHome();
        }

        $activity = $this->ActivityModel->getActivityById($actId);

        if (!$activity){
            $_SESSION['messages'][] = "Cette activité n'existe pas";

            $this->redirectHome();
        }

        if ($activity['act_status'] != 'active' && !isCurrentUserAdmin()){
            $_SESSION['messages'][] = "Cette activité n'est pas publique";

            $this->redirectHome();
        }

        $activityDate = array();

        $allPlanningItemsForActivity =  $this->PlanningModel->getAllPlanningItemsForActivity($activity['act_id']);

        foreach ($allPlanningItemsForActivity as $planningItem){
            $plaId              = $planningItem['pla_id'];
            $periodStart        = $planningItem['pla_date_start'];
            $periodEnd          = $planningItem['pla_date_end'];
            $dayIndex           = $planningItem['tsl_day_index'];
            $startHour          = $planningItem['tsl_hour_start'];
            $endHour            = $planningItem['tsl_hour_end'];
            $tslId              = $planningItem['tsl_id'];

            $planningItemResult = $this->getIndexDayInRange($periodStart, $periodEnd, $dayIndex);

            $slots = $this->getSessionsBetweenHours($startHour, $endHour, $activity);

            foreach ($planningItemResult as $date){

                $reservedSlots =  $this->getDateReservedSlots($date);

                $dateSlots = $this->applyPromotionsToDateSlots($slots, $date, $startHour, $endHour, $activity);

                foreach ($reservedSlots as $time => $participantNb){
                    $dateSlots[$time]['participantNb'] -= $participantNb;
                }

                //Events needs autoincrement index
                $dateSlots = array_values($dateSlots);

                $dateData = array(
                    'date'                  => $date,
                    'start'                 => $startHour,
                    'end'                   => $endHour,
                    'pla_id'                => $plaId,
                    'tsl_id'                => $tslId,
                    'slots'                 => $dateSlots,
                );

                $activityDate[] = $dateData;
            }
        }

        $this->_params['data']['dates']     = $activityDate;
        $this->_params['data']['activity']  = $activity;
        $this->load->view('template', $this->_params);
    }

    public function getIndexDayInRange($periodStart, $periodEnd, $dayIndex)
    {
        $dateFrom = new \DateTime($periodStart);
        $dateTo = new \DateTime($periodEnd);
        $dates = array();

        if ($dateFrom > $dateTo) {
            return $dates;
        }
        
        $dayLabel = $this->_dayIndexLabelMapping[$dayIndex];

        if ($dayIndex != $dateFrom->format('N')) {
            $dateFrom->modify('next ' . $dayLabel);
        }

        while ($dateFrom <= $dateTo) {
            $dates[] = $dateFrom->format('Y-m-d');
            $dateFrom->modify('+1 week');
        }

        return $dates;
    }

    private function getSessionsBetweenHours($startHour, $endHour, $activity){
        $slots              = array();
        $duration           = $activity['act_duration'];
        $actParticipantNb   = $activity['act_participant_nb'];
        $actBasePrice       = $activity['act_base_price'];

        $time = $startHour;

        while ($time < $endHour){
            $index = substr_replace($time ,"", -3);

            $slots[$index]['start']             = $time;
            $time                               = addMinutesToTime($time, $duration);
            $slots[$index]['end']               = $time;

            $slots[$index]['participantNb']     = $actParticipantNb;

            $slots[$index]['base_price']        = $actBasePrice;
            $slots[$index]['price']             = $actBasePrice;

            if ($time > $endHour){
                unset($slots[$index]);
                break;
            }
        }

        return $slots;
    }

    public function getDateReservedSlots($date){
        $reservedSlots      = array();
        $dateReservations   = $this->ReservationModel->getReservationsByDate($date);

        if ($dateReservations){
            foreach ($dateReservations as $dateReservation){
                $timeArray =  explode('-',$dateReservation['res_time_slot']);
                $startTime = $timeArray[0];

                if (isset($reservedSlots[$startTime])){
                    $reservedSlots[$startTime] += $dateReservation['res_participant_nb'];
                }else{
                    $reservedSlots[$startTime] = $dateReservation['res_participant_nb'];
                }
            }
        }

        return $reservedSlots;
    }

    public function applyPromotionsToDateSlots($dateSlots, $date, $startHour, $endHour, $activity){
        $datePromotions = $this->PromotionModel->getDateAndHoursPromotion($date, $startHour, $endHour);

        foreach ($dateSlots as $timeKey => $dateSlot){
            $dateSlots[$timeKey]['base_price'] = formatPrice($dateSlots[$timeKey]['price']);
            
            $priorities = array();
            
            foreach ($datePromotions as $datePromotion){

                if ($datePromotion['pro_act_ids']){
                    $actIds = explode(',', $datePromotion['pro_act_ids']);
                    if (!in_array($activity['act_id'], $actIds)){
                        continue;
                    }
                }

                if ($datePromotion['pro_cat_ids']){
                    $catIds = explode(',', $datePromotion['pro_cat_ids']);
                    if (!in_array($activity['cat_id'], $catIds)){
                        continue;
                    }
                }

                if ($datePromotion['pro_hour_start'] && $dateSlot['start'] < $datePromotion['pro_hour_start']
                    || $datePromotion['pro_hour_end'] && $dateSlot['end'] > $datePromotion['pro_hour_end']) {
                    continue;
                }

                $priorityPrice = null;

                if ($discountAmount = $datePromotion['pro_discount_fix']){
                    $priorityPrice = $dateSlots[$timeKey]['price'] - $discountAmount;
                }
                if ($discountPercent = $datePromotion['pro_discount_percent']){
                    $priorityPrice = $dateSlots[$timeKey]['price'] * (1 - $discountPercent * 0.01);
                }
                
                if (!isset($priorities[$timeKey][$datePromotion['pro_priority']])){
                    $priorities[$timeKey][$datePromotion['pro_priority']]['pro_id'] = $datePromotion['pro_id'];
                    $priorities[$timeKey][$datePromotion['pro_priority']]['price']                                              = $priorityPrice;
                    $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['name']                     = $datePromotion['pro_name'];

                    if ($discountAmount = $datePromotion['pro_discount_fix']){
                        $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_amount']      = $discountAmount;
                        $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_percent']     = null;
                    }
                    if ($discountPercent = $datePromotion['pro_discount_percent']){
                        $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_amount']      = null;
                        $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_percent']     = $discountPercent;
                    }

                }else{
                    if ($priorityPrice < $priorities[$timeKey][$datePromotion['pro_priority']]['price']){
                        $priorities[$timeKey][$datePromotion['pro_priority']]['pro_id'] = $datePromotion['pro_id'];
                        $priorities[$timeKey][$datePromotion['pro_priority']]['price']                                          = $priorityPrice;
                        $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['name']                 = $datePromotion['pro_name'];

                        $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_amount']      = null;
                        $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_percent']     = null;

                        if ($discountAmount = $datePromotion['pro_discount_fix']){
                            $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_amount']    = $discountAmount;
                            $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_percent']   = null;
                        }
                        if ($discountPercent = $datePromotion['pro_discount_percent']){
                            $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_amount']    = null;
                            $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_percent']   = $discountPercent;
                        }
                    }
                }
            }

            foreach ($priorities as $priorityTimeKey => $priority){
                foreach ($priority as $priorityKey => $priorityPromotions){
                    if ($discountAmount = $priorityPromotions[$priorityPromotions['pro_id']]['discount_amount']){
                        $dateSlots[$timeKey]['price']  = $dateSlots[$timeKey]['price'] - $discountAmount;
                    }
                    if ($discountPercent = $priorityPromotions[$priorityPromotions['pro_id']]['discount_percent']){
                        $dateSlots[$timeKey]['price']  = $dateSlots[$timeKey]['price'] * (1 - $discountPercent * 0.01);
                    }
                    $dateSlots[$timeKey]['promotions'][$priorityKey]['pro_id']      = $priorityPromotions['pro_id'];
                    $dateSlots[$timeKey]['promotions'][$priorityKey]['pro_name']    = $priorityPromotions[$priorityPromotions['pro_id']]['name'];
                }

            }

            $dateSlots[$timeKey]['price'] = formatPrice($dateSlots[$timeKey]['price']);
        }

        return $dateSlots;
    }
}