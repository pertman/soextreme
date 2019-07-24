<?php

class AdminModel extends CI_Model {
    public function getAdminByEmail($email){
        $sql = "SELECT * FROM user WHERE usr_email = ? and ust_id = ?";
        $query = $this->db->query($sql, array($email, 4));
        return $query->row_array();
    }
}
