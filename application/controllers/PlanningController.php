<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PlanningController extends MY_Controller
{

    protected $_params;
    protected $_time;
    
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
            $duration           = $activity['act_duration'];
            $actParticipantNb   = $activity['act_participant_nb'];

            $planningItemResult = $this->getIndexDayInRange($periodStart, $periodEnd, $dayIndex);

            //@TODO ADD AVAILABILITY TO SLOTS
            $slots = getSessionsBetweenHours($startHour, $endHour, $duration, $actParticipantNb);

            foreach ($planningItemResult as $date){
                $dateData = array(
                    'date'                  => $date,
                    'start'                 => $startHour,
                    'end'                   => $endHour,
                    'pla_id'                => $plaId,
                    'tsl_id'                => $tslId,
                    'slots'                 => $slots,
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

    public function createReservation(){
        $plaId              = $this->input->post('event_modal_pla_id');
        $tslId              = $this->input->post('event_modal_tsl_id');
        $inputDate          = $this->input->post('event_modal_date');
        $timeSlot           = $this->input->post('event_modal_time');
        
        $regex = "/^[0-2][0-9]:[0-5][0-9]-[0-2][0-9]:[0-5][0-9]/";
        if (!preg_match($regex, $timeSlot)){
            $_SESSION['messages'][] = "Le format du créneau horaire sélectionné n'est pas valide";
            $this->redirectHome();
        }
        
        $date               = new \DateTime($inputDate);
        $dayIndex           = ($date->format('w'));
        
        $timeSlotArray      = explode('-', $timeSlot);
        $timeStart          = $timeSlotArray[0].':00';
        $timeEnd            = $timeSlotArray[1].':00';
        
        $planning           = $this->PlanningModel->getPlanningByPlanningId($plaId);
        $planningTimeSlot   = $this->PlanningModel->getPlanningTimeSlotsById($tslId);
        
        $activity           = $this->ActivityModel->getActivityById($planning['act_id']);

        $activityDuration   = $activity['act_duration'];


        if ($planningTimeSlot['tsl_day_index'] != $dayIndex){
            $_SESSION['messages'][] = "Le jour sélectionné n'est pas valide";
            $this->redirectHome();
        }

        $planningDateStart =  new \DateTime($planning['pla_date_start']);
        $planningDateEnd   =  new \DateTime($planning['pla_date_end']);

        if ($planningDateStart > $date || $date > $planningDateEnd){
            $_SESSION['messages'][] = "La date sélectionnée n'est pas valide";
            $this->redirectHome();
        }

        if($timeStart < $planningTimeSlot['tsl_hour_start'] || $timeStart > $timeEnd || $timeEnd > $planningTimeSlot['tsl_hour_end']){
            $_SESSION['messages'][] = "Las horaires sélectionnées ne sont pas valide";
            $this->redirectHome();
        }

        if (addMinutesToTime($timeStart, $activityDuration) != $timeEnd){
            $_SESSION['messages'][] = "La durée du créneau selectionné n'est pas valide";
            $this->redirectHome();
        }

        //@TODO check participant number availability with tslId
        $availableTickets = $activity['act_participant_nb'];
        
        $this->_params['headData']['title']         = 'Reservation d\' activité';
        $this->_params['view']                      = 'reservationForm';
        $this->_params['data']['tslId']             = $tslId;
        $this->_params['data']['selectedDate']      = str_replace('-', '/', $inputDate);
        $this->_params['data']['selectedTime']      = $timeSlot;
        $this->_params['data']['availableTickets']  = $availableTickets;
        $this->_params['data']['activity']          = $activity;
        $this->load->view('template', $this->_params);

    }

    public function create(){
        var_dump($this->input->post());
        die();
    }
}