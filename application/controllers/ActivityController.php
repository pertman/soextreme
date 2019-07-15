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

    public function registerActivityForm(){
        $act_title = $this->input->post('act_title');
        $act_category = $this->input->post('act_category');
        $act_description = $this->input->post('act_description');
        $act_description_short = $this->input->post('act_description_short');

        var_dump($act_title);
    }
}