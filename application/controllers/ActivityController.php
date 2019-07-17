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
}