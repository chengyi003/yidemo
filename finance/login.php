<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>會員登入</title>

    <!-- 引用你的主風格 -->
    <link rel="stylesheet" href="style.css">
<style>
    .login-card {
        max-width: 420px;
        margin: 80px auto;
        background: rgba(255, 255, 255, 0.98);
        padding: 40px;
        border-radius: 25px;
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.15);
    }

    .login-card h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 2em;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* 統一輸入框 */
    .form-group input {
        width: 100%;
        padding: 14px;
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        font-size: 15px;
        background: #fafafa;
        margin-top: 5px;
        transition: 0.25s;
    }

    .form-group input:focus {
        outline: none;
        border-color: #667eea;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.15);
    }

    .remember-box {
        margin-top: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 15px;
    }

    .remember-box input {
        width: 18px;
        height: 18px;
        accent-color: #667eea;
    }

    /* 登入按鈕 */
    .login-btn {
        width: 100%;
        padding: 14px;
        margin-top: 20px;
        border-radius: 10px;
        font-size: 17px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        transition: all 0.3s ease;
    }

    .login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    /* 下方連結 */
    .extra-links {
        margin-top: 25px;
        display: flex;
        justify-content: space-between;
    }

    .extra-links a {
        font-size: 15px;
        color: #667eea;
        font-weight: 700;
        text-decoration: none;
    }

    .extra-links a:hover {
        text-decoration: underline;
        color: #764ba2;
    }
</style>
<link rel="stylesheet" href="sidebar.css">
</head>

<body>
<?php include "sidebar.php"; ?>
<div class="login-card">
    <h2>會員登入</h2>

    <div class="form-group">
        <label>帳號</label>
        <input type="text" id="account" placeholder="請輸入帳號">
    </div>

    <div class="form-group">
        <label>密碼</label>
        <input type="password" id="password" placeholder="請輸入密碼">
    </div>

    <div class="remember-box">
        <input type="checkbox" id="remember">
        <label for="remember">保持登入</label>
    </div>

    <button class="login-btn" onclick="doLogin()">登入</button>

    <div class="extra-links">
        <a href="register.php">註冊帳號</a>
        <a href="#">忘記密碼？</a>
    </div>
</div>

<script>
function doLogin() {
    const btn = document.querySelector(".login-btn");
    btn.disabled = true;
    btn.textContent = "登入中…";

    let payload = {
        account: document.getElementById("account").value.trim(),
        password: document.getElementById("password").value.trim(),
        remember: document.getElementById("remember").checked ? 1 : 0
    };

    if (!payload.account || !payload.password) {
        alert("帳號與密碼不能為空");
        btn.disabled = false;
        btn.textContent = "登入";
        return;
    }

    fetch("login_check.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === "success") {
            alert("登入成功！");
            location.href = "index.php";
        } else {
            alert(res.msg);
        }
    })
    .catch(err => {
        alert("連線錯誤，請稍後再試");
        console.error(err);
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = "登入";
    });
}
</script>

</body>
<script src="sidebar.js"></script>
</html>
