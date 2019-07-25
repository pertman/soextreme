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

            $existingActivity = $this->ActivityModel->getActivityByName($post['act_name']);

            if ($existingActivity){
                $this->_params['messages'][] = "Une activité avec ce nom existe déjà";
                $this->load->view('template', $this->_params);
            }else{
                $this->ActivityModel->createActivity($post);

                $_SESSION['messages'][] = "L'activité ". $post['act_name'] . " a bien été crée";

                redirect('/ActivityController/listActivities', 'refresh');
            }
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

            $dateArray      = explode(' - ', $post['date_range']);
            $datetimeStart = (isset($dateArray[0])) ? $dateArray[0] : null;
            $datetimeEnd   = (isset($dateArray[1])) ? $dateArray[1] : null;

            if ($datetimeEnd  && $datetimeStart){
                $datetimeStartArray    = explode(' ', $datetimeStart);
                $datetimeEndArray      = explode(' ', $datetimeEnd);

                $dateStart             = $datetimeStartArray[0];
                $hourStart             = $datetimeStartArray[1];
                $dateEnd               = $datetimeEndArray[0];
                $hourEnd               = $datetimeEndArray[1];

                $monday     = (isset($post['monday'])) ? 1 : 0;
                $tuesday    = (isset($post['tuesday'])) ? 1 : 0;
                $wednesday  = (isset($post['wednesday'])) ? 1 : 0;
                $thursday   = (isset($post['thursday'])) ? 1 : 0;
                $friday     = (isset($post['friday'])) ? 1 : 0;
                $saturday   = (isset($post['saturday'])) ? 1 : 0;
                $sunday     = (isset($post['sunday'])) ? 1 : 0;

                if ($monday || $tuesday || $wednesday || $thursday || $friday || $saturday || $sunday){
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
                    redirect('/AdminActivityController/planActivity?id='.$post['act_id'], 'refresh');
                }else{
                    $_SESSION['messages'][] = "Veuillez sélectionner au moins un jour";
                    redirect('/AdminActivityController/planActivity?id='.$post['act_id'], 'refresh');
                }
            }else{
                $_SESSION['messages'][] = "Veuillez sélectionner deux dates";
                redirect('/AdminActivityController/planActivity?id='.$post['act_id'], 'refresh');
            }
        } else{
            $actId      = $this->input->get('id');
            $activity   = $this->activityCheck($actId);

            $this->_params['data']['activity']  = $activity;
            $this->load->view('template', $this->_params);
        }
    }
}