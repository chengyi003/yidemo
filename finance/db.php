<?php
class DB {

    protected $pdo;
    protected $table;

    // 建構子（預設連 daily_account）
    function __construct($table = 'daily_account'){
        $dsn = "mysql:host=localhost;charset=utf8;dbname=finance_db";
        $this->pdo = new PDO($dsn, 'root', '');
        $this->table = $table;
    }

    // 取得全部資料
    function all($where = [], $desc = " ORDER BY `id` ASC"){
        $sql = "SELECT * FROM `$this->table`";
        $tmp = [];

        if(is_array($where) && count($where) > 0){
            foreach($where as $key => $value){
                $tmp[] = "`$key` = '$value'";
            }
            $sql .= " WHERE " . join(" AND ", $tmp);
        } 
        else if(is_string($where) && !empty($where)){
            $sql .= " " . $where;
        }

        $sql .= " " . $desc;

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // find_id（查 id 單筆）
    function find_id($id){
        $sql = "SELECT * FROM `$this->table` WHERE `id` = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // find_column（id、字串、陣列）
    function find_column($column){
        $where = [];
        $values = [];

        if(is_numeric($column)){
            $where[] = "`id` = ?";
            $values[] = $column;
        }
        else if(is_array($column)){
            foreach($column as $col => $val){
                $where[] = "`$col` = ?";
                $values[] = $val;
            }
        }
        else {
            $where[] = "`id` = ?";
            $values[] = $column;
        }

        $whereSQL = join(" AND ", $where);
        $sql = "SELECT * FROM `$this->table` WHERE $whereSQL";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ⭐ 新增 insert 方法（整合你的版本）
    function insert($array){
        $sql = "INSERT INTO `{$this->table}` ";

        $keys = array_keys($array);

        $sql .= "(`" . join("`,`", $keys) . "`)";
        $sql .= " VALUES ('" . join("','", $array) . "')";

        // echo $sql; // debug 用

        return $this->pdo->exec($sql);
    }
}
