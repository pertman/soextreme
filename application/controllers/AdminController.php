<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminController extends MY_Controller{

    protected $_params;

    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headData']['title'] = 'Connection Administrateur';
        $this->_params['view'] = 'adminLoginForm';

    }

    public function connect(){

        if ($post = $this->input->post()){

            $isError = false;
            $admin  = $this->AdminModel->getAdminByEmail($post['usr_email']);

            if (!$admin){
                $isError = true;

                $this->_params['messages'][] = 'Cet email n\'est pas enregistré';
            }

            if ($post['usr_password'] != $admin['usr_password']){
                $isError = true;

                $this->_params['messages'][] = 'Le mot de passe ne correspond pas';
            }

            if ($isError){
                $this->load->view('template.php', $this->_params);
            }else{
                $_SESSION['admin']['id']     = $admin['usr_id'];
                $_SESSION['messages'][]     = 'Connexion réussie';

                $this->redirectHome();
            }

        }else{
            $this->load->view('template.php', $this->_params);
        }
    }

    public function disconnect(){

        if (!isCurrentUserAdmin()){
            $this->redirectHome();
        }

        unset($_SESSION['admin']);

        $_SESSION['messages'][] = 'Déconnexion réussie';

        $this->redirectHome();
    }

    //@TODO remove autoconnect
    public function autoconnect(){
        $_SESSION['admin']['id']    = 'autoconnectId';
        $_SESSION['messages'][]     = 'Connexion réussie';

        $this->redirectHome();
    }
}