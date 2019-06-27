<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct()
    {
        session_start();

        parent::__construct();
    }

    private $_data = array();

    public function init(){
        $this->load->helper('url');
        $this->load->database();
        $this->load->model('UserModel');
    }
}
