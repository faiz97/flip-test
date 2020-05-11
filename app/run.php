<?php
include_once dirname(__DIR__).'/app/config/database.php';
include_once dirname(__DIR__).'/app/big_flip_service.php';

const CREATE_DISBURSEMENT = "create_disbursement";
const UPDATE_DISBURSEMENT = "update_disbursement";

if (count($argv) < 2){
    echo sprintf("You have to input command that you want to do (%s or %s).\n",
        CREATE_DISBURSEMENT, UPDATE_DISBURSEMENT
    );  
}

$db = new Database();
$conn = $db->getConnection();

$big_flip = new BigFlip($conn);

if ($argv[1] === CREATE_DISBURSEMENT) {
    echo $big_flip->disburse($argv[2], $argv[3], $argv[4], $argv[5]);
} elseif ($argv[1] === UPDATE_DISBURSEMENT){
    echo $big_flip->updateDisbursement($argv[2]);
} else {
    echo sprintf("Command invalid. You have to input the valid comment (%s or %s).\n",
        CREATE_DISBURSEMENT, UPDATE_DISBURSEMENT
    );
}

?>