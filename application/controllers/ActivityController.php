<?php


class ActivityController extends MY_Controller{

    public function __construct()
    {
        parent::__construct();
        parent::init();

    }

    public function showActivityForm(){
        $this->load->model('ActivityModel');
        $data['category'] = $this->ActivityModel->getCategory();
        $this->load->view('layout/header');
        $this->load->view('activityForm', $data);
        $this->load->view('layout/footer');
    }


}