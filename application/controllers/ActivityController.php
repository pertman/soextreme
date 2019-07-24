<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ActivityController extends MY_Controller{

    protected $_params;

    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headData']['title'] = 'Nouvelle Activité';
        $this->_params['view'] = 'activityForm';
    }

    public function createActivity(){
        //@TODO DUPPLICATE CONTROLLERS FOR ADMIN OR USER TO DO IT ON CONSTRUCT
        if (getCurrentUserType() != getAdminUserType()){
            $this->redirectHome();
        }

        if ($post = $this->input->post()) {

            $existingActivity = $this->ActivityModel->getActivityByName($post['act_name']);

            if ($existingActivity){
                $this->_params['messages'][] = "Une activité avec ce nom existe déjà";
                $this->load->view('template', $this->_params);
            }else{
                //TODO Attention convertion 'act_duration' fausse, les minutes deviennent des secondes en bdd
                $this->ActivityModel->createActivity($post);

                $_SESSION['messages'][] = "L'activité ". $post['act_name'] . " a bien été crée";

                redirect('/ActivityController/listActivities', 'refresh');
            }
        }else{
            $this->_params['data']['category']  = $this->CategoryModel->getActiveCategories();
            $this->load->view('template', $this->_params);
        }
    }

    public function listActivities(){
        //@TODO DUPPLICATE CONTROLLERS FOR ADMIN OR USER TO DO IT ON CONSTRUCT
        if (getCurrentUserType() != getAdminUserType()){
            $this->redirectHome();
        }

        $this->_params['headData']['title'] = 'Liste des activités';
        $this->_params['view'] = 'activityList';
        $this->_params['data']['activities']  = $this->ActivityModel->getAllActivities();
        $this->load->view('template', $this->_params);
    }

    public function planActivity(){
        //@TODO DUPPLICATE CONTROLLERS FOR ADMIN OR USER TO DO IT ON CONSTRUCT
        if (getCurrentUserType() != getAdminUserType()){
            $this->redirectHome();
        }

        $actId = $this->input->get('id');

        if (!$actId){
            $_SESSION['messages'][] = "Aucun identifiant d'activité renseigné";

            $this->redirectHome();
        }

        $activity = $this->ActivityModel->getActivityById($actId);

        if (!$activity){
            $_SESSION['messages'][] = "Cette activité n'existe pas";

            $this->redirectHome();
        }

        $this->_params['headData']['title'] = 'Planification';
        $this->_params['view'] = 'activityPlan';
        $this->_params['data']['activity']  = $activity;
        $this->load->view('template', $this->_params);
    }

    public function seeActivity(){
        $actId = $this->input->get('id');

        if (!$actId){
            $_SESSION['messages'][] = "Aucun identifiant d'activité renseigné";

            $this->redirectHome();
        }

        $activity = $this->ActivityModel->getActivityById($actId);

        if (!$activity){
            $_SESSION['messages'][] = "Cette activité n'existe pas";

            $this->redirectHome();
        }

        $this->_params['headData']['title'] = $activity['act_name'];
        $this->_params['view'] = 'activityView';
        $this->_params['data']['activity']  = $activity;
        $this->_params['data']['category']  = array();

        if ($catId = $activity['cat_id']){

            $category = $this->CategoryModel->getCategoryById($catId);

            if ($category){
                $this->_params['data']['category'] = $category;
            }
        }

        $this->load->view('template', $this->_params);
    }
}