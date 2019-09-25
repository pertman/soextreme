<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReservationController extends MY_Controller
{

    protected $_params;

    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headData']['title'] = 'Planning activité';
        $this->_params['view'] = 'planningActivity';
    }

    public function reservationStep1(){
        $plaId              = $this->input->post('event_modal_pla_id');
        $tslId              = $this->input->post('event_modal_tsl_id');
        $inputDate          = $this->input->post('event_modal_date');
        $timeSlot           = $this->input->post('event_modal_time');
        $price              = $this->input->post('event_modal_price');
        $promotionIds       = $this->input->post('event_modal_promotion_ids');

        $promotionsNames   = array();

        if ($promotionIds){
            $promotions =  $this->PromotionModel->getPromotionsByPromotionIds($promotionIds);
            foreach ($promotions as $promotion){
                $promotionsNames[$promotion['pro_id']] = $promotion['pro_name'];
            }
        }
        
        $regex = "/^[0-2][0-9]:[0-5][0-9]-[0-2][0-9]:[0-5][0-9]/";
        if (!preg_match($regex, $timeSlot)){
            $_SESSION['messages'][] = "Le format du créneau horaire sélectionné n'est pas valide";
            $this->redirectHome();
        }
        
        $date               = new \DateTime($inputDate);
        $dayIndex           = ($date->format('w'));
        
        $timeSlotArray      = explode('-', $timeSlot);
        $timeStart          = $timeSlotArray[0].':00';
        $timeEnd            = $timeSlotArray[1].':00';
        
        $planning           = $this->PlanningModel->getPlanningByPlanningId($plaId);
        $planningTimeSlot   = $this->PlanningModel->getPlanningTimeSlotsById($tslId);
        
        $activity           = $this->ActivityModel->getActivityById($planning['act_id']);

        $activityDuration   = $activity['act_duration'];

        if ($planningTimeSlot['tsl_day_index'] != $dayIndex){
            $_SESSION['messages'][] = "Le jour sélectionné n'est pas valide";
            $this->redirectHome();
        }

        $planningDateStart =  new \DateTime($planning['pla_date_start']);
        $planningDateEnd   =  new \DateTime($planning['pla_date_end']);

        if ($planningDateStart > $date || $date > $planningDateEnd){
            $_SESSION['messages'][] = "La date sélectionnée n'est pas valide";
            $this->redirectHome();
        }

        if($timeStart < $planningTimeSlot['tsl_hour_start'] || $timeStart > $timeEnd || $timeEnd > $planningTimeSlot['tsl_hour_end']){
            $_SESSION['messages'][] = "Las horaires sélectionnées ne sont pas valide";
            $this->redirectHome();
        }

        if (addMinutesToTime($timeStart, $activityDuration) != $timeEnd){
            $_SESSION['messages'][] = "La durée du créneau selectionné n'est pas valide";
            $this->redirectHome();
        }

        $formattedDate = formatDateFromFrToUs(str_replace('-','/', $inputDate));
        $timeSlotReservationsNb = $this->ReservationModel->getReservationsNumberForTimeSlot($formattedDate, $timeSlot, $activity['act_id']);

        $availableTickets = $activity['act_participant_nb'];
        if ($timeSlotReservationsNb){
            $availableTickets -= $timeSlotReservationsNb['reservation_nb'];
        }

        if ($availableTickets == 0){
            $_SESSION['messages'][] = "Tous les tickets de ce créneau horaire ont été réservés";
            $this->redirectHome();
        }

        $this->_params['headData']['title']         = 'Reservation d\' activité Etape 1';
        $this->_params['view']                      = 'reservationFormStep1';
        $this->_params['data']['tslId']             = $tslId;
        $this->_params['data']['plaId']             = $plaId;
        $this->_params['data']['selectedDate']      = str_replace('-', '/', $inputDate);
        $this->_params['data']['selectedTime']      = $timeSlot;
        $this->_params['data']['availableTickets']  = $availableTickets;
        $this->_params['data']['promotions']        = $promotionsNames;
        $this->_params['data']['price']             = $price;
        $this->_params['data']['activity']          = $activity;
        $this->load->view('template', $this->_params);

    }

    public function reservationStep2(){

        $quote = $this->input->post();

        $promotions       = array();
        if ($promotionIds = $quote['promotionIds']){
            $promotions = $this->PromotionModel->getPromotionsByPromotionIds($promotionIds);
        }

        $agePromotions = $this->PromotionModel->getAgePromotions();

        foreach ($quote['participants'] as $key => $participant){
            $price = $quote['price'];

            $quote['participants'][$key]['base_price']      = $price;
            $quote['participants'][$key]['promotions']      = $promotions;

            foreach ($agePromotions as $agePromotion){
                if ($agePromotion['pro_age_min'] && !$agePromotion['pro_age_max'] && $agePromotion['pro_age_min'] <= $participant['usr_age']
                    || !$agePromotion['pro_age_min'] && $agePromotion['pro_age_max'] && $agePromotion['pro_age_max'] >= $participant['usr_age']
                    || $agePromotion['pro_age_min'] && $agePromotion['pro_age_max'] && $agePromotion['pro_age_max'] >= $participant['usr_age'] &&  $agePromotion['pro_age_min'] <= $participant['usr_age']){

                    $quote['participants'][$key]['promotions'][] = $agePromotion;

                    if ($agePromotion['pro_discount_fix']){
                        $price -= $agePromotion['pro_discount_fix'];
                        break;
                    }
                    if ($agePromotion['pro_discount_percent']){
                        $price = $price * (1 - $agePromotion['pro_discount_percent'] * 0.01);
                        break;
                    }
                }
            }
            $quote['participants'][$key]['price']         = formatPrice($price);

        }

        $planning           = $this->PlanningModel->getPlanningByPlanningId($quote['pla_id']);
        $activity           = $this->ActivityModel->getActivityById($planning['act_id']);

        $this->_params['headData']['title']         = 'Reservation d\' activité Etape 2';
        $this->_params['view']                      = 'reservationFormStep2';

        $quote['activity']                          = $activity;
        $_SESSION['current_quote']                  = $quote;

        $this->load->view('template', $this->_params);
    }

    public function reservationStep3(){
		
        $quote  = $_SESSION['current_quote'];

        $date = formatDateFromFrToUs($quote['date']);

        $this->ReservationModel->createReservation($date, $quote['time'], count($quote['participants']), $quote['tsl_id'], $_SESSION['user']['id'], $quote['activity']['act_id']);

        $resId = $this->db->insert_id();

        include('phpqrcode/qrlib.php');

        foreach ($quote['participants'] as $key => $participant){

            $this->TicketModel->createTicket($participant['usr_firstname'], $participant['usr_lastname'], $participant['usr_age'], $participant['usr_gift_email'], $participant['price']);

            $ticId = $this->db->insert_id();

            $lien = base_url().'ReservationController/ValidateTicket?id='.$ticId;

            if(!is_dir('uploads/') || !is_dir('uploads/tickets/')){
                mkdir( 'uploads/tickets/', 0777, true );
            }

            QRcode::png($lien, 'uploads/tickets/' . $ticId . '.png');

            foreach ($participant['promotions'] as $promotion){
                $this->TicketModel->createTicketPromotionHistory($ticId, $promotion);
            }

            $this->TicketModel->createTicketReservationLink($resId, $ticId);

            if($email = $participant['usr_gift_email']){
                $user = $this->UserModel->getUserByEmail($participant['usr_gift_email']);

                if (!$user){
                    //@TODO send mail to $email with <a href="base_url().'UserController/create?mail='.$email /> for account creation
                    $this->load->library('email');
                    $mail['template']='activationCompte';

                    //Ci-dessous pour envoyer une variable à utiliser dans le mail
                    //$mail['utilisateur_id']=$utilisateur_id;

                    $this->email->set_newline("\r\n");
                    $this->email->from('frantzcorentin@gmail.com', 'Votre équipe So Extreme');
                    $this->email->to('frantzcorentin@gmail.com');
                    $this->email->subject("Vous avez reçu un cadeau 🎁");
                    $message=$this->load->view('email/activationCompte', $mail,true);
                    $this->email->message($message);
                    $this->email->send();
                }
            }
        }

        $bankResponse = $_POST['id_paypal'];
        $this->PaymentModel->createPayment($resId, $quote['total'], $bankResponse);

        unset($_SESSION['current_quote']);

        $_SESSION['messages'][] = "Votre réservation a bien été effectuée";
        $this->redirectHome();
    }

    public function testmail(){
        $this->load->library('email');
        $mail['template']='activationCompte';
        //Ci-dessous pour envoyer une variable à utiliser dans le mail
        //$mail['utilisateur_id']=$utilisateur_id;
        $this->email->set_newline("\r\n");
        $this->email->from('frantzcorentin@gmail.com', 'Votre équipe So Extreme');
        $this->email->to('frantzcorentin@gmail.com');
        $this->email->subject("Vous avez reçu un cadeau 🎁");
        $message=$this->load->view('email/activationCompte', $mail,true);
        $this->email->message($message);
        if ($this->email->send()) {
            echo ('Mail envoyé');
        }else{
            echo ('echec envoi mail');
        }

    }

    public function validateTicket(){
        $ticId = $this->input->get('id');
        
        if (!$ticId){
            $_SESSION['messages'][] = "Aucun identifiant de ticket reseigné";
            $this->redirectHome();
        }

        $ticket = $this->TicketModel->getTicketById($ticId);

        if (!$ticket){
            $_SESSION['messages'][] = "Ce ticket n'existe pas";
            $this->redirectHome();
        }

        if ($ticket['tic_is_used']){
            $_SESSION['messages'][] = "Ce ticket a déjà été utilisé";
            $this->redirectHome();
        }

        $this->TicketModel->validateTicket($ticId);
        $_SESSION['messages'][] = "Ticket validé";
        $this->redirectHome();
    }

    public function cancelReservation(){
        $resId = $this->input->get('id');

        if (!$resId){
            $_SESSION['messages'][] = "Identifiant de réservation manquant";
            redirect('/UserController/profile', 'refresh');
        }

        $reservation = $this->ReservationModel->getReservationById($resId);

        if ($reservation['usr_id'] != $_SESSION['user']['id']){
            $_SESSION['messages'][] = "Vous ne pouvez pas effectuer cette action";
            redirect('/UserController/profile', 'refresh');
        }

        $this->ReservationModel->cancelReservation($resId);

        $_SESSION['messages'][] = "Réservation annulée, vous serez remboursé dans un délais de 5 jours";
        redirect('/UserController/profile', 'refresh');
    }
}