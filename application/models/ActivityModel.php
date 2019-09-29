<?php

class ActivityModel extends CI_Model{

    public function getAllActivities(){
        $sql = "SELECT * FROM activity";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getPrivateActivities(){
        $act_status = 'private';
        $sql = "SELECT * FROM activity WHERE act_status= ?";
        $query = $this->db->query($sql, array($act_status));
        return $query->result_array();
    }

    public function getNonPrivateActivities(){
        $act_status = 'private';
        $sql = "SELECT * FROM activity WHERE act_status <> ?";
        $query = $this->db->query($sql, array($act_status));
        return $query->result_array();
    }

    public function getFilteredActivities($post){

        $sql = "SELECT * FROM activity";

        $condWord = ' WHERE';

        if (isCurrentUserCustomer()){
            $sql .= $condWord." act_status <> 'private'";
            $condWord = ' AND';
        }

        if ($post['act_name']){
            $sql .= $condWord." act_name like '%" . $post['act_name'] . "%'";
            $condWord = ' AND';
        }
        if ($post['act_level']){
            $sql .= $condWord." act_level = '" . $post['act_level'] . "'";
            $condWord = ' AND';
        }
        if ($post['cat_id']){
            $sql .= $condWord." cat_id = '" . $post['cat_id'] . "'";
            $condWord = ' AND';
        }
        if ($post['act_participant_nb']){
            $sql .= $condWord." act_participant_nb >= '" . $post['act_participant_nb'] . "'";
            $condWord = ' AND';
        }
        if ($post['act_required_age']){
            $sql .= $condWord." act_required_age <= '" . $post['act_required_age'] . "'";
            $condWord = ' AND';
        }
        if ($post['act_handicapped_accessibility'] == 'yes'){
            $sql .= $condWord." act_handicapped_accessibility = 1";
        }

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getUnavailableActivities(){
        $act_status = 'unavailable';
        $sql = "SELECT * FROM activity WHERE act_status= ?";
        $query = $this->db->query($sql, array($act_status));
        return $query->result_array();
    }

    public function getActivityById($act_id){
        $sql = "SELECT * FROM activity WHERE act_id= ?";
        $query = $this->db->query($sql, array($act_id));
        return $query->row_array();
    }

    public function createActivity($post){
        $now                        = date('Y-m-d H:i:s');
        $category                   = ($post['cat_id']) ? $post['cat_id'] : null;
        $isSpecialOffer             = (isset($post['act_is_special_offer'])) ? 1 : 0;
        $isHandicappedAccessibility = (isset($post['act_handicapped_accessibility'])) ? 1 : 0;

        $sql = 'INSERT INTO activity (act_name, act_description, act_resume, act_level, act_handicapped_accessibility, act_is_special_offer, act_description_special_offer,act_participant_nb, act_monitor_nb, act_operator_nb, act_duration, act_base_price, act_required_age, act_street, act_city, act_zipcode, act_country, act_created_at, act_updated_at, act_status, cat_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        return $this->db->query($sql, array($post['act_name'], $post['act_description'], $post['act_resume'], $post['act_level'], $isHandicappedAccessibility, $isSpecialOffer, $post['act_description_special_offer'],  $post['act_participant_nb'], $post['act_monitor_nb'], $post['act_operator_nb'], $post['act_duration'], $post['act_base_price'], $post['act_required_age'], $post['act_street'], $post['act_city'], $post['act_zipcode'], $post['act_country'], $now, $now, $post['act_status'], $category));
    }

    public function getActivitiesByCategoryId($catId){
        $sql = "SELECT * FROM activity WHERE cat_id = ? AND act_status = ?";
        $query = $this->db->query($sql, array($catId, 'active'));
        return $query->result_array();
    }

    public function addActivityCategoryId($actId, $catId){
        $sql = 'UPDATE activity SET cat_id = ? WHERE act_id =?';
        return $this->db->query($sql, array($catId, $actId));
    }

    public function updateActivity($post){
        $now            = date('Y-m-d H:i:s');
        $category       = ($post['cat_id']) ? $post['cat_id'] : null;
        $isSpecialOffer = (isset($post['act_is_special_offer'])) ? 1 : 0;
        $isHandicappedAccessibility = (isset($post['act_handicapped_accessibility'])) ? 1 : 0;

        $sql = 'UPDATE activity SET act_name = ?, act_description = ?, act_resume = ?, act_level = ?, act_handicapped_accessibility = ?, act_is_special_offer = ?, act_description_special_offer = ?, act_participant_nb = ?, act_monitor_nb = ?, act_operator_nb = ?, act_duration = ?, act_base_price = ?, act_required_age = ?, act_street = ?, act_city = ?, act_zipcode = ?, act_country = ?, act_updated_at = ?, act_status = ?, cat_id = ? WHERE act_id = ?';
        return $this->db->query($sql, array($post['act_name'], $post['act_description'], $post['act_resume'], $post['act_level'], $isHandicappedAccessibility, $isSpecialOffer, $post['act_description_special_offer'], $post['act_participant_nb'], $post['act_monitor_nb'], $post['act_operator_nb'], $post['act_duration'], $post['act_base_price'], $post['act_required_age'], $post['act_street'], $post['act_city'], $post['act_zipcode'], $post['act_country'], $now, $post['act_status'], $category, $post['act_id']));
    }

    public function removeActivityCategoryId($catId){
        $sql = 'UPDATE activity SET cat_id = ? WHERE cat_id = ?';
        return $this->db->query($sql, array(NULL, $catId));
    }

    public function setActivityImagePath($key, $path, $actId){
        $sql = 'UPDATE activity SET ' . $key . ' = ? WHERE act_id = ?';
        return $this->db->query($sql, array($path, $actId));
    }

    public function getMainPageActivities(){
        $act_status = 'private';
        $sql = "SELECT * FROM activity WHERE act_status <> ? AND act_is_special_offer = ?";
        $query = $this->db->query($sql, array($act_status, 1));
        return $query->result_array();
    }

    public function updateActivityNoteSumAndCount($sum, $count, $actId){
        $sql = 'UPDATE activity SET act_note_sum = ?, act_note_count = ? WHERE act_id = ?';
        return $this->db->query($sql, array($sum, $count, $actId));
    }

    public function getMainPagePopularActivities(){
        $act_status = 'private';
        $sql = "SELECT *, (act_note_sum / act_note_count) AS act_note FROM activity WHERE act_status <> ? ORDER BY act_note DESC LIMIT 4";
        $query = $this->db->query($sql, array($act_status));
        return $query->result_array();
    }
}