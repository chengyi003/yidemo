<?php
// ====== reset_password.php ======

$token = trim($_GET['token'] ?? '');
if ($token === '') die("連結無效");

/* ====== DB ====== */
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=finance_db;charset=utf8",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    die("資料庫連線失敗");
}

/* ====== 驗證 token ====== */
$stmt = $pdo->prepare("
    SELECT id,email
    FROM member
    WHERE reset_token = ?
      AND reset_expire > NOW()
    LIMIT 1
");
$stmt->execute([$token]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$member) die("此重設連結已失效或過期");
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
<meta charset="UTF-8">
<title>🔑 重設密碼</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="sidebar.css">

<style>
.reset-container{
    max-width:420px;
    margin:60px auto;
    background:#fff;
    padding:36px;
    border-radius:18px;
    box-shadow:0 10px 35px rgba(0,0,0,.15);
}
.reset-container h1{
    text-align:center;
    margin-bottom:24px;
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
    font-size:15px;
    transition:.25s;
}
input.error{
    border-color:#e74c3c;
    background:#fff5f5;
}
input.success{
    border-color:#2ecc71;
    background:#f6fffa;
}
.error-msg{
    font-size:13px;
    color:#e74c3c;
    margin-top:4px;
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
.note{
    font-size:13px;
    color:#666;
    margin-top:12px;
    text-align:center;
}
</style>
</head>

<body>
<?php include "sidebar.php"; ?>

<div class="reset-container">
    <h1>🔑 重設密碼</h1>

    <form method="post" action="reset_password_save.php">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <div class="form-group">
            <label>新密碼</label>
            <input type="password" name="password" id="pwd" required>
            <div class="error-msg" id="err_pwd"></div>
        </div>

        <div class="form-group">
            <label>確認新密碼</label>
            <input type="password" name="password2" id="pwd2" required>
            <div class="error-msg" id="err_pwd2"></div>
        </div>

        <button class="btn-submit">更新密碼</button>
    </form>

    <div class="note">
        此連結僅限一次使用，30 分鐘內有效
    </div>
</div>

<script>
const pwdRule = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

/* 狀態控制 */
function setError(id, errId, msg){
    const el=document.getElementById(id);
    el.classList.add("error");
    el.classList.remove("success");
    document.getElementById(errId).textContent=msg;
}
function setSuccess(id, errId){
    const el=document.getElementById(id);
    el.classList.remove("error");
    el.classList.add("success");
    document.getElementById(errId).textContent="";
}

/* 驗證 */
function validateReset(){
    let pass=true;
    const pwd=document.getElementById("pwd").value.trim();
    const pwd2=document.getElementById("pwd2").value.trim();

    if(!pwdRule.test(pwd)){
        setError("pwd","err_pwd","密碼需至少 8 碼，包含英文與數字");
        pass=false;
    }else setSuccess("pwd","err_pwd");

    if(pwd2!==pwd || pwd2===""){
        setError("pwd2","err_pwd2","兩次密碼不一致");
        pass=false;
    }else setSuccess("pwd2","err_pwd2");

    return pass;
}

/* 表單送出攔截 */
document.querySelector("form").addEventListener("submit",e=>{
    if(!validateReset()) e.preventDefault();
});

/* 即時驗證 */
["pwd","pwd2"].forEach(id=>{
    document.getElementById(id).addEventListener("input",validateReset);
});
</script>

</body>
</html>
