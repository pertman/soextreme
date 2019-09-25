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

        if ($post = $this->input->post()){

            $isError = false;

            $isEmailAlreadyUse = $this->UserModel->getUserByEmail($post['usr_email']);

            if ($isEmailAlreadyUse){
                $isError = true;
                $this->_params['messages'][] = "Cet email est déjà enregistré";
            }

            if (strlen($post['usr_password']) < 8){
                $isError = true;
                $this->_params['messages'][] = "Votre mot de passe doit comporter 8 caratères minimum";
            }

            if (!filter_var($post['usr_email'], FILTER_VALIDATE_EMAIL)){
                $isError = true;
                $this->_params['messages'][] = "Veuillez saisir un email valide";
            }

            if ($post['usr_password'] != $post['usr_password_2']){
                $isError = true;
                $this->_params['messages'][] = "Les deux mots de passe ne correspondent pas";
            }

            if ($isError){
                $this->_params['data']['post'] = $post;
                $this->load->view('template.php', $this->_params);
            }else{
                $this->UserModel->createUser($post['usr_firstname'], $post['usr_lastname'], $post['usr_email'], sha1($post['usr_password']), $post['usr_phone'], 1);

                $usrId = $this->db->insert_id();

                if(isset($_FILES['usr_profile_picture'])){
                    $this->uploadUserProfilePicture('usr_profile_picture', $_FILES['usr_profile_picture'], $usrId);
                }

                $_SESSION['messages'][] = "Votre inscription à été faite avec succès";

                $this->redirectHome();
            }
        }else{

            if ($mail = $this->input->get('mail')){
                $this->_params['data']['mail'] = $mail;
            }

            $this->load->view('template.php', $this->_params);
        }
    }

    public function profile()
    {
        $this->_params['headData']['title'] = 'Mon profil';
        $this->_params['view']              = 'profile.php';
        $user = $this->UserModel->getUserById($_SESSION['user']['id']);

        $this->_params['data']['user']  = $user;
        $reservations                   = $this->ReservationModel->getReservationsByUserId($_SESSION['user']['id']);
        $cancelledReservation           = array();
        $passedReservation              = array();

        foreach ($reservations as $key => $reservation){
            $reservations[$key]['canCancelReservation'] = $this->isReservationStartMoreThanIn4Days($reservation);
            $reservations[$key]['activity']             = $this->ActivityModel->getActivityById($reservation['act_id']);
            $reservations[$key]['tickets']              = $this->TicketModel->getTicketsByReservationId($reservation['res_id']);


            if ($reservation['res_status'] == 'cancelled'){
                $cancelledReservation[] = $reservations[$key];
                unset($reservations[$key]);
                continue;
            }elseif ($this->isPassedReservation($reservation)){
                $passedReservation[] = $reservations[$key];
                unset($reservations[$key]);
                continue;
            }
        }

        $giftReservations = $this->ReservationModel->getGiftReservations($user['usr_email'], $user['usr_id']);

        foreach ($giftReservations as $key => $giftReservation){
            $giftReservations[$key]['canCancelReservation'] = $this->isReservationStartMoreThanIn4Days($giftReservation);
            $giftReservations[$key]['activity']                 = $this->ActivityModel->getActivityById($giftReservation['act_id']);
        }

        $this->_params['data']['giftReservations']          = $giftReservations;
        $this->_params['data']['reservations']              = $reservations;
        $this->_params['data']['cancelledReservations']     = $cancelledReservation;
        $this->_params['data']['passedReservations']        = $passedReservation;
        $this->load->view('template.php', $this->_params);
    }

    public function isReservationStartMoreThanIn4Days($reservation){
        $result = false;

        $activityDateTimeString =  $reservation['res_date'].' '.getTimeSlotStartHour($reservation['res_time_slot']);
        $activityStartDateTime  = new DateTime($activityDateTimeString);

        $dateTime = new DateTime();
        $dateTime->setTimestamp(date(strtotime('now +2 hour')));

        $dateInterval = $dateTime->diff($activityStartDateTime);

        if ($dateInterval->invert == 0 && $dateInterval->days >= 4){
            $result = true;
        }

        return $result;
    }

    public function isPassedReservation($reservation){
        $result = false;

        $activityDateTimeString =  $reservation['res_date'].' '.getTimeSlotStartHour($reservation['res_time_slot']);
        $activityStartDateTime  = new DateTime($activityDateTimeString);

        $dateTime = new DateTime();
        $dateTime->setTimestamp(date(strtotime('now +2 hour')));

        $dateInterval = $dateTime->diff($activityStartDateTime);

        if ($dateInterval->invert == 1){
            $result = true;
        }

        return $result;
    }

    public function updateProfile(){
        $post = $this->input->post();

        $isEmailAlreadyUse = $this->UserModel->getUserByEmail($post['usr_email']);

        if (!filter_var($post['usr_email'], FILTER_VALIDATE_EMAIL)){
            $this->_params['messages'][] = "Veuillez saisir un email valide";
            return $this->profile();
        }

        if ($isEmailAlreadyUse && $isEmailAlreadyUse['usr_id'] != $_SESSION['user']['id']){
            $this->_params['messages'][] = "Cet email est déjà enregistré pour un autre utilisateur";
            return $this->profile();
        }

        $this->UserModel->updateInformation($post);

        if(isset($_FILES['usr_profile_picture'])){
            $this->uploadUserProfilePicture('usr_profile_picture', $_FILES['usr_profile_picture'], $post['usr_id']);
        }

        $_SESSION['messages'][] = "Votre profil a bien été modifié";
        return $this->profile();
    }

    public function updatePassword(){
        $post = $this->input->post();

        if (strlen($post['usr_password']) < 8){
            $this->_params['messages'][] = "Votre mot de passe doit comporter 8 caratères minimum";
            return $this->profile();
        }

        if ($post['usr_password'] != $post['usr_password_2']){
            $this->_params['messages'][] = "Les deux mots de passe ne correspondent pas";
            return $this->profile();
        }

        $this->UserModel->updatePassword(sha1($post['usr_password']), $post['usr_id']);
        $_SESSION['messages'][] = "Votre mot de passe a bien été modifié";
        return $this->profile();
    }

    public function uploadUserProfilePicture($key, $file, $usrId){
        $filename   = $file['name'];
        if ($filename){
            $path       = 'uploads/profile-picture/' . $usrId;
            $config['allowed_types'] = '*';
            $config['upload_path'] = $path;
            $this->load->library('upload', $config);

            if(!is_dir('uploads/') || !is_dir('uploads/profile-picture/') || !is_dir($path) ){
                mkdir( $path, 0777, true );
            }

            $realPath = $path.'/'.$key.'.jpg';
            if (file_exists($realPath)){
                unlink($realPath);
            }

            if ($this->upload->do_upload($key)) {
                rename($path.'/'.$filename, $realPath);
                $this->UserModel->setProfilePicturePath($key, $realPath, $usrId);
            }
        }
    }

    public function evaluateActivityTicket()
    {
        $post = $this->input->post();
        if (isset($post['tic_id']) && isset($post['tic_note'])){
            $this->TicketModel->evaluateActivityByTicket($post['tic_id'], $post['tic_note']);
            $activity = $this->TicketModel->getActivityByTicketId($post['tic_id']);

            $newSum     = $activity['act_note_sum'] + $post['tic_note'];
            $newCount   = $activity['act_note_count'] + 1;

            $this->ActivityModel->updateActivityNoteSumAndCount($newSum, $newCount, $activity['act_id']);

            $_SESSION['messages'][] = "Votre evaluation a bien été prise en compte";
            return $this->profile();
        }else{
            $_SESSION['messages'][] = "Tous les parametres requis ne sont pas présent";
            $this->redirectHome();
        }
    }

    public function newsletterSubscription(){
        $post = $this->input->post();

        if (!isset($post['new_email'])){
            die(json_encode(array('status' => 'error', 'message' => 'Veuillez renseigner une adresse mail')));
        }

        if (!filter_var($post['new_email'], FILTER_VALIDATE_EMAIL)){
            die(json_encode(array('status' => 'error', 'message' => 'Veuillez saisir un email valide')));
        }

        $newsletterSubscription = $this->NewsletterModel->getNewsletterByEmail($post{'new_email'});

        if ($newsletterSubscription){
            die(json_encode(array('status' => 'error', 'message' => 'Cet email est déjà enregistré à la newsletter')));
        }

        $this->NewsletterModel->createNewsletter($post{'new_email'});
        die(json_encode(array('status' => 'valid', 'message' => 'Inscription à la newslettter effectuée')));
    }

    public function contactAdmin(){
        if ($post = $this->input->post()){
            $this->AdminRequestModel->createRequest($post);

            $_SESSION['messages'][] = "Votre demande a été prise en compte";
            $this->redirectHome();
        }else{
            $this->_params['headData']['title'] = 'Contact Administration';
            $this->_params['view']              = 'adminContact.php';

            $this->load->view('template.php', $this->_params);
        }
    }
}
