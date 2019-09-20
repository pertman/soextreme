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

    public function getUserById($usrId){
        $sql = "SELECT * FROM user WHERE usr_id = ?";
        $query = $this->db->query($sql, array($usrId));
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

    public function getAllUsersFromType($type){
        $sql = "SELECT * FROM user
                LEFT JOIN user_type ON user.ust_id = user_type.ust_id
                WHERE user_type.ust_value = ?";
        $query = $this->db->query($sql, array($type));
        return $query->result_array();
    }

    public function updateInformation($post){
        $now = date('Y-m-d H:i:s');
        $sql = 'UPDATE `user` SET `usr_firstname` = ?, `usr_lastname` = ?, `usr_email` = ?, `usr_phone` = ?, `usr_updated_at` = ? WHERE `usr_id` = ?';
        return $this->db->query($sql, array($post['usr_firstname'], $post['usr_lastname'], $post['usr_email'], $post['usr_phone'], $now, $post['usr_id']));
    }

    public function updatePassword($hashPassword, $usrId){
        $now = date('Y-m-d H:i:s');
        $sql = 'UPDATE `user` SET `usr_password` = ?, `usr_updated_at` = ? WHERE `usr_id` = ?';
        return $this->db->query($sql, array($hashPassword, $now, $usrId));
    }

    public function setProfilePicturePath($key, $path, $usrId){
        $sql = 'UPDATE `user` SET ' . $key . ' = ? WHERE usr_id = ?';
        return $this->db->query($sql, array($path, $usrId));
    }
}
