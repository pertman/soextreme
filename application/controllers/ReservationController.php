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

        //@TODO check participant number availability with tslId
        $availableTickets = $activity['act_participant_nb'];
        
        $this->_params['headData']['title']         = 'Reservation d\' activité Etape 1';
        $this->_params['view']                      = 'reservationFormStep1';
        $this->_params['data']['tslId']             = $tslId;
        $this->_params['data']['plaId']             = $plaId;
        $this->_params['data']['selectedDate']      = str_replace('-', '/', $inputDate);
        $this->_params['data']['selectedTime']      = $timeSlot;
        $this->_params['data']['availableTickets']  = $availableTickets;
        $this->_params['data']['price']             = $price;
        $this->_params['data']['activity']          = $activity;
        $this->load->view('template', $this->_params);

    }

    public function reservationStep2(){

        $quote = $this->input->post();

        foreach ($quote['participants'] as $key => $participant){
            //@TODO check prices with promotion ages
            $quote['participants'][$key]['price'] = $quote['price'];

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

        $this->ReservationModel->createReservation($date, $quote['time'], count($quote['participants']), $quote['tsl_id'], $_SESSION['user']['id']);

        $resId = $this->db->insert_id();

        foreach ($quote['participants'] as $key => $participant){
            $this->TicketModel->createTicket($participant['usr_firstname'], $participant['usr_lastname'], $participant['usr_age'], $participant['usr_gift_email'], $participant['price']);

            $ticId = $this->db->insert_id();

            $this->TicketModel->createTicketReservationLink($resId, $ticId);
            if($participant['usr_gift_email']){
                //@TODO send mail
            }
        }

        //@TODO CREATE PAYMENT WITH PAYPAL DATA

        $_SESSION['messages'][] = "Votre réservation a bien été effectuée";
        $this->redirectHome();
    }
}