<?php 
class Disbursement{
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insertDisbursement(
        $id, $amount, $status, 
        $timestamp, $account_number,
        $beneficiary_name, $remark,
        $receipt, $time_served, $fee
    ){
        $query = "INSERT INTO flip_test.Disbursement (id, amount, transaction_status,
        account_number, beneficiary_name, remark,
        receipt, time_served, fee, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

        $values = [
            $id, $amount, $status, 
            $account_number, $beneficiary_name, $remark,
            $receipt, $time_served, $fee, $timestamp, $timestamp
        ];

        $stmt = $this->conn->prepare($query); 
        $stmt->execute($values);
    }

    public function updateDisbursement($id, $status, $timestamp, $receipt, $time_served){
        $query = "UPDATE flip_test.Disbursement 
            SET transaction_status=?, updated_at=?, receipt=?, time_served=? 
            WHERE id=?;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$status, $timestamp, $receipt, $time_served, $id]);
    }

}
?>