<?php


class ActivityController extends MY_Controller{

    public function __construct()
    {
        parent::__construct();
        parent::init();

    }

    public function showActivityForm(){
        $this->load->view('activityForm.php');

    }


}