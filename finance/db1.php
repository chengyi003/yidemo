<?php
class DB {

    protected $pdo;
    protected $table;

    function __construct($table = 'daily_account'){
        // InfinityFree 資料庫設定
        $db_host = "sql313.infinityfree.com";
        $db_name = "if0_40652646_finance_db";
        $db_user = "if0_40652646";
        $db_pass = "JhFQJ6uIxYaIkv"; // 你的 vPanel 密碼

        $dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8";
        $this->pdo = new PDO($dsn, $db_user, $db_pass);
        $this->table = $table;
    }

    function all($where = [], $desc = " ORDER BY `id` ASC"){
        $sql = "SELECT * FROM `$this->table`";
        $tmp = [];

        if(is_array($where) && count($where) > 0){
            foreach($where as $key => $value){
                $tmp[] = "`$key` = '$value'";
            }
            $sql .= " WHERE " . join(" AND ", $tmp);
        } else if(is_string($where) && !empty($where)){
            $sql .= " " . $where;
        }

        $sql .= " " . $desc;
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    function find_id($id){
        $sql = "SELECT * FROM `$this->table` WHERE `id` = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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
}
