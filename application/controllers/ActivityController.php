<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ActivityController extends MY_Controller{

    protected $_params;

    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headData']['title'] = 'Nouvelle Activité';
        $this->_params['view'] = 'activityForm';

    }

    public function createActivity(){
        if ($post = $this->input->post()) {

            $existingActivity = $this->ActivityModel->getActivityByName($post['act_name']);

            if ($existingActivity){
                $this->_params['messages'][] = "Une activité avec ce nom existe déjà";
                $this->load->view('template', $this->_params);
            }else{
                $this->ActivityModel->createActivity($post);
                $_SESSION['messages'][] = "L'activité ". $post['act_name'] . " à bien été crée";
                $this->redirectHome($this->_params);
            }

        }else{
            $this->_params['data']['category']  = $this->CategoryModel->getActiveCategories();
            $this->load->view('template', $this->_params);
        }
    }

    public function showActivities(){
        $this->_params['headData']['title'] = 'Liste des activités';
        $this->_params['view'] = 'activityList';
        $this->_params['data']['activities']  = $this->ActivityModel->getAllActivities();
        $this->load->view('template', $this->_params);
    }

    public function planActivity(){
        $act_id = $this->input->get('id');
        $this->_params['headData']['title'] = 'Planification';
        $this->_params['view'] = 'activityPlan';
        $this->_params['data']['activity']  = $this->ActivityModel->getActivityById($act_id);
        $this->load->view('template', $this->_params);
    }

//    @WIP
    public function scheduleActivity(){
        if ($post = $this->input->post()) {

            var_dump($post);

            //On vérifie si un jour a bien été sélectionné
//            if (!(isset($post['monday'])) && !(isset($post['tuesday'])) && !(isset($post['wednesday'])) && !(isset($post['thursday']))
//                && !(isset($post['friday'])) && !(isset($post['saturday'])) && !(isset($post['sunday']))){
//                $_SESSION['messages'][] = "Erreur : Aucun jour n'a été sélectionné";
//                $this->redirectHome($this->_params);
//
//            }else{
//                $this->ActivityModel->scheduleDateActivity($post);
//
//                $plaId = $this->db->insert_id();
//
//                $tabIndex = array();
//
//                if (isset($post['monday'])){
//                    $tabIndex[] = 1;
//                }
//                if (isset($post['tuesday'])){
//                    $tabIndex[] = 2;
//                }
//                if (isset($post['wednesday'])){
//                    $tabIndex[] = 3;
//                }
//                if (isset($post['thursday'])){
//                    $tabIndex[] = 4;
//                }
//                if (isset($post['friday'])){
//                    $tabIndex[] = 5;
//                }
//                if (isset($post['saturday'])){
//                    $tabIndex[] = 6;
//                }
//                if (isset($post['sunday'])){
//                    $tabIndex[] = 7;
//                }
//
//                foreach ($tabIndex as $dayIndex){
//                    $this->ActivityModel->scheduleDayActivity($post, $dayIndex);
//                    $tslId = $this->db->insert_id();
//                    $this->ActivityModel->linkDayDateActivity($plaId, $tslId);
//                }
//                $_SESSION['messages'][] = "L'activité à bien été planifiée";
//                $this->redirectHome($this->_params);
//            }


        }
    }
}