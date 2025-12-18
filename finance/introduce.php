<?php
// about.php
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
<meta charset="UTF-8">
<title>📘 系統技術介紹</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="sidebar.css">

<style>
.about-container{
    max-width:980px;
    margin:60px auto;
    background:#fff;
    padding:52px;
    border-radius:22px;
    box-shadow:0 12px 40px rgba(0,0,0,.12);
}
.about-container h1{
    text-align:center;
    margin-bottom:44px;
}
.section{
    margin-bottom:42px;
}
.section h2{
    margin-bottom:14px;
    padding-left:12px;
    border-left:6px solid #667eea;
}
.tech-list{
    list-style:none;
    padding-left:0;
}
.tech-list li{
    padding:8px 0;
    font-size:16px;
}
.check{
    color:#2ecc71;
    font-weight:bold;
    margin-right:8px;
}
.note{
    color:#666;
    font-size:14px;
    margin-top:8px;
    padding-left:26px;
}
</style>
</head>

<body>
<?php include "sidebar.php"; ?>

<div class="about-container">
    <h1>📘 網站系統技術介紹</h1>

    <!-- 系統概述 -->
    <div class="section">
        <h2>系統概述</h2>
        <p>
            本系統為一套以 <strong>PHP + MySQL</strong> 為核心的網站專案，
            實作會員註冊、登入、忘記密碼、個人資料管理與消費紀錄報表，
            並著重於<strong>流程完整性、安全性與使用者體驗</strong>。
        </p>
    </div>

    <!-- 忘記密碼 -->
    <div class="section">
        <h2>🔐 忘記密碼功能</h2>
        <ul class="tech-list">
            <li><span class="check">✔</span>產生一次性 <strong>reset_token</strong></li>
            <li><span class="check">✔</span>設定重設期限 <strong>reset_expire</strong></li>
            <li><span class="check">✔</span>透過 Email 寄送含 token 的重設連結</li>
            <li><span class="check">✔</span>重設完成後立即清除 token，避免重複使用</li>
            <li><span class="check">✔</span><strong>reset_password.php</strong> 驗證 token 與有效期限</li>
            <li><span class="check">✔</span>前端 JavaScript 即時驗證（含錯誤 / 成功變色提示）</li>
            <li><span class="check">✔</span><strong>reset_password_save.php</strong> 使用 password_hash() 更新密碼</li>
        </ul>
        <div class="note">
            ※ 忘記密碼流程符合實務常見「一次性連結＋時效控管」安全設計。
        </div>
    </div>

    <!-- 註冊 -->
    <div class="section">
        <h2>📝 會員註冊功能</h2>
        <ul class="tech-list">
            <li><span class="check">✔</span>帳號、密碼、Email 欄位完整驗證</li>
            <li><span class="check">✔</span>使用正則表達式檢查格式與長度</li>
            <li><span class="check">✔</span>密碼需包含英文與數字，且不可與帳號相同</li>
            <li><span class="check">✔</span>確認密碼一致性檢查</li>
            <li><span class="check">✔</span>即時錯誤提示與輸入框顏色變化</li>
            <li><span class="check">✔</span>後端再次驗證，避免繞過前端檢查</li>
        </ul>
    </div>

    <!-- Email -->
    <div class="section">
        <h2>📧 Email 寄送機制</h2>
        <ul class="tech-list">
            <li><span class="check">✔</span>使用 PHPMailer 寄送系統信件</li>
            <li><span class="check">✔</span>支援 HTML 信件格式</li>
            <li><span class="check">✔</span>用於忘記密碼重設連結通知</li>
            <li><span class="check">✔</span>寄信帳密集中管理，避免寫死於程式碼中</li>
        </ul>
    </div>

    <!-- 報表 -->
    <div class="section">
        <h2>📊 報表與資料查詢</h2>
        <ul class="tech-list">
            <li><span class="check">✔</span>依條件查詢個人消費 / 紀錄資料</li>
            <li><span class="check">✔</span>後端以 SQL 條件組合方式進行篩選</li>
            <li><span class="check">✔</span>報表僅限登入會員存取</li>
            <li><span class="check">✔</span>支援資料整理與後續匯出功能</li>
        </ul>
    </div>

    <!-- 安全 -->
    <div class="section">
        <h2>🔐 機密資訊管理</h2>
        <ul class="tech-list">
            <li><span class="check">✔</span>資料庫與寄信帳密集中於設定檔管理</li>
            <li><span class="check">✔</span>機密設定檔未納入版本控制（.gitignore）</li>
            <li><span class="check">✔</span>避免敏感資訊外洩風險</li>
        </ul>
    </div>

    <!-- Session -->
    <div class="section">
        <h2>👤 個人頁面與 Session 管理</h2>
        <ul class="tech-list">
            <li><span class="check">✔</span>登入後使用 PHP Session 記錄會員狀態</li>
            <li><span class="check">✔</span>個人頁面僅限登入狀態存取</li>
            <li><span class="check">✔</span>未登入者自動導向登入頁</li>
        </ul>
    </div>

    <!-- Cookie -->
    <div class="section">
        <h2>🍪 保持登入機制</h2>
        <ul class="tech-list">
            <li><span class="check">✔</span>使用 Cookie 實作保持登入功能</li>
            <li><span class="check">✔</span>Cookie 僅存放識別用 token</li>
            <li><span class="check">✔</span>登出時同步清除 Cookie 與 Session</li>
        </ul>
    </div>

    <!-- Form -->
    <div class="section">
        <h2>📋 表單送出方式</h2>
        <ul class="tech-list">
            <li>
                <span class="check">✔</span><strong>GET</strong>
                <div class="note">
                    用於查詢與搜尋等不涉及機密資料的操作。
                </div>
            </li>
            <li>
                <span class="check">✔</span><strong>POST</strong>
                <div class="note">
                    用於註冊、登入、重設密碼等資料提交。
                </div>
            </li>
            <li>
                <span class="check">✔</span><strong>AJAX POST + JSON</strong>
                <div class="note">
                    使用 fetch API 傳送 JSON，進行非同步驗證與局部更新。
                </div>
            </li>
        </ul>
        <div class="note">
            ※ 後端依 Content-Type 分別處理 $_GET、$_POST 或 JSON 請求。
        </div>
    </div>

    <!-- 技術 -->
    <div class="section">
        <h2>🛠 使用技術</h2>
        <ul class="tech-list">
            <li><span class="check">✔</span>PHP（原生 PHP、PDO）</li>
            <li><span class="check">✔</span>MySQL（資料表設計與關聯）</li>
            <li><span class="check">✔</span>HTML / CSS（版面與視覺設計）</li>
            <li><span class="check">✔</span>JavaScript（表單驗證與互動）</li>
            <li><span class="check">✔</span>password_hash / password_verify</li>
        </ul>
    </div>

</div>

</body>
</html>
