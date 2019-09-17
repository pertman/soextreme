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

    public function getPromotionById($proId){
        $sql = "SELECT * FROM promotion WHERE pro_id = ? ";
        $query = $this->db->query($sql, array($proId));
        return $query->row_array();
    }

    public function updatePromotion($post){
        $proDiscountType    = $post['pro_discount_type'];
        $proDiscountValue   = $post['pro_discount_value'];

        $proIsMainPage      = 0;
        $proHourStart       = null;
        $proHourEnd         = null;
        $proDateStart       = null;
        $proDateEnd         = null;
        $proDiscountFix     = null;
        $proDiscountPercent = null;
        $proAgeMin          = null;
        $proAgeMax          = null;
        $proActIds          = null;
        $proCatIds          = null;

        if ($proDiscountType == 'pro_discount_fix'){
            $proDiscountFix     = $proDiscountValue;
        }else{
            $proDiscountPercent = $proDiscountValue;
        }

        if($post['pro_type'] == 'age'){
            if ($post['pro_age_min']){
                $proAgeMin = $post['pro_age_min'];
            }
            if ($post['pro_age_max']){
                $proAgeMax = $post['pro_age_max'];
            }
        }else{
            if (isset($post['pro_is_main_page'])){
                $proIsMainPage = 1;
            }

            if ($post['date_range']) {
                $dateRangeArray = explode(' - ', $post['date_range']);
                $proDateStart  = $dateRangeArray[0];
                $proDateEnd    = $dateRangeArray[1];
            }

            if ($post['pro_hour_start']){
                $proHourStart = $post['pro_hour_start'];
            }

            if ($post['pro_hour_end']){
                $proHourEnd = $post['pro_hour_end'];
            }

            if (isset($post['act_ids'])){
                $proActIds = implode(',', $post['act_ids']);
            }

            if (isset($post['cat_ids'])){
                $proCatIds = implode(',', $post['cat_ids']);
            }
        }

        $sql = 'UPDATE `promotion` SET `pro_type` = ?, `pro_name` = ?, `pro_description` = ?, `pro_is_main_page` = ?, `pro_hour_start` = ?, `pro_hour_end` = ?, `pro_date_start` = ?, `pro_date_end` = ?, `pro_discount_fix` = ?, `pro_discount_percent` = ?, `pro_age_min` = ?, `pro_age_max` = ?, `pro_priority` = ?, `pro_act_ids` = ?, `pro_cat_ids` = ? WHERE pro_id = ?';
        return $this->db->query($sql, array($post['pro_type'], $post['pro_name'], $post['pro_description'], $proIsMainPage, $proHourStart, $proHourEnd, $proDateStart, $proDateEnd, $proDiscountFix, $proDiscountPercent, $proAgeMin, $proAgeMax, $post['pro_priority'], $proActIds, $proCatIds, $post['pro_id']));
    }

    public function deletePromotion($proId){
        $sql = 'DELETE FROM promotion WHERE pro_id = ?';
        return $this->db->query($sql, array($proId));
    }
}