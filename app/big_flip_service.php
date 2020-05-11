<?php

include dirname(__DIR__).'/app/model/account.php';
include dirname(__DIR__).'/app/model/disbursement.php';

class BigFlip{
    private const URL = "https://nextar.flip.id";
    private const secret = "HyzioY7LP6ZoO7nTYKbG8O4ISkyWnX1JvAEVAhtWKZumooCzqp41";
    private const content_type = "Content-Type: application/x-www-form-urlencoded";
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    private function build_header(){
        $encoded_secret = base64_encode(self::secret.":");
        $authorization = "Authorization: Basic ".$encoded_secret;
        $header = array(
            $authorization,
            self::content_type,
        );

        return $header;
    }

    private function post_disbursement($account_number, $bank_code, $amount, $remark){
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => sprintf(self::URL."/%s", "disburse"), 
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => $this->build_header(),
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POSTFIELDS => sprintf("account_number=%d&bank_code=%s&amount=%d&remark=%s", 
                $account_number, $bank_code, $amount, $remark
            )
        );

        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, TRUE);
    }

    private function getDisbursementUpdate($transaction_id){
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => sprintf(self::URL."/%s/%s", "disburse", $transaction_id), 
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $this->build_header(),
            CURLOPT_RETURNTRANSFER => TRUE
        );

        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, TRUE);
    }
    
    public function disburse($account_number, $bank_code, $amount, $remark){
        try {
            $this->conn->beginTransaction();
            $account = new Account($this->conn);

            if (!$account_number || !$bank_code || !$amount || !$remark){
                throw new Exception("Parameter can not be empty");
            }
        
            if (!$account->isAccountNumberExists($account_number)){
                throw new Exception("Account number does not exist");
            }

            if (!$account->isBankCodeExists($account_number, $bank_code)){
                throw new Exception("Bank code does not exist");
            }

            $balance = $account->getBalance($account_number);
            if ($balance < $amount){
                throw new Exception("Balance is not sufficient");
            }

            $newBalance = $balance - $amount;
            $account->updateBalance($account_number, $newBalance);
            $response = $this->post_disbursement($account_number, $bank_code, $amount, $remark);

            $disbursement = new Disbursement($this->conn);
            $disbursement->insertDisbursement(
                $response["id"], $response["amount"], 
                $response["status"], $response["timestamp"],
                $response["account_number"], $response["beneficiary_name"],
                $response["remark"], $response["receipt"],
                $response["time_served"], $response["fee"] 
            );

            $this->conn->commit();
            return sprintf(
                "Disbursement ID: %d\nStatus: %s\nReceipt: %s\nFee: %f\nTime Served: %s\nTimestamp: %s\n\nSUCCESS\n", 
                $response["id"], $response["status"], $response["receipt"], 
                $response["fee"], $response["time_served"], $response["timestamp"]
            );

        } catch (Exception $e) {
            $this->conn->rollBack();
            return $e->getMessage()."\n\nFAILED\n";
        }

    }

    public function updateDisbursement($transaction_id){
        try {
            $this->conn->beginTransaction();

            if (!$transaction_id){
                throw new Exception("Transaction ID can not be empty");
            }

            $response = $this->getDisbursementUpdate($transaction_id);

            $disbursement = new Disbursement($this->conn);
            $disbursement->updateDisbursement(
                $response["id"], $response["status"], $response["timestamp"],
                $response["receipt"], $response["time_served"]
            );

            $this->conn->commit();
            return sprintf(
                "Disbursement ID: %d\nStatus: %s\nReceipt: %s\nTime Served: %s\nTimestamp: %s\n\nSUCCESS\n", 
                $response["id"], $response["status"], $response["receipt"], 
                $response["time_served"], $response["timestamp"]
            );

        } catch (Exception $e) {
            $this->conn->rollBack();
            return $e->getMessage()."\n\nFAILED\n";
        }
    }
}


?>
