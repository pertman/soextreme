<?php


class ActivityController extends MY_Controller{

    protected $_params;

    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headerData']['title'] = 'Nouvelle Activité';
        $this->_params['view'] = 'activityForm';

    }

    public function createActivity(){
        if ($post = $this->input->post()) {
//            @TODO control and create activity
        }else{
            $this->_params['data']['category']  = $this->ActivityModel->getActiveCategories();
            $this->load->view('template', $this->_params);
        }
    }
}