<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ActivityController extends MY_Controller{

    protected $_params;

    public function __construct()
    {
        parent::__construct();
        parent::init();

        $this->_params = parent::getBaseParams();
        $this->_params['headData']['title'] = 'Liste des activités';
        $this->_params['view'] = 'activityList';
    }

    public function listActivities(){
    //@TODO put pramas headData and view in each functions
        if (isCurrentUserAdmin()){
            $this->_params['data']['activities']  = $this->ActivityModel->getAllActivities();
        }else{
            $this->_params['data']['activities']  = $this->ActivityModel->getActiveActivities();
        }
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

        $this->_params['data']['alreadyDoneActivity'] = false;

        if (isCurrentUserCustomer()){
            if ($this->TicketModel->getActivityUsedTicketsByUser($_SESSION['user']['id'], $activity['act_id'])){
                $this->_params['data']['alreadyDoneActivity'] = true;
            }
        }

        $comments = $this->CommentModel->getFirstLevelComments($actId);

        foreach ($comments as $key => $comment){
            $comments[$key]['comments'] = $this->CommentModel->getSecondLevelCommentByComId($comment['com_id']);
        }

        $this->_params['data']['comments'] = $comments;

        $this->load->view('template', $this->_params);
    }
    
    public function addActivityComment(){
        $post = $this->input->post();
        if (!$post){
            $this->redirectHome();
        }

        if (!isset($post['com_text']) || !isset($post['act_id']) || !isset($post['usr_id'])){
            $_SESSION['messages'][] = "Tous les paramêtres requis ne sont pas renseignés";
            $this->redirectHome();
        }

        if ($post['usr_id'] != $_SESSION['user']['id']){
            $_SESSION['messages'][] = "Utilisateur invalide";
            $this->redirectHome();
        }

        $this->CommentModel->createComment($post['com_text'], $post['act_id'], $post['usr_id']);

        $comId = $this->db->insert_id();

        if (isset($_FILES['com_picture_path'])){
            $this->uploadCommentPhoto('com_picture_path', $_FILES['com_picture_path'], $comId);
        }

        $_SESSION['messages'][] = "Commentaire effectué";
        redirect('/ActivityController/seeActivity?id='.$post['act_id'], 'refresh');
    }

    public function addActivityCommentLevel2(){
        $post = $this->input->post();

        if (!$post){
            die(json_encode(array('status' => 'error', 'message' => 'Tous les paramêtres requis ne sont pas renseignés')));
        }

        if (!isset($post['com_text']) || !isset($post['act_id']) || !isset($post['usr_id']) || !isset($post['com_commented_com_id'])){
            die(json_encode(array('status' => 'error', 'message' => 'Tous les paramêtres requis ne sont pas renseignés')));
        }

        if ($post['usr_id'] != $_SESSION['user']['id']){
            die(json_encode(array('status' => 'error', 'message' => 'Utilisateur invalide')));
        }

        $this->CommentModel->createComment($post['com_text'], $post['act_id'], $post['usr_id'], $post['com_commented_com_id']);

        $comId = $this->db->insert_id();

        $comment = $this->CommentModel->getCommentByComId($comId);

        die(json_encode(array('status' => 'valid', 'message' => 'Commentaire effectué', 'comment' => $comment)));
    }

    public function uploadCommentPhoto($key, $file, $comId){
        $filename   = $file['name'];
        if ($filename){
            $path       = 'uploads/comments-pictures/' . $comId;
            $config['allowed_types'] = '*';
            $config['upload_path'] = $path;
            $this->load->library('upload', $config);

            if(!is_dir('uploads/') || !is_dir('uploads/comments-pictures/') || !is_dir($path) ){
                mkdir( $path, 0777, true );
            }

            $realPath = $path.'/'.$key.'.jpg';
            if (file_exists($realPath)){
                unlink($realPath);
            }

            if ($this->upload->do_upload($key)) {
                rename($path.'/'.$filename, $realPath);
                $this->CommentModel->setCommentImagePath($key, $realPath, $comId);
            }
        }
    }

    public function deleteComment(){
        $post = $this->input->post();

        if (!isset($post['comId']) || !isset($post['actId'])){
            die(json_encode(array('status' => 'error', 'message' => 'Tous les paramêtres requis ne sont pas présent')));
        }

        if (isCurrentUserCustomer()){
            $comment = $this->CommentModel->getCommentById($post['comId']);

            if (!$comment){
                die(json_encode(array('status' => 'error', 'message' => 'Ce commentaire n\'existe pas')));
            }

            if ($comment['com_commented_com_id']){
                $firstLevelComment = $this->CommentModel->getCommentById($comment['com_commented_com_id']);

                if ($firstLevelComment['usr_id'] != $_SESSION['user']['id'] && $comment['usr_id'] != $_SESSION['user']['id']){
                    die(json_encode(array('status' => 'error', 'message' => 'Vous ne pouvez pas effectuer cette action')));
                }
            }else{
                if ($comment['usr_id'] != $_SESSION['user']['id']){
                    die(json_encode(array('status' => 'error', 'message' => 'Vous ne pouvez pas effectuer cette action')));
                }
            }
        }

        $this->CommentModel->deleteSecondLevelComments($post['comId']);
        $this->CommentModel->deleteComment($post['comId']);

        die(json_encode(array('status' => 'valid', 'message' => 'Commentaire supprimé', 'com_id' => $post['comId'])));
    }
}