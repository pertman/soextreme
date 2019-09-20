<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AccountController extends MY_Controller {

    protected $_params;
    
    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headData']['title'] = 'Mon profil';
        $this->_params['view'] = 'profile.php';
    }

    public function profile()
    {
        $this->_params['data']['user'] = $this->UserModel->getUserById($_SESSION['user']['id']);
        $reservations = $this->ReservationModel->getReservationsByUserId($_SESSION['user']['id']);
        foreach ($reservations as $key => $reservation){
            $reservations[$key]['activity'] = $this->ActivityModel->getActivityById($reservation['act_id']);
            $reservations[$key]['tickets'] = $this->TicketModel->getTicketsByReservationId($reservation['res_id']);
        }
        $this->_params['data']['reservations'] = $reservations;
        $this->load->view('template.php', $this->_params);
    }

    public function updateProfile(){
        $post = $this->input->post();

        $isEmailAlreadyUse = $this->UserModel->getUserByEmail($post['usr_email']);

        if ($isEmailAlreadyUse && $isEmailAlreadyUse['usr_id'] != $_SESSION['user']['id']){
            $this->_params['messages'][] = "Cet email est déjà enregistré pour un autre utilisateur";
            return $this->profile();
        }

        $this->UserModel->updateInformation($post);

        $_SESSION['messages'][] = "Votre profil a bien été modifié";
        return $this->profile();
    }

    public function updatePassword(){
        $post = $this->input->post();
        $this->_params['view'] = 'profile.php';

        if ($post['usr_password'] != $post['usr_password_2']){
            $this->_params['messages'][] = "Les deux mots de passe ne correspondent pas";
            return $this->profile();
        }

        $_SESSION['messages'][] = "Votre mot de passe a bien été modifié";
        return $this->profile();
    }
}
