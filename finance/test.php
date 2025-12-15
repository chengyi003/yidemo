<?php 
include_once "sql.php";

//print_r($rows);
// foreach($rows as $r){
//     echo $r['id'].'. '.$r['item'].'<br>';
// }

$row = find_id('daily_account', 5);

print_r($row);

$vv =find_column('daily_account', [
    'id' => 3,
    'category' => 5
]);

print_r($vv);