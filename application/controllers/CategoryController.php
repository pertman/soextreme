<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CategoryController extends MY_Controller{

    protected $_params;

    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headData']['title'] = 'Nouvelle Catégorie';
        $this->_params['view'] = 'categoryForm';
    }

    public function createCategory(){
        //@TODO DUPPLICATE CONTROLLERS FOR ADMIN OR USER TO DO IT ON CONSTRUCT
        if (getCurrentUserType() != getAdminUserType()){
            $this->redirectHome();
        }

        if ($post = $this->input->post()) {

            $catName = $post['cat_name'];

            $existingCategory = $this->CategoryModel->getCategoryByName($catName);

            if ($existingCategory){
                $this->_params['messages'][] = "Une catégorie avec ce nom existe déjà";
                $this->load->view('template', $this->_params);
            }else{
                $this->CategoryModel->createCategory($catName);

                $_SESSION['messages'][] = "La catégorie ". $catName . " a bien été crée";

                $this->redirectHome();
            }
        }else{
            $this->load->view('template', $this->_params);
        }
    }
}