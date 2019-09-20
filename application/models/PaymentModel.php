<?php

class PaymentModel extends CI_Model{
    public function createPayment($resId, $total, $bankResponse){
        $sql = 'INSERT INTO `payment` (`pay_amount`, `pay_state`, `pay_bank_response`, `res_id`) VALUES (?,?,?,?)';
        return $this->db->query($sql, array($total, 'validated', $bankResponse, $resId));
    }
}