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

        $todayDateTime = new DateTime();
        $todayDateTime->setTime(0,0,0);

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

            if (!isCurrentUserAdmin()){
                $slots = $this->getSessionsBetweenHours($startHour, $endHour, $activity);
            }

            foreach ($planningItemResult as $date){

                if (!isCurrentUserAdmin()) {
                    $planningDateTime = new DateTime($date);

                    if ($planningDateTime < $todayDateTime) {
                        continue;
                    }
                }

                $dateData = array(
                    'type'                  => 'planning',
                    'date'                  => $date,
                    'start'                 => $startHour,
                    'end'                   => $endHour,
                    'pla_id'                => $plaId,
                    'tsl_id'                => $tslId
                );

                if (!isCurrentUserAdmin()) {
                    $reservedSlots      = $this->getDateReservedSlots($date, $actId);
                    $datePromotions     = $this->PromotionModel->getDateAndHoursPromotion($date, $startHour, $endHour);


                    $dateData['slots']  = $this->getDateSlots($activity['act_id'], $activity['cat_id'], $slots, $reservedSlots, $datePromotions);
                }

                $activityDate[] = $dateData;
            }
        }

        if (isCurrentUserAdmin()){
            $reservedDates  = array();
            $reservations   = $this->ReservationModel->getAllReservationsByActivity($actId);
            foreach ($reservations as $reservation){
                $key = $reservation['res_date'] . ' ' . $reservation['res_time_slot'];
                if (!isset($reservedDates[$key])){
                    $timeArray  = explode('-', $reservation['res_time_slot']);
                    $startHour  = $timeArray[0]. ':00';
                    $endHour    = $timeArray[1]. ':00';

                    $reservedDates[$key] = array(
                        'type'                  => 'reservation',
                        'date'                  => $reservation['res_date'],
                        'start'                 => $startHour,
                        'end'                   => $endHour,
                        'participants'          => array(
                            0 => array(
                                'firstname' => $reservation['tic_firstname'],
                                'lastname'  => $reservation['tic_lastname'],
                                'age'       => $reservation['tic_age'],
                            )
                        )
                    );
                }else{
                    $reservedDates[$key]['participants'][] = array(
                        'tic_id'    => $reservation['tic_id'],
                        'firstname' => $reservation['tic_firstname'],
                        'lastname'  => $reservation['tic_lastname'],
                        'age'       => $reservation['tic_age'],
                    );
                }
            }

            foreach ($reservedDates as $reservedDate){
                $activityDate[] = $reservedDate;
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

    public function getDateSlots($actId, $catId, $slots, $reservedSlots, $datePromotions){
        $dateSlots      = $this->applyPromotionsToDateSlots($slots, $datePromotions, $actId, $catId);

        foreach ($reservedSlots as $time => $participantNb) {
            //Remove reserved time slots but on hours not available because of planning modifications
            if (isset($dateSlots[$time])) {
                $dateSlots[$time]['participantNb'] -= $participantNb;
            }
        }

        //Events needs autoincrement index
        $dateSlots = array_values($dateSlots);

        return $dateSlots;
    }

    public function getDateReservedSlots($date, $actId){
        $reservedSlots      = array();
        $dateReservations   = $this->ReservationModel->getReservationsByDate($date, $actId);

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

    public function applyPromotionsToDateSlots($dateSlots, $datePromotions, $actId, $catId){
        foreach ($dateSlots as $timeKey => $dateSlot){
            $dateSlots[$timeKey]['base_price'] = formatPrice($dateSlots[$timeKey]['price']);

            $priorities = array();

            foreach ($datePromotions as $datePromotion){

                if ($datePromotion['pro_act_ids']){
                    $actIds = explode(',', $datePromotion['pro_act_ids']);
                    if (!in_array($actId, $actIds)){
                        continue;
                    }
                }

                if ($datePromotion['pro_cat_ids']){
                    $catIds = explode(',', $datePromotion['pro_cat_ids']);
                    if (!in_array($catId, $catIds)){
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

    public function testPromotionsAndAvailabilities(){
        $this->load->library('unit_test');

        $actId          = 6;
        $catId          = 3;

        $slots = array(
                '08:00' => array(
                    'start' => '08:00:00',
                    'end' => '08:30:00',
                    'participantNb' => '5',
                    'base_price' => '60',
                    'price' => '60',
                ),
                '08:30' => array(
                    'start' => '08:30:00',
                    'end' => '09:00:00',
                    'participantNb' => '5',
                    'base_price' => '60',
                    'price' => '60',
                ),
                '09:00' => array(
                    'start' => '09:00:00',
                    'end' => '09:30:00',
                    'participantNb' => '5',
                    'base_price' => '60',
                    'price' => '60',
                ),
        );

        $reservedSlots  = array('08:00' =>'2', '08:30' =>'3');

        $datePromotions = array(
            0 => array(
            'pro_id' => '1',
            'pro_type' => 'other',
            'pro_name' => '-10 % avant 9h',
            'pro_description' => '-10% avant 9h',
            'pro_is_main_page' => '1',
            'pro_hour_start' => null,
            'pro_hour_end' => '09:00:00',
            'pro_date_start' => null,
            'pro_date_end' => null,
            'pro_cart_amount' => null,
            'pro_discount_fix' => null,
            'pro_discount_percent' => '10',
            'pro_age_min' => null,
            'pro_age_max' => null,
            'pro_code' => null,
            'pro_max_use' => null,
            'pro_priority' => '2',
            'pro_is_active' => '1',
            'pro_user_ids' => null,
            'pro_act_ids' => null,
            'pro_cat_ids' => null,
            ),
            1 => array(
                'pro_id' => '2',
                'pro_type' => 'other',
                'pro_name' => '-15 % avant 8h30',
                'pro_description' => '-15% avant 8h30',
                'pro_is_main_page' => '1',
                'pro_hour_start' => null,
                'pro_hour_end' => '08:30:00',
                'pro_date_start' => null,
                'pro_date_end' => null,
                'pro_cart_amount' => null,
                'pro_discount_fix' => null,
                'pro_discount_percent' => '15',
                'pro_age_min' => null,
                'pro_age_max' => null,
                'pro_code' => null,
                'pro_max_use' => null,
                'pro_priority' => '1',
                'pro_is_active' => '1',
                'pro_user_ids' => null,
                'pro_act_ids' => null,
                'pro_cat_ids' => null,
            ),
            2 => array(
                'pro_id' => '1',
                'pro_type' => 'other',
                'pro_name' => '-5 % avant 9h',
                'pro_description' => '-5% avant 9h',
                'pro_is_main_page' => '1',
                'pro_hour_start' => null,
                'pro_hour_end' => '09:00:00',
                'pro_date_start' => null,
                'pro_date_end' => null,
                'pro_cart_amount' => null,
                'pro_discount_fix' => null,
                'pro_discount_percent' => '5',
                'pro_age_min' => null,
                'pro_age_max' => null,
                'pro_code' => null,
                'pro_max_use' => null,
                'pro_priority' => '2',
                'pro_is_active' => '1',
                'pro_user_ids' => null,
                'pro_act_ids' => null,
                'pro_cat_ids' => null,
            ),
        );

        $dateSlots = $this->getDateSlots($actId, $catId, $slots, $reservedSlots, $datePromotions);

        echo $this->unit->run($dateSlots[0]['price'], (60 * 0.85)* 0.9, 'Is 10% from 8h00 to 8h30');
        echo $this->unit->run($dateSlots[1]['price'], 60 * 0.9, 'Is 10% from 8h30 to 9h00');
        echo $this->unit->run($dateSlots[2]['price'], 60, 'Is Not 10% from 9h00 to 9h30');

        echo $this->unit->run($dateSlots[0]['participantNb'], 3, 'Is 3 available slots from 8h00 to 8h30');
        echo $this->unit->run($dateSlots[1]['participantNb'], 2, 'Is 2 available slots from 8h30 to 9h00');
        echo $this->unit->run($dateSlots[2]['participantNb'], 5, 'Is 5 available slots 10% from 9h00 to 9h30');
    }
}