<?php

class NewsletterModel extends CI_Model{

    public function getNewsletterByEmail($email){
        $sql = "SELECT * FROM newsletter WHERE new_email = ?";
        $query = $this->db->query($sql, array($email));
        return $query->row_array();
    }

    public function createNewsletter($email){
        $sql = 'INSERT INTO newsletter (new_email) VALUES (?)';
        return $this->db->query($sql, array($email));
    }
}