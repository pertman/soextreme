<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        parent::init();


        if (!verifyReferer()){
            die('REFERER ERROR');
        }
    }
    
    public function showSubscriptionForm(){
        $this->load->view('subscriptionForm.php');
    }

    public function create(){

        if (!verifyCSRF()){
            die('CSRF ERROR');
        }

        $isError = false;
        $result  = array();

        $post    = $this->input->post();

        $this->output->set_content_type('Content-Type: application/json');

        $isEmailAlreadyUse = $this->UserModel->getUserByEmail($post['usr_email']);

        if ($isEmailAlreadyUse){
            $isError = true;
            $result['status'] = 'error';
            $result['messages'][] = 'Cet email est déjà enregistré';
        }

        if ($post['usr_password'] != $post['usr_password_2']){
            $isError = true;
            $result['status'] = 'error';
            $result['messages'][] = 'Les deux mots de passe ne correspondent pas';
        }

        if ($isError){
            return $this->output->set_output(json_encode($result));
        }

        $this->UserModel->createUser($post['usr_firstname'], $post['usr_lastname'], $post['usr_email'], sha1($post['usr_password']), $post['usr_phone'], 1);

        $result['status'] = 'valid';
        $result['messages'][] = 'Votre inscription à été faite avec succès';

        return $this->output->set_output(json_encode($result));
    }
}
