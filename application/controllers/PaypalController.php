<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaypalController extends MY_Controller{

    protected $_params;

    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headData']['title'] = 'Paiement Paypal';
        $this->_params['view'] = 'paypal';
    }

    public function index(){
        $this->load->view('template', $this->_params);
    }

    public function createPayment(){
		$name   = $_POST['name'];
		$total  = $_POST['total'];

        $success            = 0;
		$msg                = "Une erreur est survenue, merci de bien vouloir réessayer ultérieurement...";
		$paypal_response    = array();
		
		$payer = new $this->PaypalModel();
		$payer->setSandboxMode(1);
		$payer->setClientID("AY7g7B8-XTIhqkiBdGBnTmuXIf_2-GCDIf6CX5OnCSaGTBDzDPwumYTyFaN4h3_vakspteu0AxSMfQHf");
		$payer->setSecret("EJdOvQqSeeKdbgNouFuUt88h0gVoC8x1YdBdFjhrxW5yNYSTeVSNAYWoaRvnufdHhqod4aPMsSTwDTQm");
		
		$payment_data = array(
		   "intent" => "sale",
		   "redirect_urls" => array(
			  "return_url" => "http://localhost/",
			  "cancel_url" => "http://localhost/"
           ),
		   "payer" => array(
			  "payment_method" => "paypal"
           ),
		   "transactions" => array(
			  0 => array(
				 "amount" => array(
					"total" => $total,
					"currency" => "EUR"
                 ),
				 "item_list" => array(
					"items" => array(
					   0 => array(
						  "sku" => "1PK5Z9",
						  "quantity" => "1",
						  "name" => $name,
						  "price" => $total,
						  "currency" => "EUR"
                       )
                    )
                 ),
				 "description" => "Description du paiement..."
              )
           )
        );
		
		$paypal_response = $payer->createPayment($payment_data);
		$paypal_response = json_decode($paypal_response);
		
		if (!empty($paypal_response->id)) 
		{
			$success = 1;
			$msg = "";
		}
		else 
		{
		   $msg = "Une erreur est survenue durant la communication avec les serveurs de PayPal. Merci de bien vouloir réessayer ultérieurement.";
		}
		
		echo json_encode(array("success" => $success, "msg" => $msg, "paypal_response" => $paypal_response));
    }
	
	public function executePayment(){
        $success            = 0;
		$msg                = "Une erreur est survenue, merci de bien vouloir réessayer ultérieurement...";
		$paypal_response    = array();
		
		if (!empty($_POST['paymentID']) AND !empty($_POST['payerID'])) 
		{
		   $paymentID   = htmlspecialchars($_POST['paymentID']);
		   $payerID     = htmlspecialchars($_POST['payerID']);

		   $payer = new $this->PaypalModel();
		   $payer->setSandboxMode(1);
		   $payer->setClientID("AY7g7B8-XTIhqkiBdGBnTmuXIf_2-GCDIf6CX5OnCSaGTBDzDPwumYTyFaN4h3_vakspteu0AxSMfQHf");
		   $payer->setSecret("EJdOvQqSeeKdbgNouFuUt88h0gVoC8x1YdBdFjhrxW5yNYSTeVSNAYWoaRvnufdHhqod4aPMsSTwDTQm");

		   $payment = true;
		   if ($payment) {
			  $paypal_response = $payer->executePayment($paymentID, $payerID);
			  $paypal_response = json_decode($paypal_response);

			  if ($paypal_response->state == "approved") {
				 $success = 1;
				 $msg = "";
			  } else {
				 $msg = "Une erreur est survenue durant l'approbation de votre paiement. Merci de réessayer ultérieurement ou contacter un administrateur du site.";
			  }
		   } else {
			  $msg = "Votre paiement n'a pas été trouvé dans notre base de données. Merci de réessayer ultérieurement ou contacter un administrateur du site. (Votre compte PayPal n'a pas été débité)";
		   }
		}
		echo json_encode(array("success" => $success, "msg" => $msg, "paypal_response" => $paypal_response));
    }
}