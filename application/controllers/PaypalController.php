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
    //@TODO put pramas headData and view in each functions
		/*
        if (isCurrentUserAdmin()){
            $this->_params['data']['activities']  = $this->ActivityModel->getAllActivities();
        }else{
            $this->_params['data']['activities']  = $this->ActivityModel->getActiveActivities();
        }
		*/

        $this->load->view('template', $this->_params);
    }

    public function createPayment(){
		
		// success qui sera un booléen (0 ou 1) permettant de savoir si tout s'est passé correctement ou non
        $success = 0;	
		$msg = "Une erreur est survenue, merci de bien vouloir réessayer ultérieurement...";
		//paypal_response qui contiendra tout ce que PayPal nous enverra via son API
		$paypal_response = [];
		
		$payer = new $this->PaypalModel();
		$payer->setSandboxMode(1);
		$payer->setClientID("AY7g7B8-XTIhqkiBdGBnTmuXIf_2-GCDIf6CX5OnCSaGTBDzDPwumYTyFaN4h3_vakspteu0AxSMfQHf");
		$payer->setSecret("EJdOvQqSeeKdbgNouFuUt88h0gVoC8x1YdBdFjhrxW5yNYSTeVSNAYWoaRvnufdHhqod4aPMsSTwDTQm");
		
		$payment_data = [
		   "intent" => "sale",
		   "redirect_urls" => [
			  "return_url" => "http://localhost/",
			  "cancel_url" => "http://localhost/"
		   ],
		   "payer" => [
			  "payment_method" => "paypal"
		   ],
		   "transactions" => [
			  [
				 "amount" => [
					"total" => "9.99", // Prix total de la transaction, ici le prix de notre item
					"currency" => "EUR" // USD, CAD, etc.
				 ],
				 "item_list" => [
					"items" => [
					   [
						  "sku" => "1PK5Z9", // Un identifiant quelconque (code / référence) que vous pouvez attribuer au produit que vous vendez
						  "quantity" => "1",
						  "name" => "Un produit quelconque",
						  "price" => "9.99",
						  "currency" => "EUR"
					   ]
					]
				 ],
				 "description" => "Description du paiement..."
			  ]
		   ]
		];
		
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
		
		echo json_encode(["success" => $success, "msg" => $msg, "paypal_response" => $paypal_response]);
		//$this->ActivityModel->getActivityById($actId);
    }
	
	public function executePayment(){
		
		// success qui sera un booléen (0 ou 1) permettant de savoir si tout s'est passé correctement ou non
        $success = 0;	
		$msg = "Une erreur est survenue, merci de bien vouloir réessayer ultérieurement...";
		//paypal_response qui contiendra tout ce que PayPal nous enverra via son API
		$paypal_response = [];
		
		if (!empty($_POST['paymentID']) AND !empty($_POST['payerID'])) 
		{
		   $paymentID = htmlspecialchars($_POST['paymentID']);
		   $payerID = htmlspecialchars($_POST['payerID']);

		   $payer = new $this->PaypalModel();
		   $payer->setSandboxMode(1);
		   $payer->setClientID("AY7g7B8-XTIhqkiBdGBnTmuXIf_2-GCDIf6CX5OnCSaGTBDzDPwumYTyFaN4h3_vakspteu0AxSMfQHf");
			$payer->setSecret("EJdOvQqSeeKdbgNouFuUt88h0gVoC8x1YdBdFjhrxW5yNYSTeVSNAYWoaRvnufdHhqod4aPMsSTwDTQm");
			
			/*
		   $payment = $bdd->prepare('SELECT * FROM paiements WHERE payment_id = ?');
		   $payment->execute(array($paymentID));
		   $payment = $payment->fetch();
			*/
			$payment = true;
		   if ($payment) {
			  $paypal_response = $payer->executePayment($paymentID, $payerID);
			  $paypal_response = json_decode($paypal_response);
				/*
			  $update_payment = $bdd->prepare('UPDATE paiements SET payment_status = ?, payer_email = ? WHERE payment_id = ?');
			  $update_payment->execute(array($paypal_response->state, $paypal_response->payer->payer_info->email, $paymentID));
				*/
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
		echo json_encode(["success" => $success, "msg" => $msg, "paypal_response" => $paypal_response]);
    }
}