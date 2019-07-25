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
                    $catInserted = false;
                    foreach ($post['act_ids'] as $actId){
                        if ($actId){
                            $this->ActivityModel->addActivityCategoryId($actId, $catId);
                            $catInserted = true;
                        }
                    }

                    if ($catInserted){
                        $_SESSION['messages'][] = "Les activités sélectionnées ont bien été ajoutées à " . $catName;
                    }
                }

                if (isset($post['men_ids'])){
                    $menInserted = false;
                    foreach ($post['men_ids'] as $menId){
                        if ($menId){
                            $this->MenuModel->createMenuCategoryLink($menId, $catId);
                            $menInserted = true;
                        }
                    }

                    if ($menInserted){
                        $_SESSION['messages'][] = $catName ." a bien été ajoutées aux menus sélectionnés";
                    }
                }
                redirect('/AdminCategoryController/listCategory', 'refresh');
            }
        }else{
            $this->load->view('template', $this->_params);
        }
    }

    public function listCategory(){
        $this->_params['headData']['title'] = 'Liste des catégories';
        $this->_params['view'] = 'categoryList';
        $this->_params['data']['categories']  = $this->CategoryModel->getActiveCategories();
        $this->load->view('template', $this->_params);
    }

    public function updateCategory(){
        $this->_params['headData']['title'] = 'Modification de catégorie';
        $this->_params['view'] = 'categoryForm';
        $this->_params['data']['activities'] = $this->ActivityModel->getAllActivities();
        $this->_params['data']['menus']      = $this->MenuModel->getAllMenus();

        if ($post = $this->input->post()) {

            $catId      = $post['cat_id'];
            $catName    = $post['cat_name'];

            $this->CategoryModel->updateCategoryName($catId, $catName);


            $this->ActivityModel->removeActivityCategoryId($catId);

            if (isset($post['act_ids'])){
                foreach ($post['act_ids'] as $actId){
                    if ($actId){
                        $this->ActivityModel->addActivityCategoryId($actId, $catId);
                    }
                }

                $_SESSION['messages'][] = "La sélection d'activités pour " . $catName . " a bien été mise à jour";
            }

            $this->MenuModel->deleteMenuCategoryLinkByCategoryId($catId);

            if (isset($post['men_ids'])){
                foreach ($post['men_ids'] as $menId){
                    if ($menId){
                        $this->MenuModel->createMenuCategoryLink($menId, $catId);
                    }
                }

                $_SESSION['messages'][] = "La sélection de menus pour " . $catName . " a bien été mise à jour";
            }

            redirect('/AdminCategoryController/listCategory', 'refresh');

        }else{
            $catId          = $this->input->get('id');
            $category       = $this->categoryCheck($catId);

            $activities     = $this->ActivityModel->getActivitiesByCategoryId($catId);
            $menus          = $this->MenuModel->getMenusByCategoryId($catId);
        }

        $this->_params['data']['category']    = $category;
        $this->_params['data']['cat_activities']  = $activities;
        $this->_params['data']['cat_menus']       = $menus;
        $this->load->view('template', $this->_params);
    }

    public function deleteCategory(){
        $catId          = $this->input->get('id');
        $this->categoryCheck($catId);

        $this->ActivityModel->removeActivityCategoryId($catId);
        $this->MenuModel->deleteMenuCategoryLinkByCategoryId($catId);
        $this->CategoryModel->deleteCategoryById($catId);

        $_SESSION['messages'][] = "La suppression de la catégorie id " . $catId . " a bien été effectuée";

        redirect('/AdminCategoryController/listCategory', 'refresh');
    }

    public function categoryCheck($catId){
        if (!$catId){
            $_SESSION['messages'][] = "Aucun identifiant de catégorie renseigné";

            $this->redirectHome();
        }

        $category = $this->CategoryModel->getCategoryById($catId);

        if (!$category){
            $_SESSION['messages'][] = "Cette categorie n'existe pas";

            $this->redirectHome();
        }

        return $category;
    }
}