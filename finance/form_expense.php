<?php 
require_once("db.php");
session_start();
// 建 daily_account 的 DB 物件
$AccountDB = new DB("daily_account");
$CategoryDB = new DB("category");

// 商店
$stores = $AccountDB->all([], "GROUP BY `store`");

// 帳戶
$accounts = $AccountDB->all([], "GROUP BY `account`");

// 類別
$categories = $CategoryDB->all();
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>💰 新增消費</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>💰 新增消費</h1>
            <form action="insert_expense.php" method="post">
                
                <!-- 基本資訊 -->
                <div class="form-section">
                    <h3>基本資訊</h3>
                            <input type="hidden" name="member_id" value="<?php echo $_SESSION['user']['id']; ?>">

                    <div class="form-row">
                        <div class="form-group">
                            <label>日期 *</label>
                            <input type="date" name="date" required>
                        </div>
                        <div class="form-group">
                            <label>時間 *</label>
                            <input type="time" name="time" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>幣別 *</label>
                        <div class="radio-group">
                            <label><input type="radio" name="currency" value="TWD" checked> 台幣</label>
                            <label><input type="radio" name="currency" value="USD"> 美元</label>
                            <label><input type="radio" name="currency" value="AUD"> 澳幣</label>
                            <label><input type="radio" name="currency" value="JPY"> 日圓</label>
                            <label><input type="radio" name="currency" value="CNY"> 人民幣</label>
                        </div>
                    </div>
                </div>

                <!-- 消費詳情 -->
                <div class="form-section">
                    <h3>消費詳情</h3>

                    <div class="form-group">
                        <label>品項 *</label>
                        <input type="text" name="item" required>
                    </div>

                    <div class="form-group">
                        <label>商店 *</label>
                        <select name="store" required>
                            <option value="">-- 請選擇商店 --</option>
                            <?php 
                                foreach($stores as $s){
                                    echo "<option value='{$s['store']}'>{$s['store']}</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>金額 *</label>
                            <input type="number" name="payment" step="0.01" min="0" required>
                        </div>

                        <div class="form-group">
                            <label>類別 *</label>
                            <select name="category" required>
                                <option value="">-- 請選擇類別 --</option>
                                <?php 
                                    foreach($categories as $cat){
                                        echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 付款方式 -->
                <div class="form-section">
                    <h3>付款方式</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>付款方式 *</label>
                            <select name="payment_method" required>
                                <option value="">-- 請選擇 --</option>
                                <option value="1">信用卡</option>
                                <option value="2">現金</option>
                                <option value="3">電子支付</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>付款帳戶 *</label>
                            <select name="account" required>
                                <option value="">-- 請選擇帳戶 --</option>
                                <?php 
                                    foreach($accounts as $acc){
                                        echo "<option value='{$acc['account']}'>{$acc['account']}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 類型 -->
                <div class="form-section">
                    <h3>交易類型</h3>
                    <div class="radio-group">
                        <label><input type="radio" name="type" value="支出" checked> 支出</label>
                        <label><input type="radio" name="type" value="收入"> 收入</label>
                    </div>
                </div>

                <!-- 備註 -->
                <div class="form-section">
                    <h3>備註</h3>
                    <textarea name="desc" rows="3" placeholder="輸入備註..."></textarea>
                </div>

                <div class="form-buttons">
                    <input type="submit" value="💾 新增消費">
                    <input type="reset" value="🔄 重置">
                </div>
            </form>

            <div class="back-link">
                <a href="index.php">🔙 返回首頁</a>
            </div>
        </div>
    </div>
</body>
</html>
