<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ActivityController extends MY_Controller{

    protected $_params;

    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headData']['title'] = 'Liste des activités';
        $this->_params['view'] = 'activityList';
    }

    public function listActivities(){

        $this->_params['data']['activities']  = $this->ActivityModel->getAllActivities();
        $this->load->view('template', $this->_params);
    }

    public function seeActivity(){

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

        $this->_params['headData']['title'] = $activity['act_name'];
        $this->_params['view'] = 'activityView';
        $this->_params['data']['activity']  = $activity;
        $this->_params['data']['category']  = array();

        if ($catId = $activity['cat_id']){

            $category = $this->CategoryModel->getCategoryById($catId);

            if ($category){
                $this->_params['data']['category'] = $category;
            }
        }

        $this->load->view('template', $this->_params);
    }
}