<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginController extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        parent::init();

        if (!verifyReferer()){
            die('REFERER ERROR');
        }
    }

    public function showLoginForm(){
        $this->load->view('loginForm.php');
    }

    public function connect(){

        if (!verifyCSRF()){
            die('CSRF ERROR');
        }

        $result  = array();

        $post    = $this->input->post();

        $this->output->set_content_type('Content-Type: application/json');

        $user = $this->UserModel->getUserByEmail($post['usr_email']);

        if (!$user){
            $result['status'] = 'error';
            $result['messages'][] = 'Cet email n\'est pas enregistré';

            return $this->output->set_output(json_encode($result));
        }

        if (sha1($post['usr_password']) != $user['usr_password']){
            $result['status'] = 'error';
            $result['messages'][] = 'Le mot de passe ne correspond pas';

            return $this->output->set_output(json_encode($result));
        }

        $_SESSION['id'] = $user['usr_id'];

        $result['status'] = 'valid';
        $result['messages'][] = 'Connexion réussie';

        return $this->output->set_output(json_encode($result));
    }

    public function disconnect(){
        $result = array();

        unset($_SESSION['id']);

        $result['status'] = 'valid';
        $result['messages'][] = 'Déconnexion réussie';

        return $this->output->set_output(json_encode($result));
    }
}
