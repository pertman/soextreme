<?php

class PromotionModel extends CI_Model{
    public function createAgePromotion($post){
        $proDiscountType    = $post['pro_discount_type'];
        $proDiscountValue   = $post['pro_discount_value'];

        $sql = 'INSERT INTO `promotion` (`pro_name`, `pro_description`, `pro_type`, `' . $proDiscountType . '`, `pro_age_min`, `pro_age_max`, `pro_is_active`) VALUES (?,?,?,?,?,?,?)';
        return $this->db->query($sql, array($post['pro_name'], $post['pro_description'], $post['pro_type'], $proDiscountValue, $post['pro_age_min'], $post['pro_age_max'], '1'));
    }

    public function createOtherPromotion($post, $startDate, $endDate, $actIds, $catIds, $usrIds){
        $proDiscountType    = $post['pro_discount_type'];
        $proDiscountValue   = $post['pro_discount_value'];

        $sql = 'INSERT INTO `promotion` (`pro_name`, `pro_description`, `pro_type`, `' . $proDiscountType . '`, `pro_date_start`, `pro_date_end`, `pro_hour_start`, `pro_hour_end`, `pro_cart_amount`, `pro_code`, `pro_max_use`, `pro_is_active`, `pro_act_ids`, `pro_cat_ids`, `pro_user_ids`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        return $this->db->query($sql, array($post['pro_name'], $post['pro_description'], $post['pro_type'], $proDiscountValue, $startDate, $endDate, $post['timeStart'], $post['timeEnd'], $post['pro_cart_amount'], $post['pro_code'], $post['pro_max_use'], '1', $actIds, $catIds, $usrIds));
    }

    public function getAllPromotions(){
        $sql = "SELECT * FROM promotion";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}