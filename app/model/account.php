<?php
class Account{
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function isAccountNumberExists($account_number) {
        $query = "SELECT * FROM flip_test.Account WHERE account_number='".$account_number."';";
        $stmt = $this->conn->prepare($query); 
        $stmt->execute(); 
        $row = $stmt->fetch();

        if ($row){
            return TRUE;
        }

        return FALSE;
    }

    public function isBankCodeExists($account_number, $bank_code) {
        $query = "SELECT * FROM flip_test.Account 
            WHERE account_number='".$account_number."' AND bank_code='".$bank_code."';";
        $stmt = $this->conn->prepare($query); 
        $stmt->execute(); 
        $row = $stmt->fetch();

        if ($row){
            return TRUE;
        }

        return FALSE;
    }

    public function getBalance($account_number) {
        $query = "SELECT balance FROM flip_test.Account WHERE account_number = ".$account_number." LIMIT 1"; 
        $stmt = $this->conn->prepare($query); 
        $stmt->execute(); 
        $row = $stmt->fetch();
        $balance = $row['balance'];

        return $balance;
    }

    public function updateBalance($account_number, $new_balance){
        $query = "UPDATE flip_test.Account SET balance=? WHERE account_number=?;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$new_balance, $account_number]);
    }
}
?>