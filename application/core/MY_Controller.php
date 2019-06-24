<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct()
    {
        session_start();

        parent::__construct();
    }

    public function init(){
        $this->load->helper('url');
        $this->load->database();
//        $this->load->database();
//        $this->load->helper('helper_helper');

        $this->load->model('TestModel');
    }
}
