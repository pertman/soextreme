<?php

class ActivityModel extends CI_Model{
    
    public function getActivityByName($name){
        $sql = "SELECT * FROM activity WHERE act_name = ?";
        $query = $this->db->query($sql, array($name));
        return $query->row_array();
    }

    public function getAllActivities(){
        $sql = "SELECT * FROM activity";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getPrivateActivities(){
        //@TODO CHANGE STATUS IN VARCHAR
        $act_status = 1;
        $sql = "SELECT * FROM activity WHERE act_status= ?";
        $query = $this->db->query($sql, array($act_status));
        return $query->result_array();
    }

    public function getActiveActivities(){
        //@TODO CHANGE STATUS IN VARCHAR
        $act_status = 2;
        $sql = "SELECT * FROM activity WHERE act_status= ?";
        $query = $this->db->query($sql, array($act_status));
        return $query->result_array();
    }

    public function getUnavailableActivities(){
        //@TODO CHANGE STATUS IN VARCHAR
        $act_status = 3;
        $sql = "SELECT * FROM activity WHERE act_status= ?";
        $query = $this->db->query($sql, array($act_status));
        return $query->result_array();
    }

    public function getActivityById($act_id){
        $sql = "SELECT * FROM activity WHERE act_id= ?";
        $query = $this->db->query($sql, array($act_id));
        return $query->result_array();
    }

    public function createActivity($post){
        $now            = date('Y-m-d H:i:s');
        $category       = ($post['cat_id']) ? $post['cat_id'] : null;
        $isSpecialOffer = (isset($post['act_is_special_offer'])) ? 1 : 0;

        $sql = 'INSERT INTO activity (act_name, act_description, act_resume, act_is_special_offer, act_description_special_offer, act_monitor_nb, act_operator_nb, act_duration, act_base_price, act_created_at, act_updated_at, act_status, cat_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)';
        return $this->db->query($sql, array($post['act_name'], $post['act_description'], $post['act_resume'], $isSpecialOffer, $post['act_description_special_offer'], $post['act_monitor_nb'], $post['act_operator_nb'], $post['act_duration'], $post['act_base_price'], $now, $now, 1, $category));
    }
}