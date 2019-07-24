<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MenuController extends MY_Controller{

    protected $_params;

    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headData']['title'] = 'Gestion du Menu';
        $this->_params['view'] = 'menuForm';
    }

    public function createMenu(){
        //@TODO DUPPLICATE CONTROLLERS FOR ADMIN OR USER TO DO IT ON CONSTRUCT
        if (getCurrentUserType() != getAdminUserType()){
            $this->redirectHome();
        }

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

            redirect('/MenuController/listMenu', 'refresh');
        }else{
            $this->_params['data']['category']  = $this->CategoryModel->getActiveCategories();
            $this->load->view('template', $this->_params);
        }
    }

    public function listMenu(){
        //@TODO DUPPLICATE CONTROLLERS FOR ADMIN OR USER TO DO IT ON CONSTRUCT
        if (getCurrentUserType() != getAdminUserType()){
            $this->redirectHome();
        }

        $this->_params['view'] = 'menuList';
        $this->_params['data']['menus']  = $this->MenuModel->getAllMenus();
        $this->load->view('template', $this->_params);
    }
    
    public function modifyMenu(){
        //@TODO DUPPLICATE CONTROLLERS FOR ADMIN OR USER TO DO IT ON CONSTRUCT
        if (getCurrentUserType() != getAdminUserType()){
            $this->redirectHome();
        }

        $menuId = $this->input->get('id');

        if (!$menuId){
            $_SESSION['messages'][] = "Aucun identifiant de menu renseigné";

            redirect('/MenuController/listMenu', 'refresh');
        }

        $menu = $this->MenuModel->getMenuById($menuId);

        if (!$menu){
            $_SESSION['messages'][] = "Ce menu n'existe pas";

            redirect('/MenuController/listMenu', 'refresh');
        }

        $this->_params['view']                  = 'menuItem';
        $this->_params['data']['menu']          = $menu;
        $this->_params['data']['categories']    = $this->MenuModel->getMenuCategories($menuId);
        $this->load->view('template', $this->_params);
    }
    
    public function update(){
        //@TODO DUPPLICATE CONTROLLERS FOR ADMIN OR USER TO DO IT ON CONSTRUCT
        if (getCurrentUserType() != getAdminUserType()){
            $this->redirectHome();
        }

        if ($post = $this->input->post()){
            $menId = $post['men_id'];

            $isTopMenu = (isset($post['is_top_menu'])) ? 1 : 0;

            if ($isTopMenu){
                $this->MenuModel->setMenuAsMainMenu($menId);
                $this->MenuModel->disableOldTopMenu($menId);
            }

            foreach ($post['cat_ids'] as $catId => $category){
                $this->MenuModel->updateCategoryMenuIndex($menId, $catId, $category['mcl_index']);
            }

            $_SESSION['messages'][] = "La modification du menu id " . $menId . " a bien été effectuée";

            $this->listMenu();
        }
    }

    public function deleteMenu(){
        //@TODO DUPPLICATE CONTROLLERS FOR ADMIN OR USER TO DO IT ON CONSTRUCT
        if (getCurrentUserType() != getAdminUserType()){
            $this->redirectHome();
        }

        $menuId = $this->input->get('id');

        if (!$menuId){
            $_SESSION['messages'][] = "Aucun identifiant de menu renseigné";

            redirect('/MenuController/listMenu', 'refresh');
        }

        $menu = $this->MenuModel->getMenuById($menuId);

        if (!$menu){
            $_SESSION['messages'][] = "Ce menu n'existe pas";

            redirect('/MenuController/listMenu', 'refresh');
        }

        $this->MenuModel->deleteMenuCategoryLink($menuId);
        $this->MenuModel->deleteMenu($menuId);

        $_SESSION['messages'][] = "La suppression du menu id " . $menuId . " a bien été effectuée";

        redirect('/MenuController/listMenu', 'refresh');
    }
}