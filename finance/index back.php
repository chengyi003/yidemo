<?php
$dsn="mysql:host=localhost;dbname=finance_db;charset=utf8";
$pdo=new PDO($dsn,'root','');

?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>💰 消費記錄系統</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💰 記帳系統</h1>
            <p>智慧消費管理，掌握每一筆支出</p>
            <div class="action-buttons">
                <a href="form_expense.php" class="btn btn-primary">➕ 新增消費</a>
                <a href="report.php" class="btn btn-primary">📊 財務報表</a>
            </div>
        </div>

        <div class="table-wrapper">
<?php
$expenses=$pdo->query("SELECT `daily_account`.*,
                              `category`.`name` AS `category_name`,
                              `payment_method`.`name` AS `payment_method_name`
                        FROM `daily_account`,
                             `category`,
                             `payment_method`
                        WHERE `daily_account`.`category`=`category`.`id`
                                AND `daily_account`.`payment_method`=`payment_method`.`id`
                    Order By `date` DESC, 
                             `time` DESC")->fetchALL(PDO::FETCH_ASSOC);

if (count($expenses) > 0) {
    echo '<div class="table-header">';
    echo '<h2>📊 消費記錄詳情</h2>';
    echo '</div>';
    echo '<div class="table-content">';
    echo '<table>';
    echo '<thead><tr>
            <th>品項</th>
            <th>金額</th>
            <th>商店</th>
            <th>分類</th>
            <th>貨幣</th>
            <th>付款方式</th>
            <th>類型</th>
            <th>付款帳戶</th>
            <th>日期</th>
            <th>時間</th>
            <th>操作</th>
        </tr></thead>';
    echo '<tbody>';
    
    foreach($expenses as $exp) {
        $type_class = $exp['type'] === '收入' ? 'status-income' : 'status-expense';
        echo "<tr>
                <td>{$exp['item']}</td>
                <td class='amount'>NT\${$exp['payment']}</td>
                <td>{$exp['store']}</td>
                <td><span class='category-badge'>{$exp['category_name']}</span></td>
                <td>{$exp['currency']}</td>
                <td><span class='payment-badge'>{$exp['payment_method_name']}</span></td>
                <td><span class='{$type_class}'>{$exp['type']}</span></td>
                <td><span class='account-badge'>{$exp['account']}</span></td>
                <td>{$exp['date']}</td>
                <td>{$exp['time']}</td>
                <td>
                    <a class='btn-edit' href='edit.php?id={$exp['id']}'>編輯</a>
                    <a class='btn-delete' href='javascript:del({$exp['id']})'>刪除</a>
                </td>
            </tr>";
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    echo '</div>';
} else {
    echo '<div class="table-wrapper">';
    echo '<div class="empty-state">';
    echo '<h3>📭 還沒有消費記錄</h3>';
    echo '<p>點擊「新增消費」按鈕開始記錄您的消費</p>';
    echo '<a href="form_expense.php" class="btn btn-primary">➕ 新增消費</a>';
    echo '</div>';
    echo '</div>';
}
?>

        <div class="footer">
            <p>💼 記帳系統 | 智慧財務管理</p>
        </div>
    </div>

</body>
<script>
function del(id){
    
    if(confirm(`確定要刪除編號為 ${id} 的消費紀錄嗎?`)){
        location.href=`delete.php?id=${id}`;
    }
}


</script>
</html>
