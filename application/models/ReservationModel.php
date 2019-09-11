<?php

class ReservationModel extends CI_Model{
    public function createReservation($resDate, $resTimeSlot, $resParticipantNb, $tslId, $usrId){
        $now            = date('Y-m-d H:i:s');
        $sql = 'INSERT INTO `reservation` (`res_date`, `res_time_slot`, `res_participant_nb`, `res_created_at`, `res_updated_at`, `res_status`, `tsl_id`, usr_id) VALUES (?,?,?,?,?,?,?,?)';
        return $this->db->query($sql, array($resDate, $resTimeSlot, $resParticipantNb, $now, $now, 'validated', $tslId, $usrId));
    }

    public function getReservationsByDate($date){
        $sql = "SELECT * FROM reservation WHERE res_date = ?";
        $query = $this->db->query($sql, array($date));
        return $query->result_array();
    }
}