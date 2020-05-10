<?php
$set_invalid_date = "SET GLOBAL sql_mode = 'ALLOW_INVALID_DATES';";

$create_schema = "CREATE SCHEMA IF NOT EXISTS `flip_test` DEFAULT CHARACTER SET utf8 ;
USE `flip_test` ;";

$create_account_db = "CREATE TABLE IF NOT EXISTS `flip_test`.`Account` (
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `bank_code` VARCHAR(45) NOT NULL,
    `account_number` VARCHAR(45) NOT NULL,
    `balance` DOUBLE NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `account_number_UNIQUE` (`account_number` ASC))
    ENGINE = InnoDB;";

$create_disbursement_db = "CREATE TABLE IF NOT EXISTS `flip_test`.Disbursement (
    `id` BIGINT NOT NULL,
    `account_number` VARCHAR(45) NOT NULL,
    `amount` DOUBLE NULL,
    `transaction_status` VARCHAR(10) NULL,
    `beneficiary_name` VARCHAR(45) NULL,
    `remark` VARCHAR(45) NULL,
    `receipt` VARCHAR(255) NULL,
    `time_served` TIMESTAMP NULL,
    `fee` FLOAT NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `account_number_idx` (`account_number` ASC),
    CONSTRAINT `account_number`
      FOREIGN KEY (`account_number`)
      REFERENCES `flip_test`.`Account` (`account_number`)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION)
    ENGINE = InnoDB;";

$create_account_instance = "INSERT INTO Account (bank_code, account_number, balance)
VALUES ('bni', '123456789', 10000);";

$query_array = [$set_invalid_date, $create_schema, $create_account_db, $create_disbursement_db, $create_account_instance]
?>