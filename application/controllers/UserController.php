<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends MY_Controller {

    protected $_data = array();

    public function __construct()
    {
        parent::__construct();
        parent::init();

    }

    public function index()
    {
        $this->load->view('registrationForm.php');
    }

    public function create(){
        $post = $this->input->post();
        $isError = false;

        $isEmailAlreadyUse = $this->UserModel->getUserByEmail($post['usr_email']);

        if ($isEmailAlreadyUse){
            $isError = true;
            $this->_data['messages'][] = "Cet email est déjà enregistré";
        }

        if ($post['usr_password'] != $post['usr_password_2']){
            $isError = true;
            $this->_data['messages'][] = "Les deux mots de passe ne correspondent pas";
        }

        if ($isError){
            $this->load->view('registrationForm.php', $this->_data);
        }else{
            $this->UserModel->createUser($post['usr_firstname'], $post['usr_lastname'], $post['usr_email'], $post['usr_password'], $post['usr_phone'], 1);


            $_SESSION['id'] = $this->db->insert_id();
            $_SESSION['messages'][] = "Votre inscription à été faite avec succès";

            redirect('MainController', 'refresh');
        }
    }
}
