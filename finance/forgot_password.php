<?php
// 目前先不用處理 PHP 邏輯
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
<meta charset="UTF-8">
<title>🔐 忘記密碼｜記帳系統</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="sidebar.css">

<style>
.forgot-container{
    max-width:420px;
    margin:60px auto;
    background:#fff;
    padding:36px;
    border-radius:16px;
    box-shadow:0 8px 30px rgba(0,0,0,.12);
}
.forgot-container h1{
    text-align:center;
    margin-bottom:24px;
    font-size:1.8em;
}
.form-group{margin-bottom:18px}
.form-group label{
    font-weight:600;
    display:block;
    margin-bottom:6px
}
.form-group input{
    width:100%;
    padding:12px;
    border-radius:8px;
    border:2px solid #ddd;
}
.btn-submit{
    width:100%;
    padding:14px;
    font-size:16px;
    border:none;
    border-radius:10px;
    background:linear-gradient(135deg,#667eea,#764ba2);
    color:#fff;
    cursor:pointer;
}
.btn-submit:disabled{
    opacity:.7;
    cursor:not-allowed;
}
.note{
    margin-top:16px;
    font-size:13px;
    color:#666;
    text-align:center;
}
.note.success{ color:#198754; }
.note.error{ color:#dc3545; }
</style>
</head>

<body>
<?php include "sidebar.php"; ?>

<div class="forgot-container">
    <h1>🔐 忘記密碼</h1>

    <div class="form-group">
        <label>註冊時的 Email</label>
        <input type="email" id="email" placeholder="example@gmail.com">
    </div>

    <button type="button" class="btn-submit" onclick="sendReset()">
        送出重設連結
    </button>

    <div class="note" id="noteMsg">
        系統將寄送密碼重設連結至您的信箱
    </div>
</div>

<script src="sidebar.js"></script>

<script>
function sendReset(){
    const emailInput = document.getElementById("email");
    const note = document.getElementById("noteMsg");
    const btn  = document.querySelector(".btn-submit");

    const email = emailInput.value.trim();
    if(email === ""){
        note.textContent = "請輸入 Email";
        note.className = "note error";
        return;
    }

    btn.disabled = true;
    btn.textContent = "送出中…";
    note.textContent = "";

    fetch("forgot_password_send.php",{
        method:"POST",
        headers:{
            "Content-Type":"application/json"
        },
        body: JSON.stringify({ email })
    })
    .then(res => res.json())
    .then(res => {
        note.textContent = "📧 如果此 Email 存在，系統已寄送重設連結";
        note.className = "note success";
        emailInput.value = "";
    })
    .catch(err => {
        note.textContent = "系統錯誤，請稍後再試";
        note.className = "note error";
        console.error(err);
    })
    .finally(()=>{
        btn.disabled = false;
        btn.textContent = "送出重設連結";
    });
}
</script>

</body>
</html>
