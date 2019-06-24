<?php
class TestModel extends CI_Model {
    public function getUserTypeById($id){
        $sql = "SELECT * FROM user_type WHERE ust_id = ?";
        $query = $this->db->query($sql,array($id));
        return $query->row_array();
    }
}
