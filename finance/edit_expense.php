<?php 
$dsn="mysql:host=localhost;dbname=finance_db;charset=utf8";
$pdo=new PDO($dsn,'root','');


$sql="UPDATE `daily_account` SET ";
    $tmp=[];
    foreach($_POST as $key => $value){
        $tmp[]="`$key`='$value'";
    }
$sql .= join(", ",$tmp);
        /* ``='{}',
        `time`='{$_POST['time']}',
        `currency`='{$_POST['currency']}',
        `item`='{$_POST['item']}',
        `store`='{$_POST['store']}',
        `category`='{$_POST['category']}',
        `amount`='{$_POST['amount']}',
        `payment_method`='{$_POST['payment_method']}',
        `type`='{$_POST['type']}',
        `account`='{$_POST['account']}',
        `note`='{$_POST['note']}' */

$sql .=" WHERE `id`='{$_POST['id']}'";

//echo $sql;
$pdo->exec($sql);
header("location:index.php");

