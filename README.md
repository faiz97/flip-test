# flip-test
Back End test for flip.id

## Set Up DB Variable
Set up your DB variable in `/flip-test/app/config/config.php`
```
<?php
return array(
    'database' => array(
        'host' => <your_host>,
        'db_name' => <your db_name>,
        'username' => <your db username>,
        'password' => <your db password>
    )
);
?>
```
## Migration
Run migration script by entering `flip-test` folder in your terminal then type `php app/migration/migrate.php` in your terminal. The migration script will create:
1. Schema named `flip_test`
2. Table named `Account` and `Disbursement`
3. Row in `Account` table with attributes `account_number="123456789"`, `bank_code="bni"`, and `balance=10000`

## Running Program
First, you have to enter `flip-test` folder in your terminal.

If you want to run create disbursement service:
`php app/run.php create_disbursement <account_number> <bank_code> <amount> <remark>`

If you want to run update disbursement service:
`php app/run.php update_disbursement <transaction_id>`
