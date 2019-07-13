<?php

class UserModel extends CI_Model {

    public function createUser($firstname, $lastname, $email, $password, $phone, $userType){
        $now = date('Y-m-d H:i:s');
        $sql = 'INSERT INTO user (usr_firstname, usr_lastname, usr_email, usr_password, usr_phone, usr_created_at, usr_updated_at, usr_status, ust_id) VALUES (?,?,?,?,?,?,?,?,?)';
        return $this->db->query($sql, array($firstname, $lastname, $email, $password, $phone, $now, $now, 'active', $userType));
    }

    public function getUserByEmail($email){
        $sql = "SELECT * FROM user WHERE usr_email = ?";
        $query = $this->db->query($sql, array($email));
        return $query->row_array();
    }

    public function getUserTypes(){
        $sql = "SELECT * FROM user_type";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getUserTypeById($id){
        $sql = "SELECT * FROM user_type WHERE ust_id = ?";
        $query = $this->db->query($sql,array($id));
        return $query->row_array();
    }
}
