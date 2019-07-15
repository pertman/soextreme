<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends MY_Controller {

    protected $_params;
    
    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headData']['title'] = 'Inscription';
        $this->_params['view'] = 'subscriptionForm.php';
    }

    public function create(){
//        @TODO CSRF
//        if (!verifyCSRF()){
//            die('CSRF ERROR');
//        }

        if ($post = $this->input->post()){

            $isError = false;

            $isEmailAlreadyUse = $this->UserModel->getUserByEmail($post['usr_email']);

            if ($isEmailAlreadyUse){
                $isError = true;
                $this->_params['messages'][] = "Cet email est déjà enregistré";
            }

            if ($post['usr_password'] != $post['usr_password_2']){
                $isError = true;
                $this->_params['messages'][] = "Les deux mots de passe ne correspondent pas";
            }

            if ($isError){
                $this->load->view('template.php', $this->_params);
            }else{
                $this->UserModel->createUser($post['usr_firstname'], $post['usr_lastname'], $post['usr_email'], sha1($post['usr_password']), $post['usr_phone'], 1);

                $_SESSION['messages'][] = "Votre inscription à été faite avec succès";

                $this->redirectHome($this->_params);
            }
        }else{
            $this->load->view('template.php', $this->_params);
        }
    }
}
