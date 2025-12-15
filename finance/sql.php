<?php
//all();

function all($table='daily_account',$where=[],$desc=' ORDER BY `id` ASC'){
    $dsn="mysql:host=localhost;charset=utf8;dbname=finance_db";
    $pdo=new PDO($dsn,'root','');
    
    $sql="SELECT * FROM $table ";

    if(is_array($where) && count($where)>0){
        foreach($where as $key => $value){
            $tmp[]="`$key`='$value'";
        }
        $sql .= " WHERE ".join(" && ",$tmp) ;
    }else if(is_string($where) && !empty($where)){
          $sql .= $where  ;
    }

    $sql .= $desc;


   echo $sql;
    echo "<hr>";
    
    $rows=$pdo->query($sql)->fetchALL(PDO::FETCH_ASSOC);
    
    return $rows;
}
function find_id($table, $id){
    $dsn = "mysql:host=localhost;charset=utf8;dbname=finance_db";
    $pdo = new PDO($dsn, 'root', '');

    $sql = "SELECT * FROM `$table` WHERE `id` = ? LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function find_column($table, $column){
    $dsn = "mysql:host=localhost;charset=utf8;dbname=finance_db";
    $pdo = new PDO($dsn, 'root', '');

    $where = [];
    $values = [];

    // 如果 column 是數字 → 預設查 id
    if(is_numeric($column)){
        $where[] = "`id` = ?";
        $values[] = $column;
    }
    // 如果 column 是陣列 → 多條件 AND 查詢
    else if(is_array($column)){
        foreach($column as $col => $val){
            $where[] = "`$col` = ?";
            $values[] = $val;
        }
    }
    else {
        // 其他類型（字串）→ 當成 id 查詢
        $where[] = "`id` = ?";
        $values[] = $column;
    }

    // 組 WHERE
    $whereSQL = implode(' AND ', $where);
    $sql = "SELECT * FROM `$table` WHERE $whereSQL";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>