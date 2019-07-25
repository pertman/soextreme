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

//    @WIP
    public function scheduleActivity(){
        if ($post = $this->input->post()) {
            //var_dump($post);

            //Traitements de la string date_range
            $dateArray = explode(' - ', $post['date_range']);
            $datetime_start = (isset($dateArray[0])) ? $dateArray[0] : null;
            $datetime_end  =  (isset($dateArray[1])) ? $dateArray[1] : null;


            if ($datetime_end != null || $datetime_start != null){
                $_SESSION['messages'][] = "Erreur : vous n'avez pas sélectionné de date de fin";
                redirect('/ActivityController/planActivity?id='.$post['act_id'], 'refresh');
            }else{

                echo ('date OK');
                $datetime_startArray = explode(' ', $datetime_start);
                $datetime_endArray = explode(' ', $datetime_end);

                $date_start = $datetime_startArray[0];
                $hour_start = $datetime_startArray[1];
                $date_end  = $datetime_endArray[0];
                $hour_end = $datetime_endArray[1];

                //On vérifie si un jour a bien été sélectionné
                if (!(isset($post['monday'])) && !(isset($post['tuesday'])) && !(isset($post['wednesday'])) && !(isset($post['thursday']))
                    && !(isset($post['friday'])) && !(isset($post['saturday'])) && !(isset($post['sunday']))){

                    $_SESSION['messages'][] = "Erreur : Aucun jour n'a été sélectionné";
                    redirect('/ActivityController/planActivity?id='.$post['act_id'], 'refresh');

                }else{

                        $this->ActivityModel->scheduleDateActivity($date_start,$date_end, $post);
                        $plaId = $this->db->insert_id();
                        $tabIndex = array();

                        if (isset($post['monday'])){
                            $tabIndex[] = 1;
                        }
                        if (isset($post['tuesday'])){
                            $tabIndex[] = 2;
                        }
                        if (isset($post['wednesday'])){
                            $tabIndex[] = 3;
                        }
                        if (isset($post['thursday'])){
                            $tabIndex[] = 4;
                        }
                        if (isset($post['friday'])){
                            $tabIndex[] = 5;
                        }
                        if (isset($post['saturday'])){
                            $tabIndex[] = 6;
                        }
                        if (isset($post['sunday'])){
                            $tabIndex[] = 7;
                        }

                            foreach ($tabIndex as $dayIndex){
                            $this->ActivityModel->scheduleDayActivity($hour_start,$hour_end,$dayIndex);
                            $tslId = $this->db->insert_id();
                            $this->ActivityModel->linkDayDateActivity($plaId, $tslId);
                            }
                     $_SESSION['messages'][] = "L'activité à bien été planifiée";
                     redirect('/ActivityController/planActivity?id='.$post['act_id'], 'refresh');
                    }
                }
        }
    }
}