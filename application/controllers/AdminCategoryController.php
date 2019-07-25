<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminCategoryController extends MY_Controller{

    protected $_params;

    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headData']['title'] = 'Nouvelle Catégorie';
        $this->_params['view'] = 'categoryForm';

        if (!isCurrentUserAdmin()){
            $this->redirectHome();
        }
    }

    public function createCategory(){

        $this->_params['data']['activities'] = $this->ActivityModel->getAllActivities();
        $this->_params['data']['menus']      = $this->MenuModel->getAllMenus();

        if ($post = $this->input->post()) {

            $catName = $post['cat_name'];

            $existingCategory = $this->CategoryModel->getCategoryByName($catName);

            if ($existingCategory){
                $this->_params['messages'][] = "Une catégorie avec ce nom existe déjà";
                $this->load->view('template', $this->_params);
            }else{
                $this->CategoryModel->createCategory($catName);

                $catId = $this->db->insert_id();
                
                $_SESSION['messages'][] = "La catégorie ". $catName . " a bien été crée";

                if (isset($post['act_ids'])){
                    foreach ($post['act_ids'] as $actId){
                        $this->ActivityModel->addActivityCategoryId($actId, $catId);
                    }

                    $_SESSION['messages'][] = "Les activités sélectionnées ont bien été ajoutées à " . $catName;
                }

                if (isset($post['men_ids'])){
                    foreach ($post['men_ids'] as $menId){
                        $this->MenuModel->createMenuCategoryLink($menId, $catId);
                    }

                    $_SESSION['messages'][] = $catName ." a bien été ajoutées aux menus sélectionnés";
                }
                $this->redirectHome();
            }
        }else{
            $this->load->view('template', $this->_params);
        }
    }
}