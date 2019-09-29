<?php

class CommentModel extends CI_Model{
    public function createComment($comText, $actId, $usrId, $commentedComId = null){
        $now            = date('Y-m-d H:i:s');
        $sql = 'INSERT INTO comment (com_text, com_commented_com_id, com_created_at, com_updated_at, act_id, usr_id) VALUES (?,?,?,?,?,?)';
        return $this->db->query($sql, array($comText, $commentedComId, $now, $now, $actId, $usrId));
    }

    public function setCommentImagePath($key, $path, $comId){
        $sql = 'UPDATE comment SET ' . $key . ' = ? WHERE com_id = ?';
        return $this->db->query($sql, array($path, $comId));
    }

    public function getFirstLevelComments($actId){
        $sql = "SELECT * FROM comment
                LEFT JOIN user ON comment.usr_id = user.usr_id
                WHERE comment.com_commented_com_id IS NULL AND comment.act_id = ? ORDER BY com_id DESC";
        $query = $this->db->query($sql, array($actId));
        return $query->result_array();
    }

    public function getSecondLevelCommentByComId($comId){
        $sql = "SELECT * FROM comment
                LEFT JOIN user ON comment.usr_id = user.usr_id
                WHERE comment.com_commented_com_id = ?";
        $query = $this->db->query($sql, array($comId));
        return $query->result_array();
    }

    public function getCommentByComId($comId){
        $sql = "SELECT * FROM comment
                LEFT JOIN user ON comment.usr_id = user.usr_id
                WHERE comment.com_id = ?";
        $query = $this->db->query($sql, array($comId));
        return $query->result_array();
    }

    public function getCommentById($comId){
        $sql = "SELECT * FROM comment WHERE com_id = ?";
        $query = $this->db->query($sql, array($comId));
        return $query->row_array();
    }

    public function deleteSecondLevelComments($comId){
        $sql = 'DELETE FROM comment WHERE com_commented_com_id = ?';
        return $this->db->query($sql, array($comId));
    }

    public function deleteComment($comId){
        $sql = 'DELETE FROM comment WHERE com_id = ?';
        return $this->db->query($sql, array($comId));
    }

    public function getLastCommentPictures(){
        $sql = 'SELECT * FROM comment WHERE com_picture_path IS NOT NULL ORDER BY com_id DESC LIMIT 10';
        $query = $this->db->query($sql, array());
        return $query->result_array();
    }
}