<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'application/libraries/REST_Controller.php';
class ApiController extends REST_Controller {

    protected $_params;
    
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->load->database();
        $this->load->model('UserModel');
        $this->load->model('ActivityModel');
        $this->load->model('CategoryModel');
        $this->load->model('AdminModel');
        $this->load->model('MenuModel');
        $this->load->model('PlanningModel');
        //@TODO check security
    }

    function Activities_get(){
        $actTerm = $this->input->get('term');
        $activities = $this->ActivityModel->getActivityByTerm($actTerm);
        die(json_encode($activities));
    }

    function ActivityById_get(){
        $actId = $this->input->get('id');
        if (!$actId){
            die('Error id parameter missing');
        }
        $activities = $this->ActivityModel->getActivityById($actId);
        die(json_encode($activities));
    }


}
