<?php
class Database{
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    private function setConfig(){
        $configs = include('config.php');
        $db_configs = $configs['database'];

        $this->host = $db_configs['host'];
        $this->db_name = $db_configs['db_name'];   
        $this->username = $db_configs['username'];
        $this->password = $db_configs['password'];
    }

    // get the database connection
    public function getConnection(){
        $this->setConfig();
        $this->conn = null;
  
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
    }
}
?>