<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminMenuController extends MY_Controller{

    protected $_params;

    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headData']['title'] = 'Gestion du Menu';
        $this->_params['view'] = 'menuForm';

        if (!isCurrentUserAdmin()){
            $this->redirectHome();
        }
    }

    public function createMenu(){

        $this->_params['headData']['title'] = 'Création de Menu';
        $this->_params['view'] = 'menuForm';

        if ($post = $this->input->post()) {
            $menuName = $post['men_name'];

            $isTopMenu = (isset($post['is_top_menu'])) ? 1 : 0;

            $this->MenuModel->createMenu($menuName, $isTopMenu);

            $menuId = $this->db->insert_id();

            if ($isTopMenu){
                $this->MenuModel->disableOldTopMenu($menuId);
            }

            foreach($post['cat_ids'] as $catId){
                $this->MenuModel->createMenuCategoryLink($menuId, $catId);
            }

            $_SESSION['messages'][] = "Le menu ". $menuName . " a bien été crée";

            redirect('/AdminMenuController/listMenu', 'refresh');
        }else{
            $this->_params['data']['categories']  = $this->CategoryModel->getActiveCategories();
            $this->load->view('template', $this->_params);
        }
    }

    public function listMenu(){

        $this->_params['view'] = 'menuList';
        $this->_params['data']['menus']  = $this->MenuModel->getAllMenus();
        $this->load->view('template', $this->_params);
    }
    
    public function editCategoryMenuIndex(){

        $this->_params['headData']['title'] = 'Edition des index';
        $this->_params['view'] = 'menuItem';

        if ($post = $this->input->post()){
            $menId = $post['men_id'];

            foreach ($post['cat_ids'] as $catId => $category){
                $this->MenuModel->updateCategoryMenuIndex($menId, $catId, $category['mcl_index']);
            }

            $_SESSION['messages'][] = "La modification du menu id " . $menId . " a bien été effectuée";

            redirect('/AdminMenuController/listMenu', 'refresh');
        }else{
            $menuId = $this->input->get('id');

            $menu = $this->menuCheck($menuId);

            $this->_params['data']['menu']          = $menu;
            $this->_params['data']['categories']    = $this->MenuModel->getMenuCategories($menuId);
            $this->load->view('template', $this->_params);
        }
    }
    
    public function updateMenu(){

        $this->_params['headData']['title'] = 'Edition de Menu';
        $this->_params['view'] = 'menuForm';

        if ($post = $this->input->post()) {
            $menId = $post['men_id'];

            $menu = $this->menuCheck($menId);

            $isTopMenu = (isset($post['is_top_menu'])) ? 1 : 0;

            if ($isTopMenu){
                $this->MenuModel->setMenuAsMainMenu($menId);
                $this->MenuModel->disableOldTopMenu($menId);
            }

            $this->MenuModel->updateMenuName($menId, $post['men_name']);

            $this->MenuModel->deleteMenuCategoryLinkByMenuId($menId);

            foreach($post['cat_ids'] as $catId){
                $this->MenuModel->createMenuCategoryLink($menId, $catId);
            }

            $_SESSION['messages'][] = "Le menu ". $menu['men_name'] . " a bien été crée";

            redirect('/AdminMenuController/listMenu', 'refresh');
        }else{
            $menId = $this->input->get('id');

            $menu = $this->menuCheck($menId);

            $this->_params['data']['menu']              = $menu;
            $this->_params['data']['categories']        = $this->CategoryModel->getActiveCategories();
            $this->_params['data']['men_categories']    = $this->MenuModel->getMenuCategories($menId);
            $this->load->view('template', $this->_params);
        }
    }

    public function deleteMenu(){

        $menuId = $this->input->get('id');

        $this->menuCheck($menuId);

        $this->MenuModel->deleteMenuCategoryLinkByMenuId($menuId);
        $this->MenuModel->deleteMenu($menuId);

        $_SESSION['messages'][] = "La suppression du menu id " . $menuId . " a bien été effectuée";

        redirect('/AdminMenuController/listMenu', 'refresh');
    }

    public function menuCheck($menId){

        if (!$menId){
            $_SESSION['messages'][] = "Aucun identifiant de menu renseigné";

            redirect('/AdminMenuController/listMenu', 'refresh');
        }

        $menu = $this->MenuModel->getMenuById($menId);

        if (!$menu){
            $_SESSION['messages'][] = "Ce menu n'existe pas";

            redirect('/AdminMenuController/listMenu', 'refresh');
        }

        return $menu;
    }
}