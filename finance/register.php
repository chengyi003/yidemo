<?php
$dsn = "mysql:host=localhost;dbname=finance_db;charset=utf8";
$pdo = new PDO($dsn, 'root', '');
?>

<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔐 註冊帳號｜記帳系統</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="sidebar.css">

    <style>
        .register-container {
            max-width: 480px;
            margin: 40px auto;
            background: rgba(255,255,255,0.95);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .register-container h1 {
            background: linear-gradient(135deg,#667eea,#764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;
            font-size: 2em;
            margin-bottom: 25px;
        }

        .form-group { margin-bottom: 18px; }

        .form-group label {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
            color: #444;
        }

        .form-group label span { color: #dc3545; }

        .form-group input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            background: #fafafa;
            font-size: 15px;
        }

        input.input-error {
            background:#f8d7da !important;
            border:2px solid #dc3545 !important;
            color:#842029 !important;
        }

        input.input-success {
            background:#d1e7dd !important;
            border:2px solid #198754 !important;
            color:#0f5132 !important;
        }

        .error-msg { color:#dc3545;font-size:13px;margin-top:6px; }

        .checkbox-row {
            display:flex;
            gap:10px;
            margin-top:20px;
        }

        .btn-submit {
            width:100%;
            padding:14px;
            font-size:16px;
            font-weight:700;
            background:linear-gradient(135deg,#667eea,#764ba2);
            border:none;
            border-radius:10px;
            color:white;
            margin-top:10px;
            cursor:pointer;
        }

        /* autofill 修正 */
        input:-webkit-autofill {
            -webkit-box-shadow:0 0 0 1000px white inset !important;
        }
        input.input-success:-webkit-autofill {
            -webkit-box-shadow:0 0 0 1000px #d1e7dd inset !important;
        }
        input.input-error:-webkit-autofill {
            -webkit-box-shadow:0 0 0 1000px #f8d7da inset !important;
        }
    </style>

</head>

<body>
<?php include "sidebar.php"; ?>

<div class="register-container">
    <h1>🔐 註冊新帳號</h1>

    <form id="regForm">
        <div class="form-group">
            <label>帳號 (account) <span>*</span></label>
            <input type="text" id="account" placeholder="至少 8 碼" oninput="liveCheck()">
            <div class="error-msg" id="err_account"></div>
        </div>

        <div class="form-group">
            <label>電子郵件 (email) <span>*</span></label>
            <input type="text" id="email" placeholder="example@gmail.com" oninput="liveCheck()">
            <div class="error-msg" id="err_email"></div>
        </div>

        <div class="form-group">
            <label>密碼 (password) <span>*</span></label>
            <input type="password" id="pwd" placeholder="至少 8碼，含英文+數字" oninput="liveCheck()">
            <div class="error-msg" id="err_pwd"></div>
        </div>

        <div class="form-group">
            <label>確認密碼 <span>*</span></label>
            <input type="password" id="pwd2" placeholder="再次輸入密碼" oninput="liveCheck()">
            <div class="error-msg" id="err_pwd2"></div>
        </div>

        <div class="checkbox-row">
            <input type="checkbox" id="agree" onchange="liveCheck()">
            <label for="agree">我已知此網站為教學測試用作品</label>
        </div>
        <div class="error-msg" id="err_agree"></div>

        <button type="button" class="btn-submit" onclick="checkForm()">建立帳號</button>
    </form>
</div>

<script src="sidebar.js"></script>

<script>
/* 規則 */
const emailRule = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const mixRule   = /^(?=.*[a-zA-Z])(?=.*\d).+$/;
const pwdRule   = /^(?=.*[a-zA-Z])(?=.*\d).{8,}$/;

/* UI */
function setError(idInput,idErr,msg){
    let el=document.getElementById(idInput);
    el.className="input-error";
    document.getElementById(idErr).innerHTML=msg;
}
function setSuccess(idInput,idErr){
    let el=document.getElementById(idInput);
    el.className="input-success";
    document.getElementById(idErr).innerHTML="";
}

/* 平時輸入 */
function liveCheck(){
    validate(false);
}

/* 按按鈕 */
function checkForm(){
    validate(true);
}

/* 驗證 */
function validate(doSubmit){
    let pass = true;

    let acc  = document.getElementById("account").value.trim();
    let email= document.getElementById("email").value.trim();
    let pwd  = document.getElementById("pwd").value.trim();
    let pwd2 = document.getElementById("pwd2").value.trim();
    let agreeChecked = document.getElementById("agree").checked;

    /* 帳號 */
    if(acc.length < 8){
        setError("account","err_account","帳號至少 8 碼。");
        pass=false;
    }else if(!mixRule.test(acc)){
        setError("account","err_account","帳號需包含英文 + 數字。");
        pass=false;
    }else if(acc === pwd){
        setError("account","err_account","帳號與密碼不能相同。");
        pass=false;
    }else{
        setSuccess("account","err_account");
    }

    /* Email */
    if(!emailRule.test(email)){
        setError("email","err_email","Email 格式不正確。");
        pass=false;
    }else setSuccess("email","err_email");

    /* 密碼 */
    if(!pwdRule.test(pwd)){
        setError("pwd","err_pwd","密碼需 ≥8 碼，含英文 + 數字。");
        pass=false;
    }else if(pwd === acc){
        setError("pwd","err_pwd","密碼不能與帳號相同。");
        pass=false;
    }else setSuccess("pwd","err_pwd");

    /* 確認密碼 */
    if(pwd2 !== pwd || pwd2 === ""){
        setError("pwd2","err_pwd2","兩次密碼不一致。");
        pass=false;
    }else setSuccess("pwd2","err_pwd2");

    /* checkbox */
    if(!agreeChecked){
        document.getElementById("err_agree").innerHTML="請勾選此項目。";
        pass=false;
    }else document.getElementById("err_agree").innerHTML="";

    /* 全通過 → 送 AJAX */
    if(pass && doSubmit){
        submitAjax({ account:acc, email:email, password:pwd });
    }
}

/* Autofill */
window.addEventListener("load",()=>{
    setTimeout(()=>{
        document.querySelectorAll("input").forEach(i=>i.dispatchEvent(new Event("input")));
    },200);
});

/* AJAX */
function submitAjax(payload){
    const btn=document.querySelector(".btn-submit");
    btn.disabled=true;
    btn.textContent="送出中…";

    fetch("register_save.php",{
        method:"POST",
        headers:{ "Content-Type":"application/json" },
        body:JSON.stringify(payload)
    })
    .then(r=>r.json())
    .then(res=>{
        if(res.status==="success"){
            alert("註冊成功！");
            location.href="login.php";
        }else{
            alert(res.msg || "註冊失敗");
        }
    })
    .catch(err=>{
        alert("連線錯誤，稍後再試");
        console.error(err);
    })
    .finally(()=>{
        btn.disabled=false;
        btn.textContent="建立帳號";
    });
}
</script>

</body>
</html>
