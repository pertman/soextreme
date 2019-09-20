<?php

class PlanningModel extends CI_Model{
    public function createPlanning($dateStart, $dateEnd, $actId){
        $sql = 'INSERT INTO `planning` (`pla_date_start`, `pla_date_end`, `act_id`) VALUES (?,?,?)';
        return $this->db->query($sql, array($dateStart, $dateEnd, $actId));
    }

    public function createTimeSlot($hourStart, $hourEnd,$dayIndex){

        $sql = 'INSERT INTO `time_slot` (`tsl_hour_start`, `tsl_hour_end`, `tsl_day_index`) VALUES (?,?,?)';
        return $this->db->query($sql, array($hourStart, $hourEnd, $dayIndex));
    }

    public function createTimeSlotPlanningLink($plaId, $tslId){

        $sql = 'INSERT INTO `time_slot_planning_link` (`pla_id`,`tsl_id`) VALUES (?,?)';
        return $this->db->query($sql, array($plaId, $tslId));
    }

    public function getAllPlanningItemsForActivity($actId){
        $sql = "SELECT * FROM planning
                LEFT JOIN time_slot_planning_link ON planning.pla_id = time_slot_planning_link.pla_id
                LEFT JOIN time_slot ON time_slot_planning_link.tsl_id = time_slot.tsl_id
                WHERE act_id = ?";
        $query = $this->db->query($sql, array($actId));
        return $query->result_array();
    }

    public function getPlanningTimeSlotsById($tslId){
        $sql = "SELECT * FROM time_slot 
                WHERE tsl_id = ?";
        $query = $this->db->query($sql, array($tslId));
        return $query->row_array();
    }

    public function getPlanningByPlanningId($plaId){
        $sql = "SELECT * FROM planning
                WHERE planning.pla_id = ?";
        $query = $this->db->query($sql, array($plaId));
        return $query->row_array();
    }

    public function getTimeSlotsByPlanningId($plaId){
        $sql = "SELECT * FROM time_slot
                LEFT JOIN time_slot_planning_link ON time_slot.tsl_id = time_slot_planning_link.tsl_id
                WHERE time_slot_planning_link.pla_id = ?";
        $query = $this->db->query($sql, array($plaId));
        return $query->result_array();
    }

    public function updatePlanning($dateStart, $dateEnd, $plaId){
        $sql = 'UPDATE `planning` SET `pla_date_start` = ?, `pla_date_end` = ? WHERE `pla_id` = ?';
        return $this->db->query($sql, array($dateStart, $dateEnd, $plaId));
    }

    public function deleteTimeSlotLinkById($tspId){
        $sql = "DELETE FROM  time_slot_planning_link WHERE tsp_id = ?";
        return $this->db->query($sql, array($tspId));
    }

    public function deleteTimeSlotById($tslId){
        $sql = "DELETE FROM  time_slot WHERE tsl_id = ?";
        return $this->db->query($sql, array($tslId));
    }
}