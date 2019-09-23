<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminActivityController extends MY_Controller{

    protected $_params;

    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headData']['title'] = 'Nouvelle Activité';
        $this->_params['view'] = 'activityForm';

        if (!isCurrentUserAdmin()){
            $this->redirectHome();
        }
    }

    public function createActivity(){

        if ($post = $this->input->post()) {
            $this->ActivityModel->createActivity($post);

            $actId = $this->db->insert_id();

            foreach ($_FILES as $key => $file){
                $this->uploadPhoto($key, $file, $actId);
            }

            $_SESSION['messages'][] = "L'activité ". $post['act_name'] . " a bien été crée";

            redirect('/ActivityController/listActivities', 'refresh');
        }else{
            $this->_params['data']['categories']  = $this->CategoryModel->getActiveCategories();
            $this->load->view('template', $this->_params);
        }
    }

    public function updateActivity(){
        $this->_params['headData']['title'] = 'Modification d\'activité';
        $this->_params['view'] = 'activityForm';
        $this->_params['data']['categories']  = $this->CategoryModel->getActiveCategories();

        if ($post = $this->input->post()){
            $actId = $post['act_id'];
            $this->activityCheck($actId);

            $this->ActivityModel->updateActivity($post);

            foreach ($_FILES as $key => $file){
                $this->uploadPhoto($key, $file, $actId);
            }

            $activity = $this->ActivityModel->getActivityById($actId);

            $_SESSION['messages'][] = "L'activité " . $activity['act_name'] . " a bien été modifiée";
        }else{
            $actId      = $this->input->get('id');
            $activity   = $this->activityCheck($actId);
        }

        $this->_params['data']['activity']  = $activity;
        $this->load->view('template', $this->_params);
    }

    public function activityCheck($actId){
        if (!$actId){
            $_SESSION['messages'][] = "Aucun identifiant d'activité renseigné";

            $this->redirectHome();
        }

        $activity = $this->ActivityModel->getActivityById($actId);

        if (!$activity){
            $_SESSION['messages'][] = "Cette activité n'existe pas";

            $this->redirectHome();
        }

        return $activity;
    }

    public function planActivity(){
        $this->_params['headData']['title'] = 'Planification';
        $this->_params['view'] = 'activityPlan';

        if ($post = $this->input->post()) {
            $this->_params['data']['actId']     = $post['act_id'];
            $this->_params['data']['planning']  = $post;

            $dateArray = explode(' - ', $post['date_range']);
            $dateStart = (isset($dateArray[0])) ? $dateArray[0] : null;
            $dateEnd   = (isset($dateArray[1])) ? $dateArray[1] : null;

            $hourStart = $post['tsl_hour_start'];
            $hourEnd   = $post['tsl_hour_end'];

            if (!$dateStart || !$dateEnd || !$hourStart || !$hourEnd){
                $_SESSION['messages'][] = "Veuillez sélectionner deux dates et deux heures";
                return $this->load->view('template', $this->_params);
            }

            if ($hourStart >= $hourEnd) {
                $_SESSION['messages'][] = "Veuillez selectionner une heure de début inférieure à l'heure de fin";
                return $this->load->view('template', $this->_params);
            }
            $monday     = (isset($post['monday'])) ? 1 : 0;
            $tuesday    = (isset($post['tuesday'])) ? 1 : 0;
            $wednesday  = (isset($post['wednesday'])) ? 1 : 0;
            $thursday   = (isset($post['thursday'])) ? 1 : 0;
            $friday     = (isset($post['friday'])) ? 1 : 0;
            $saturday   = (isset($post['saturday'])) ? 1 : 0;
            $sunday     = (isset($post['sunday'])) ? 1 : 0;

            if (!$monday && !$tuesday && !$wednesday && !$thursday && !$friday && !$saturday && !$sunday){
                $_SESSION['messages'][] = "Veuillez sélectionner au moins un jour";
                return $this->load->view('template', $this->_params);
            }

            $this->PlanningModel->createPlanning($dateStart,$dateEnd, $post['act_id']);
            $plaId = $this->db->insert_id();
            $tabIndex = array();

            if ($monday){
                $tabIndex[] = 1;
            }
            if ($tuesday){
                $tabIndex[] = 2;
            }
            if ($wednesday){
                $tabIndex[] = 3;
            }
            if ($thursday){
                $tabIndex[] = 4;
            }
            if ($friday){
                $tabIndex[] = 5;
            }
            if ($saturday){
                $tabIndex[] = 6;
            }
            if ($sunday){
                $tabIndex[] = 7;
            }

            foreach ($tabIndex as $dayIndex){
                $this->PlanningModel->createTimeSlot($hourStart,$hourEnd,$dayIndex);
                $tslId = $this->db->insert_id();
                $this->PlanningModel->createTimeSlotPlanningLink($plaId, $tslId);
            }

            $_SESSION['messages'][] = "L'activité à bien été planifiée";
            return $this->load->view('template', $this->_params);

        } else{
            $actId = $this->input->get('id');
            $this->activityCheck($actId);

            $this->_params['data']['actId']  = $actId;
            $this->load->view('template', $this->_params);
        }
    }

    public function modifyPlanning(){
        $this->_params['headData']['title'] = 'Modification de planification';
        $this->_params['view']              = 'activityPlan';

        if ($plaId = $this->input->post('event_modal_pla_id')){
            $planning =  $this->planningCheck($plaId);


            $planningTimeSlots = $this->PlanningModel->getTimeSlotsByPlanningId($plaId);

            foreach ($planningTimeSlots as $planningTimeSlot){
                if (!isset($planning['tsl_hour_start'])){
                    $planning['tsl_hour_start'] = $planningTimeSlot['tsl_hour_start'];
                    $planning['tsl_hour_end']   = $planningTimeSlot['tsl_hour_end'];
                }

                $planning[$this->getDayNameByDayIndex($planningTimeSlot['tsl_day_index'])] = 'on';
            }

            $this->_params['data']['actId']     = $planning['act_id'];
            $this->_params['data']['planning']  = $planning;

            $this->load->view('template', $this->_params);
        }else{
            $post = $this->input->post();

            $this->_params['data']['actId']     = $post['act_id'];
            $this->_params['data']['planning']  = $post;

            $dateArray = explode(' - ', $post['date_range']);
            $dateStart = (isset($dateArray[0])) ? $dateArray[0] : null;
            $dateEnd   = (isset($dateArray[1])) ? $dateArray[1] : null;

            $hourStart = $post['tsl_hour_start'];
            $hourEnd   = $post['tsl_hour_end'];

            if (!$dateStart || !$dateEnd || !$hourStart || !$hourEnd){
                $_SESSION['messages'][] = "Veuillez sélectionner deux dates et deux heures";
                return $this->load->view('template', $this->_params);
            }

            if ($hourStart >= $hourEnd) {
                $_SESSION['messages'][] = "Veuillez selectionner une heure de début inférieure à l'heure de fin";
                return $this->load->view('template', $this->_params);
            }
            $monday     = (isset($post['monday'])) ? 1 : 0;
            $tuesday    = (isset($post['tuesday'])) ? 1 : 0;
            $wednesday  = (isset($post['wednesday'])) ? 1 : 0;
            $thursday   = (isset($post['thursday'])) ? 1 : 0;
            $friday     = (isset($post['friday'])) ? 1 : 0;
            $saturday   = (isset($post['saturday'])) ? 1 : 0;
            $sunday     = (isset($post['sunday'])) ? 1 : 0;

            if (!$monday && !$tuesday && !$wednesday && !$thursday && !$friday && !$saturday && !$sunday){
                $_SESSION['messages'][] = "Veuillez sélectionner au moins un jour";
                return $this->load->view('template', $this->_params);
            }

            $this->PlanningModel->updatePlanning($dateStart,$dateEnd, $post['pla_id']);
            $plaId = $post['pla_id'];
            $tabIndex = array();

            if ($monday){
                $tabIndex[] = 1;
            }
            if ($tuesday){
                $tabIndex[] = 2;
            }
            if ($wednesday){
                $tabIndex[] = 3;
            }
            if ($thursday){
                $tabIndex[] = 4;
            }
            if ($friday){
                $tabIndex[] = 5;
            }
            if ($saturday){
                $tabIndex[] = 6;
            }
            if ($sunday){
                $tabIndex[] = 7;
            }

            $timeSlots = $this->PlanningModel->getTimeSlotsByPlanningId($plaId);

            foreach ($timeSlots as $timeSlot){
                $this->PlanningModel->deleteTimeSlotLinkById($timeSlot['tsp_id']);
                $this->PlanningModel->deleteTimeSlotById($timeSlot['tsl_id']);
            }

            foreach ($tabIndex as $dayIndex){
                $this->PlanningModel->createTimeSlot($hourStart,$hourEnd,$dayIndex);
                $tslId = $this->db->insert_id();
                $this->PlanningModel->createTimeSlotPlanningLink($plaId, $tslId);
            }

            $_SESSION['messages'][] = "La plannification à bien été modifiée";

            redirect('/ActivityController/listActivities', 'refresh');
            return $this->load->view('template', $this->_params);
        }
    }

    public function planningCheck($plaId){
        if (!$plaId){
            $_SESSION['messages'][] = "Aucun identifiant de plannification renseigné";

            $this->redirectHome();
        }

        $planning = $this->PlanningModel->getPlanningByPlanningId($plaId);

        if (!$planning){
            $_SESSION['messages'][] = "Cette plannification n'existe pas";

            $this->redirectHome();
        }

        return $planning;
    }

    public function getDayNameByDayIndex($dayIndex){
        $mapping = array(
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
            7 => 'sunday',
        );

        return $mapping[$dayIndex];
    }

    public function uploadPhoto($key, $file, $actId){
        $filename   = $file['name'];
        if ($filename){
            $path       = 'uploads/activities/' . $actId;
            $config['allowed_types'] = '*';
            $config['upload_path'] = $path;
            $this->load->library('upload', $config);

            if(!is_dir('uploads/') || !is_dir('uploads/activities/') || !is_dir($path) ){
                mkdir( $path, 0777, true );
            }

            $realPath = $path.'/'.$key.'.jpg';
            if (file_exists($realPath)){
                unlink($realPath);
            }

            if ($this->upload->do_upload($key)) {
                rename($path.'/'.$filename, $realPath);
                $this->ActivityModel->setActivityImagePath($key, $realPath, $actId);
            }
        }
    }
}