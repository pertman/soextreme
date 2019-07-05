<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SessionController extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        parent::init();
    }

    public function isLoggedIn()
    {
        $this->output->set_content_type('Content-Type: application/json');
        if (isset($_SESSION['id'])){
            $result = json_encode(array('status' => 'valid'));
        }else{
            $result = json_encode(array("status" => "error"));
        }

        return $this->output->set_output($result);
    }
}
