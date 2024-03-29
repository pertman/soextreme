<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct()
    {
        session_start();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        parent::__construct();
    }

    public function init(){
        $this->load->helper('url');
        $this->load->helper('main_helper');
        $this->load->helper('planning_helper');
        $this->load->database();
        $this->load->model('UserModel');
        $this->load->model('ActivityModel');
        $this->load->model('CategoryModel');
        $this->load->model('AdminModel');
        $this->load->model('MenuModel');
        $this->load->model('PlanningModel');
        $this->load->model('ReservationModel');
        $this->load->model('TicketModel');
        $this->load->model('PromotionModel');
        $this->load->model('PaymentModel');
        $this->load->model('CommentModel');
        $this->load->model('NewsletterModel');
        $this->load->model('AdminRequestModel');
        $this->load->model('PaypalModel');

        if (!isset($_SESSION['messages'])){
            $_SESSION['messages'] = array();
        }

    }

    public function getBaseParams(){
        $params                = array();
        $params['data']        = array();
        $params['messages']    = array();
        $params['headData']    = array();
        $params['topMenu']     = $this->getTopMenuParam();

        return $params;
    }

    public function redirectHome(){
        redirect('/', 'refresh');
    }

    public function getTopMenuParam(){
        //@TODO cache
        $topMenuParam   = array();
        $topMenu        = $this->MenuModel->getTopMenu();

        if ($topMenu){
            $menuCategories = $this->MenuModel->getMenuCategories($topMenu['men_id']);

            foreach ($menuCategories as $menuCategory){
                $catId = $menuCategory['cat_id'];
                $topMenuParam['categories'][$catId]['name'] = $menuCategory['cat_name'];

                $activities = $this->ActivityModel->getActivitiesByCategoryId($catId);

                $topMenuParam['categories'][$catId]['activities'] = array();

                foreach ($activities as $activity){
                    $actId = $activity['act_id'];
                    $topMenuParam['categories'][$catId]['activities'][$actId]['name'] = $activity['act_name'];
                }

                //Remove categories without activities
                if (!$topMenuParam['categories'][$catId]['activities']){
                    unset($topMenuParam['categories'][$catId]);
                }
            }
        }

        return $topMenuParam;
    }
}
