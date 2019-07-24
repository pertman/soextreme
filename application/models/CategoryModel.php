<?php

class CategoryModel extends CI_Model{

    public function createCategory($catName){
        $now = date('Y-m-d H:i:s');
        $sql = 'INSERT INTO category (cat_name, cat_created_at, cat_updated_at, cat_status) VALUES (?, ?, ?, ?)';
        return $this->db->query($sql, array($catName, $now, $now, 1));
    }

    public function getCategoryByName($name){
        $sql = "SELECT * FROM category WHERE cat_name = ?";
        $query = $this->db->query($sql, array($name));
        return $query->row_array();
    }

    public function getActiveCategories(){
        $sql = 'SELECT `cat_name`,`cat_id` FROM `category` WHERE `cat_status`= ?';
        $query = $this->db->query($sql, array(1));
        return $query->result_array();
    }

    public function getCategoryById($catId){
        $sql = "SELECT * FROM category WHERE cat_id = ?";
        $query = $this->db->query($sql, array($catId));
        return $query->row_array();
    }
}