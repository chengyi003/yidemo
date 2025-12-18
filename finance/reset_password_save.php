<?php
// ====== reset_password_save.php ======
error_reporting(E_ALL);
ini_set("display_errors", 1);

/* ====== 接收 POST ====== */
$token     = trim($_POST['token'] ?? '');
$password  = $_POST['password'] ?? '';
$password2 = $_POST['password2'] ?? '';

if ($token === '' || $password === '' || $password2 === '') {
    die("資料不完整");
}

/* ====== 密碼規則（與前端一致） ====== */
if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
    die("密碼需至少 8 碼，包含英文與數字");
}
if ($password !== $password2) {
    die("兩次密碼不一致");
}

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

/* ====== 再驗一次 token（防直接 POST） ====== */
$stmt = $pdo->prepare("
    SELECT id
    FROM member
    WHERE reset_token = ?
      AND reset_expire > NOW()
    LIMIT 1
");
$stmt->execute([$token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("此重設連結已失效或過期");
}

/* ====== 密碼加密 ====== */
$hash = password_hash($password, PASSWORD_DEFAULT);

/* ====== 更新密碼 + 清除 token（★重點：pwd） ====== */
$stmt = $pdo->prepare("
    UPDATE member
    SET pwd = ?,
        reset_token = NULL,
        reset_expire = NULL
    WHERE id = ?
");
$stmt->execute([$hash, $user['id']]);

/* ====== 成功畫面 ====== */
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
<meta charset="UTF-8">
<title>密碼更新成功</title>
<style>
body{
    font-family:Arial;
    background:#f5f6fa;
}
.box{
    max-width:420px;
    margin:80px auto;
    background:#fff;
    padding:36px;
    border-radius:18px;
    text-align:center;
    box-shadow:0 10px 35px rgba(0,0,0,.15);
}
h1{color:#2ecc71}
p{margin-top:12px;color:#555}
</style>
</head>
<body>

<div class="box">
    <h1>✅ 密碼更新成功</h1>
    <p>請使用新密碼重新登入</p>
    <p><span id="sec">3</span> 秒後自動跳轉登入頁</p>
</div>

<script>
let sec=3;
const s=document.getElementById("sec");
const t=setInterval(()=>{
    sec--;
    s.textContent=sec;
    if(sec<=0){
        clearInterval(t);
        location.href="login.php";
    }
},1000);
</script>

</body>
</html>
