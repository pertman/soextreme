<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginController extends MY_Controller {

    protected $_params;
    
    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headData']['title'] = 'Connexion';
        $this->_params['view'] = 'loginForm.php';
    }

    public function connect(){

        if ($post = $this->input->post()){

            $isError = false;

            $user = $this->UserModel->getUserByEmail($post['usr_email']);
    
            if (!$user){
                $isError = true;

                $this->_params['messages'][] = 'Cet email n\'est pas enregistré';
            }
    
            if (sha1($post['usr_password']) != $user['usr_password']){
                $isError = true;

                $this->_params['messages'][] = 'Le mot de passe ne correspond pas';
            }

            if ($isError){
                $this->load->view('template.php', $this->_params);
            }else{
                $_SESSION['user']['id']     = $user['usr_id'];
                $_SESSION['messages'][]     = 'Connexion réussie';

                $this->redirectHome();
            }

        }else{
            $this->load->view('template.php', $this->_params);
        }
        

    }

    public function disconnect(){

        unset($_SESSION['user']);

        $_SESSION['messages'][] = 'Déconnexion réussie';

        $this->redirectHome();
    }
}
