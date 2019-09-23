<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminRequestController extends MY_Controller{

    protected $_params;

    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headData']['title'] = 'Demandes utilisateurs';
        $this->_params['view'] = 'adminRequestList.php';

        if (!isCurrentUserAdmin()){
            $this->redirectHome();
        }
    }

    public function requests(){
        $this->_params['data']['openRequests']          = $this->AdminRequestModel->getRequestsByStatus('open');
        $this->_params['data']['closedRequests']        = $this->AdminRequestModel->getRequestsByStatus('closed');
        $this->_params['data']['payBackRequests']       = $this->ReservationModel->getCancelledReservations();
        $this->load->view('template.php', $this->_params);
    }

    public function openRequests(){
        $this->_params['data']['openRequests']          = $this->AdminRequestModel->getRequestsByStatus('open');
        $this->_params['data']['closedRequests']        = array();
        $this->_params['data']['payBackRequests']       = array();
        $this->load->view('template.php', $this->_params);
    }

    public function closedRequests(){
        $this->_params['data']['openRequests']          = array();
        $this->_params['data']['closedRequests']        = $this->AdminRequestModel->getRequestsByStatus('closed');
        $this->_params['data']['payBackRequests']       = array();
        $this->load->view('template.php', $this->_params);
    }
    
    public function closeRequest(){
        $adrId = $this->input->get('id');

        if (!$adrId){
            $_SESSION['messages'][] = 'Identifiant de la demande manquant';
        }

        $this->AdminRequestModel->closeRequest($adrId);

        $_SESSION['messages'][] = 'Demande n° ' . $adrId . ' clôturée';

        $this->requests();
    }

    public function paybackRequests(){
        $this->_params['data']['openRequests']          = array();
        $this->_params['data']['closedRequests']        = array();
        $this->_params['data']['payBackRequests']       = $this->ReservationModel->getCancelledReservations();
        $this->load->view('template.php', $this->_params);
    }
}