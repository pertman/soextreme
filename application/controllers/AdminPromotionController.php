<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminPromotionController extends MY_Controller
{

    protected $_params;

    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headData']['title'] = 'Gestion de promotion';
        $this->_params['view'] = 'promotionForm';

        if (!isCurrentUserAdmin()) {
            $this->redirectHome();
        }
    }

    public function createPromotion()
    {
        $this->_params['headData']['title'] = 'Création de promotion';
        $this->_params['view'] = 'promotionForm';

        $this->_params['data']['activities'] = $this->ActivityModel->getAllActivities();
        $this->_params['data']['categories'] = $this->CategoryModel->getActiveCategories();

        $this->_params['data']['isAllPromotionsConditionActivated'] = false;
        //$this->_params['data']['users'] = $this->UserModel->getAllUsersFromType('customer');

        if ($post = $this->input->post()) {
            $this->_params['data']['promotion'] = $post;

            if ($post['pro_type'] == 'age') {
                if (!$post['pro_age_min'] && !$post['pro_age_max']) {
                    $_SESSION['messages'][] = "Veuillez saisir au moins un age minimum ou un age maximum";
                    return $this->load->view('template', $this->_params);
                }

                if ($post['pro_age_min'] && $post['pro_age_max'] && $post['pro_age_min'] >= $post['pro_age_max']) {
                    $_SESSION['messages'][] = "Veuillez saisir un age maximum supérieur à l'age minimum";
                    $this->_params['data']['promotion'] = $post;
                    return $this->load->view('template', $this->_params);
                }

                $this->PromotionModel->createAgePromotion($post);
                $_SESSION['messages'][] = "La promotion a bien été créé";
                $this->redirectHome();
            }
            if ($post['pro_type'] == 'other') {
                $this->_params['data']['pro_activities'] = (isset($post['act_ids'])) ? $post['act_ids'] : array();
                $this->_params['data']['pro_categories'] = (isset($post['cat_ids'])) ? $post['cat_ids'] : array();
                $this->_params['data']['pro_users'] = (isset($post['usr_ids'])) ? $post['usr_ids'] : array();

                if (!$post['date_range'] && !$post['pro_hour_start'] && !$post['pro_hour_end']  && !isset($post['act_ids']) && !isset($post['cat_ids'])) {
                    $_SESSION['messages'][] = "Veuillez renseigner au moins une condition";
                    return $this->load->view('template', $this->_params);
                }

                if ($post['date_range'] && strpos($post['date_range'], ' - ') === false) {
                    $_SESSION['messages'][] = "Veuillez selectionner une periode valable";
                    return $this->load->view('template', $this->_params);
                }

                if ($post['pro_hour_start'] && $post['pro_hour_end'] && $post['pro_hour_start'] >= $post['pro_hour_end']) {
                    $_SESSION['messages'][] = "Veuillez selectionner une heure de début inférieure à l'heure de fin";
                    return $this->load->view('template', $this->_params);
                }

                $actIds = (isset($post['act_ids'])) ? implode(',', $post['act_ids']) : "";
                $catIds = (isset($post['cat_ids'])) ? implode(',', $post['cat_ids']) : "";
//                $usrIds = (isset($post['usr_ids'])) ? implode(',', $post['usr_ids']) : "";

                $startDate  = null;
                $endDate    = null;

                if ($post['date_range']) {
                    $dateRangeArray = explode(' - ', $post['date_range']);
                    $startDate = $dateRangeArray[0];
                    $endDate = $dateRangeArray[1];
                }

                $timeStart = ($post['pro_hour_start']) ? $post['pro_hour_start'] : null;
                $timeEnd   = ($post['pro_hour_end']) ? $post['pro_hour_end'] : null;

                if (!$actIds){
                    $actIds = null;
                }
                if (!$catIds){
                    $catIds = null;
                }

                $this->PromotionModel->createOtherPromotion($post, $startDate, $endDate, $timeStart, $timeEnd, $actIds, $catIds);

                $_SESSION['messages'][] = "La promotion a bien été créé";
                $this->redirectHome();
            }

        } else {
            $this->load->view('template', $this->_params);
        }
    }

    public function listPromotion()
    {
        $this->_params['view'] = 'promotionList';
        $this->_params['data']['promotions'] = $this->PromotionModel->getAllPromotions();

        $this->load->view('template', $this->_params);
    }

    public function updatePromotion()
    {
        $this->_params['headData']['title'] = 'Modification de promotion';
        $this->_params['view'] = 'promotionForm';

        $this->_params['data']['activities'] = $this->ActivityModel->getAllActivities();
        $this->_params['data']['categories'] = $this->CategoryModel->getActiveCategories();
        $this->_params['data']['categories'] = $this->CategoryModel->getActiveCategories();

        $this->_params['data']['isAllPromotionsConditionActivated'] = false;

        if ($post = $this->input->post()){

            if ($post['pro_type'] == 'age') {
                if (!$post['pro_age_min'] && !$post['pro_age_max']) {
                    $_SESSION['messages'][] = "Veuillez saisir au moins un age minimum ou un age maximum";
                    $this->_params['data']['promotion'] = $post;
                    return $this->load->view('template', $this->_params);
                }

                if ($post['pro_age_min'] && $post['pro_age_max'] && $post['pro_age_min'] >= $post['pro_age_max']) {
                    $_SESSION['messages'][] = "Veuillez saisir un age maximum supérieur à l'age minimum";
                    $this->_params['data']['promotion'] = $post;
                    return $this->load->view('template', $this->_params);
                }

                $this->PromotionModel->updatePromotion($post);
                $_SESSION['messages'][] = "La promotion a bien été modifiée";
                $this->redirectHome();
            }
            if ($post['pro_type'] == 'other') {
                $this->_params['data']['pro_activities'] = (isset($post['act_ids'])) ? $post['act_ids'] : array();
                $this->_params['data']['pro_categories'] = (isset($post['cat_ids'])) ? $post['cat_ids'] : array();
                $this->_params['data']['pro_users']      = (isset($post['usr_ids'])) ? $post['usr_ids'] : array();

                if (!$post['date_range'] && !$post['pro_hour_start'] && !$post['pro_hour_end']  && !isset($post['act_ids']) && !isset($post['cat_ids'])) {
                    $_SESSION['messages'][] = "Veuillez renseigner au moins une condition";
                    return $this->load->view('template', $this->_params);
                }

                if ($post['date_range'] && strpos($post['date_range'], ' - ') === false) {
                    $_SESSION['messages'][] = "Veuillez selectionner une periode valable";
                    return $this->load->view('template', $this->_params);
                }

                if ($post['pro_hour_start'] && $post['pro_hour_end'] && $post['pro_hour_start'] >= $post['pro_hour_end']) {
                    $_SESSION['messages'][] = "Veuillez selectionner une heure de début inférieure à l'heure de fin";
                    return $this->load->view('template', $this->_params);
                }

                $this->PromotionModel->updatePromotion($post);

                $_SESSION['messages'][] = "La promotion a bien été modifiée";
                $this->redirectHome();
            }


        }else{
            $proId = $this->input->get('id');

            $promotion = $this->promoCheck($proId);
            $this->_params['data']['promotion'] = $promotion;
            return $this->load->view('template', $this->_params);
        }
    }

    public function deletePromotion(){
        $proId = $this->input->get('id');
        $this->promoCheck($proId);

        $this->PromotionModel->deletePromotion($proId);

        $_SESSION['messages'][] = "La promotion a bien été supprimée";

        redirect('/AdminPromotionController/listPromotion', 'refresh');
    }

    public function promoCheck($proId){
        if (!$proId){
            $_SESSION['messages'][] = "Aucun identifiant de promotion renseigné";

            redirect('/AdminPromotionController/listPromotion', 'refresh');
        }

        $promotion = $this->PromotionModel->getPromotionById($proId);

        if (!$promotion){
            $_SESSION['messages'][] = "Cette promotion n'existe pas";

            redirect('/AdminPromotionController/listPromotion', 'refresh');
        }

        return $promotion;
    }
}