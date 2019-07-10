<?php


class ActivityModel extends CI_Model{

    public function getCategory(){
        $this->load->database();
        $sql = 'SELECT `cat_name`,`cat_id` FROM `category` WHERE `cat_status`=1';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}