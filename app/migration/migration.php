<?php
include_once dirname(__DIR__).'/config/database.php';
include_once 'query.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
    // use exec() because no results are returned
    $sql;
    foreach ($query_array as $query){
        $sql = $query;
        $conn->exec($query);
    }

    echo "Table created successfully\n";
  } catch(PDOException $e) {
    echo $sql . " " . $e->getMessage()."\n";
  }
  
  $conn = null;
?>