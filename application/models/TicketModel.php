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

    public function createTicketPromotionLink($ticId, $proId){
        $sql = 'INSERT INTO `ticket_promotion_link` (`tic_id`, `pro_id`) VALUES (?,?)';
        return $this->db->query($sql, array($ticId, $proId));
    }
}