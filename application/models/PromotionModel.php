<?php

class PromotionModel extends CI_Model{
    public function createAgePromotion($post){
        $proDiscountType    = $post['pro_discount_type'];
        $proDiscountValue   = $post['pro_discount_value'];

        $sql = 'INSERT INTO `promotion` (`pro_name`, `pro_description`, `pro_type`, `' . $proDiscountType . '`, `pro_age_min`, `pro_age_max`, `pro_is_active`) VALUES (?,?,?,?,?,?,?)';
        return $this->db->query($sql, array($post['pro_name'], $post['pro_description'], $post['pro_type'], $proDiscountValue, $post['pro_age_min'], $post['pro_age_max'], '1'));
    }

    public function createOtherPromotion($post, $startDate, $endDate, $timeStart, $timeEnd, $actIds, $catIds){
        $proDiscountType    = $post['pro_discount_type'];
        $proDiscountValue   = $post['pro_discount_value'];

        $sql = 'INSERT INTO `promotion` (`pro_name`, `pro_description`, `pro_type`, `' . $proDiscountType . '`, `pro_priority`, `pro_date_start`, `pro_date_end`, `pro_hour_start`, `pro_hour_end`, `pro_is_active`, `pro_act_ids`, `pro_cat_ids`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)';
        return $this->db->query($sql, array($post['pro_name'], $post['pro_description'], $post['pro_type'], $proDiscountValue, $post['pro_priority'], $startDate, $endDate, $timeStart, $timeEnd, '1', $actIds, $catIds));
    }

    public function getAllPromotions(){
        $sql = "SELECT * FROM promotion";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getDateAndHoursPromotion($date, $startHour, $endHour){
        $sql = "SELECT * FROM promotion WHERE pro_is_active = 1 AND pro_date_start IS NOT NULL AND pro_date_end IS NOT NULL AND pro_date_start <= ? AND pro_date_end >= ? 
                                        OR pro_is_active = 1 AND pro_hour_start IS NOT NULL AND pro_hour_end IS NULL AND pro_hour_start >= ?
                                        OR pro_is_active = 1 AND pro_hour_start IS NULL AND pro_hour_end IS NOT NULL AND pro_hour_end <= ?
                                        OR pro_is_active = 1 AND pro_hour_start IS NOT NULL AND pro_hour_end IS NOT NULL AND pro_hour_start >= ? AND pro_hour_end <= ?
                                        OR pro_is_active = 1 AND pro_date_start IS NULL AND pro_date_end IS NULL AND pro_hour_start IS  NULL AND pro_hour_end IS NULL AND pro_type = ?
                                        ORDER BY pro_priority";
        $query = $this->db->query($sql,array($date,$date,$startHour,$endHour,$startHour,$endHour,'other'));
        return $query->result_array();
    }

    public function getAgePromotions(){
        $sql = "SELECT * FROM promotion WHERE pro_is_active = 1 && pro_type = ?";
        $query = $this->db->query($sql,array('age'));
        return $query->result_array();
    }

    public function getPromotionsByPromotionIds($promotionIds){
        $sql = "SELECT * FROM promotion WHERE pro_id IN (" . $promotionIds . ")";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}