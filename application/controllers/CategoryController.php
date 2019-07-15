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
        if ($post = $this->input->post()) {

            $catName = $post['cat_name'];

            $existingCategory = $this->CategoryModel->getCategoryByName($catName);

            if ($existingCategory){
                $this->_params['messages'][] = "Une catégorie avec ce nom existe déjà";
                $this->load->view('template', $this->_params);
            }else{
                $this->CategoryModel->createCategory($catName);

                $_SESSION['messages'][] = "La catégorie ". $catName . " à bien été crée";

                $this->redirectHome($this->_params);
            }
        }else{
            $this->load->view('template', $this->_params);
        }
    }
}