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
            $plaId          = $planningItem['pla_id'];
            $periodStart    = $planningItem['pla_date_start'];
            $periodEnd      = $planningItem['pla_date_end'];
            $dayIndex       = $planningItem['tsl_day_index'];
            $startHour      = $planningItem['tsl_hour_start'];
            $endHour        = $planningItem['tsl_hour_end'];

            $planningItemResult = $this->getIndexDayInRange($periodStart, $periodEnd, $dayIndex);

            foreach ($planningItemResult as $date){
                $dateData = array(
                    'date'      => $date,
                    'start'     => $startHour,
                    'end'       => $endHour,
                    'pla_id'    => $plaId
                );

                $activityDate[] = $dateData;
            }
        }

        $this->_params['data']['dates']     =  $activityDate;
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
}