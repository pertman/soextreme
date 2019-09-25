<?php

class AdminRequestModel extends CI_Model{
    public function createRequest($post){
        $now = date('Y-m-d H:i:s');
        $sql = 'INSERT INTO admin_request (adr_subject, adr_description, adr_created_at, adr_updated_at, usr_id) VALUES (?,?,?,?,?)';
        return $this->db->query($sql, array($post['adr_subject'], $post['adr_description'], $now, $now, $_SESSION['user']['id']));
    }

    public function getRequestsByStatus($status){
        $sql = "SELECT * FROM admin_request LEFT JOIN `user` ON admin_request.usr_id = `user`.usr_id WHERE adr_status = ? ORDER BY adr_id DESC";
        $query = $this->db->query($sql, array($status));
        return $query->result_array();
    }

    public function closeRequest($adrId){
        $now = date('Y-m-d H:i:s');
        $sql = 'UPDATE admin_request SET adr_status = ?, adr_updated_at = ? WHERE adr_id = ?';
        return $this->db->query($sql, array('closed', $now, $adrId));
    }
}