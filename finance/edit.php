<?php  
require "DB.php";  
// JhFQJ6uIxYaIkv
// 建立 daily_account 的 DB 物件
$Daily = new DB('daily_account');
$Cate  = new DB('category');

// 取得這筆 id 的資料
$exp = $Daily->find_id($_GET['id']);
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>💰 編輯消費</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-container form-container-wide">
            <h1>💰 編輯消費</h1>
            <form action="edit_expense.php" method="post">
                <input type="hidden" name="id" value="<?=$exp['id'];?>">

                <!-- 第一行：日期、時間、幣別 -->
                <div class="form-grid-3">
                    <div class="form-group">
                        <label for="date">日期 *</label>
                        <input type="date" id="date" name="date" value="<?=$exp['date'];?>" required>
                    </div>
                    <div class="form-group">
                        <label for="time">時間 *</label>
                        <input type="time" id="time" name="time" value="<?=$exp['time'];?>" required>
                    </div>
                    <div class="form-group">
                        <label for="currency">幣別 *</label>
                        <select id="currency" name="currency" required>
                            <option value="TWD" <?=($exp['currency']=='TWD')?'selected':'';?>>台幣</option>
                            <option value="USD" <?=($exp['currency']=='USD')?'selected':'';?>>美元</option>
                            <option value="AUD" <?=($exp['currency']=='AUD')?'selected':'';?>>澳幣</option>
                            <option value="JPY" <?=($exp['currency']=='JPY')?'selected':'';?>>日圓</option>
                            <option value="CNY" <?=($exp['currency']=='CNY')?'selected':'';?>>人民幣</option>
                        </select>
                    </div>
                </div>

                <!-- 第二行：品項、商店 -->
                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="item">品項 *</label>
                        <input type="text" id="item" name="item" value="<?=$exp['item'];?>" required>
                    </div>
                    <div class="form-group">
                        <label for="store">商店 *</label>
                        <select name="store" id="store" required>
                            <option value="">-- 請選擇商店 --</option>
                            <?php 
                                $stores = $Daily->all([], "GROUP BY `store`");
                                foreach($stores as $store){
                                    $sel = ($exp['store']==$store['store'])?'selected':'';
                                    echo "<option value='{$store['store']}' $sel>{$store['store']}</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- 第三行：金額、類別、付款方式、帳戶 -->
                <div class="form-grid-4">
                    <div class="form-group">
                        <label for="payment">金額 *</label>
                        <input type="number" id="payment" name="payment" value="<?=$exp['payment'];?>" step="0.01" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="category">類別 *</label>
                        <select name="category" id="category" required>
                            <option value="">-- 請選擇 --</option>
                            <?php 
                                $categories = $Cate->all();
                                foreach($categories as $cat){
                                    $sel = ($exp['category']==$cat['id'])?'selected':'';
                                    echo "<option value='{$cat['id']}' $sel>{$cat['name']}</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="payment_method">付款方式 *</label>
                        <select name="payment_method" id="payment_method" required>
                            <option value="">-- 請選擇 --</option>
                            <option value="1" <?=($exp['payment_method']=='1')?'selected':'';?>>信用卡</option>
                            <option value="2" <?=($exp['payment_method']=='2')?'selected':'';?>>現金</option>
                            <option value="3" <?=($exp['payment_method']=='3')?'selected':'';?>>電子支付</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="account">帳戶 *</label>
                        <select name="account" id="account" required>
                            <option value="">-- 請選擇 --</option>
                            <?php 
                                $accounts = $Daily->all([], "GROUP BY `account`");
                                foreach($accounts as $acc){
                                    $sel = ($exp['account']==$acc['account'])?'selected':'';
                                    echo "<option value='{$acc['account']}' $sel>{$acc['account']}</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- 第四行：交易類型、備註 -->
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>交易類型 *</label>
                        <div class="radio-group-inline">
                            <div class="radio-item">
                                <input type="radio" id="type_expense" name="type" value="支出" <?=($exp['type']=='支出')?'checked':'';?>>
                                <label for="type_expense">支出</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="type_income" name="type" value="收入" <?=($exp['type']=='收入')?'checked':'';?>>
                                <label for="type_income">收入</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="desc">備註說明</label>
                        <textarea id="desc" name="desc" style="resize: vertical;"><?=$exp['desc'];?></textarea>
                    </div>
                </div>

                <!-- 按鈕 -->
                <div class="form-buttons">
                    <input type="submit" value="💾 保存修改">
                    <input type="reset" value="🔄 重置">
                    <a href="index.php" class="btn btn-secondary">🔙 返回</a>
                </div>

            </form>
        </div>
    </div>
</body>
</html>
