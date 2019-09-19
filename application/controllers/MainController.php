<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MainController extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	protected $_params;

    public function __construct()
    {
        parent::__construct();
        parent::init();
        
        $this->_params = parent::getBaseParams();
    }

	public function index()
	{
        $this->_params['headData']['title']     = 'Accueil';
	    $this->_params['view']                  = 'home.php';

        $this->_params['data']['activities']    = $this->ActivityModel->getMainPageActivities();
        $this->_params['data']['promotions']    = $this->PromotionModel->getMainPagePromotions();
        $this->load->view('template.php', $this->_params);
	}
}
