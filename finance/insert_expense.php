<?php 
$dsn="mysql:host=localhost;dbname=finance_db;charset=utf8";
$pdo=new PDO($dsn,'root','');

$col="`" . join('`,`',array_keys($_POST)) . "`";
$val="'". join("','",$_POST) . "'";

$sql="INSERT INTO `daily_account` ($col) VALUES ($val)";
echo $sql;
$pdo->exec($sql);

header("location:index.php");


