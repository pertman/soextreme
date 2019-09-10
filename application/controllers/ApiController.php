<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiController extends MY_Controller {

    protected $_params;
    
    public function __construct()
    {
        parent::__construct();
        parent::init();

        //@TODO check security
    }

    public function getActivities(){
        $activities = $this->ActivityModel->getAllActivities();
        die(json_encode($activities));
    }

    public function getActivityById(){
        $actId = $this->input->get('id');
        if (!$actId){
            die('Error id parameter missing');
        }
        $activities = $this->ActivityModel->getActivityById($actId);
        die(json_encode($activities));
    }
}
