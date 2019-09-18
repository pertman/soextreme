<?php

class TicketModel extends CI_Model{
    public function createTicket($firstname, $lastname, $age, $giftEmail, $price){
        $now            = date('Y-m-d H:i:s');
        $isGift = ($giftEmail) ? 1 : 0;
        $sql = 'INSERT INTO `ticket` (`tic_firstname`, `tic_lastname`, `tic_age`, `tic_email`, `tic_price`, `tic_is_used`, `tic_is_gift`, `tic_created_at`, `tic_updated_at`, `tic_status`, usr_id) VALUES (?,?,?,?,?,?,?,?,?,?,?)';
        return $this->db->query($sql, array($firstname, $lastname, $age, $giftEmail, $price, 0, $isGift, $now, $now, 'created', null));
    }

    public function createTicketReservationLink($resId, $ticId){
        $sql = 'INSERT INTO `ticket_reservation_link` (`res_id`, `tic_id`) VALUES (?,?)';
        return $this->db->query($sql, array($resId, $ticId));
    }

    public function createTicketPromotionHistory($ticId, $promotion){
        $sql = 'INSERT INTO `ticket_promotion_history` (`tic_id`, `pro_id`, `pro_type`, `pro_name`, `pro_description`, `pro_hour_start`, `pro_hour_end`, `pro_date_start`, `pro_date_end`, `pro_discount_fix`, `pro_discount_percent`, `pro_age_min`, `pro_age_max`, `pro_priority`, `pro_act_ids`, `pro_cat_ids`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        return $this->db->query($sql, array($ticId, $promotion['pro_id'], $promotion['pro_type'], $promotion['pro_name'], $promotion['pro_description'], $promotion['pro_hour_start'], $promotion['pro_hour_end'], $promotion['pro_date_start'], $promotion['pro_date_end'], $promotion['pro_discount_fix'], $promotion['pro_discount_percent'], $promotion['pro_age_min'], $promotion['pro_age_max'], $promotion['pro_priority'], $promotion['pro_act_ids'], $promotion['pro_cat_ids']));
    }

    public function getTicketsByReservationId($resId){
        $sql = "SELECT * FROM ticket
                LEFT JOIN ticket_reservation_link ON ticket.tic_id = ticket_reservation_link.tic_id
                WHERE ticket_reservation_link.res_id = ?";
        $query = $this->db->query($sql, array($resId));
        return $query->result_array();
    }
}