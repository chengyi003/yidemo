<?php

$dsn="mysql:host=localhost;dbname=finance_db;charset=utf8";
$pdo=new PDO($dsn,'root','');
$sql="DELETE FROM `daily_account` WHERE `id`='{$_GET['id']}'";
$pdo->exec($sql);
header("location:index.php");
?>