<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiController extends MY_Controller {

    protected $_params;
    
    public function __construct()
    {
        parent::__construct();
        parent::init();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
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
