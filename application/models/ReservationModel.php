<?php

class ReservationModel extends CI_Model{
    public function createReservation($resDate, $resTimeSlot, $resParticipantNb, $tslId, $usrId, $actId){
        $now            = date('Y-m-d H:i:s');
        $sql = 'INSERT INTO `reservation` (`res_date`, `res_time_slot`, `res_participant_nb`, `res_created_at`, `res_updated_at`, `res_status`, `tsl_id`, `usr_id`, `act_id`) VALUES (?,?,?,?,?,?,?,?,?)';
        return $this->db->query($sql, array($resDate, $resTimeSlot, $resParticipantNb, $now, $now, 'validated', $tslId, $usrId, $actId));
    }

    public function getReservationsByDate($date, $actId){
        $sql = "SELECT * FROM reservation WHERE res_date = ? AND act_id = ?";
        $query = $this->db->query($sql, array($date, $actId));
        return $query->result_array();
    }

    public function getReservationById($resId){
        $sql = "SELECT * FROM reservation WHERE res_id = ?";
        $query = $this->db->query($sql, array($resId));
        return $query->row_array();
    }

    public function cancelReservation($resId){
        $now = date('Y-m-d H:i:s');
        $sql = 'UPDATE reservation SET res_status = ?, `res_updated_at` = ? WHERE `res_id` = ?';
        return $this->db->query($sql, array('cancelled', $now, $resId));
    }

    public function getReservationsNumberForTimeSlot($date, $timeSlot, $actId){
        $sql = "SELECT SUM(res_participant_nb) as reservation_nb FROM reservation WHERE res_date = ? AND res_time_slot = ? AND act_id = ?";
        $query = $this->db->query($sql, array($date, $timeSlot, $actId));
        return $query->row_array();
    }

    public function getReservationsByUserId($usrId){
        $sql = "SELECT * FROM reservation
                LEFT JOIN payment ON reservation.res_id = payment.res_id
                WHERE usr_id = ?";
        $query = $this->db->query($sql, array($usrId));
        return $query->result_array();
    }

    public function getCancelledReservations(){
        $sql = "SELECT * FROM reservation
                LEFT JOIN payment ON reservation.res_id = payment.res_id
                LEFT JOIN `user` ON reservation.usr_id = `user`.usr_id
                WHERE reservation.res_status = ?";
        $query = $this->db->query($sql, array('cancelled'));
        return $query->result_array();
    }

    public function getGiftReservations($usrEmail, $usrId){
        $sql = "SELECT * FROM reservation
                LEFT JOIN ticket_reservation_link ON reservation.res_id = ticket_reservation_link.res_id
                LEFT JOIN ticket ON ticket_reservation_link.tic_id = ticket.tic_id
                WHERE ticket.tic_is_gift = ? AND ticket.tic_email = ? AND reservation.usr_id <> ?";
        $query = $this->db->query($sql, array(1, $usrEmail, $usrId));
        return $query->result_array();
    }
}