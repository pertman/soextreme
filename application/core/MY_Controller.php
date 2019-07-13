<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct()
    {
        session_start();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        parent::__construct();
    }

    public function init(){
        $this->load->helper('url');
        $this->load->helper('main_helper');
        $this->load->database();
        $this->load->model('UserModel');
        $this->load->model('ActivityModel');

        if (!isset($_SESSION['messages'])){
            $_SESSION['messages'] = array();
        }

    }

    public function getBaseParams(){
        $params                = array();
        $params['data']        = array();
        $params['messages']    = array();
        $params['headerData']  = array();

        return $params;
    }

    public function redirectHome($params){
        $params['headerData']['title'] = 'Accueil';
        $params['view'] = 'home.php';
        $this->load->view('template.php', $params);
    }
}
