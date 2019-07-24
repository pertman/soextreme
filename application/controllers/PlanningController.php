<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PlanningController extends MY_Controller
{

    protected $_params;

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

        if ($activity['act_status'] != 'active'){
            $_SESSION['messages'][] = "Cette activité n'est pas publique";

            $this->redirectHome();
        }

        $this->_params['data']['activity']  = $activity;
        $this->load->view('template', $this->_params);
    }
}