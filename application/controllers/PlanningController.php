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

                $dateSlots = $slots;
                $reservedSlots = array();

                $dateReservations = $this->ReservationModel->getReservationsByDate($date);

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

            $slots[$index]['price']             = $actBasePrice;

            if ($time > $endHour){
                unset($slots[$index]);
                break;
            }
        }

        return $slots;
    }
}