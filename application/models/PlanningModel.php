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
}