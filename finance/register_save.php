<?php
header("Content-Type: application/json; charset=utf-8");

// ★★★★★ 防止所有雜訊輸出（最重要）
ob_start();

/* ====== 載入 PHPMailer ====== */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

/* ====== 讀取 .env ====== */
$envPath = __DIR__ . '/.env';
$env = [];

if (file_exists($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (strpos(trim($line), '#') === 0) continue; // 跳過註解
        list($key, $value) = explode('=', $line, 2);
        $env[trim($key)] = trim($value);
    }
}

$gmail_user = $env['GMAIL_USER'] ?? null;
$gmail_pass = $env['GMAIL_PASS'] ?? null;

if (!$gmail_user || !$gmail_pass) {
    ob_end_clean();
    echo json_encode([
        "status" => "error",
        "msg" => ".env 未設定完整，請確認 GMAIL_USER / GMAIL_PASS"
    ]);
    exit;
}

/* ====== 接收前端 JSON ====== */
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    ob_end_clean();
    echo json_encode([
        "status" => "error",
        "msg" => "未收到資料"
    ]);
    exit;
}

$account = $data["account"] ?? "";
$email   = $data["email"] ?? "";
$pwd     = $data["password"] ?? "";

/* ====== 連線資料庫 ====== */
try {
    $dsn = "mysql:host=localhost;dbname=finance_db;charset=utf8";
    $pdo = new PDO($dsn, "root", "");
} catch (Exception $e) {
    ob_end_clean();
    echo json_encode([
        "status" => "error",
        "msg" => "資料庫連線錯誤"
    ]);
    exit;
}

/* ====== 檢查帳號是否重複 ====== */
$sql = "SELECT * FROM member WHERE account = ?";
$chk = $pdo->prepare($sql);
$chk->execute([$account]);

if ($chk->rowCount() > 0) {
    ob_end_clean();
    echo json_encode([
        "status" => "error",
        "msg" => "帳號已存在，請換一個"
    ]);
    exit;
}

/* ====== 檢查 email 是否重複 ====== */
$sql = "SELECT * FROM member WHERE email = ?";
$chk = $pdo->prepare($sql);
$chk->execute([$email]);

if ($chk->rowCount() > 0) {
    ob_end_clean();
    echo json_encode([
        "status" => "error",
        "msg" => "Email 已被使用"
    ]);
    exit;
}

/* ====== 新增會員 ====== */
$sql = "INSERT INTO member (account, pwd, email, is_admin) VALUES (?, ?, ?, 0)";
$insert = $pdo->prepare($sql);
$insert->execute([
    $account,
    password_hash($pwd, PASSWORD_DEFAULT),
    $email
]);

/* ====== 寄送註冊成功信 ====== */
try {
    $mail = new PHPMailer(true);

    // SMTP 設定
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    // ★ 由 .env 取得
    $mail->Username = $gmail_user;
    $mail->Password = $gmail_pass;

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = "UTF-8";

    // 寄件人 / 收件人
    $mail->setFrom($gmail_user, '記帳系統');
    $mail->addAddress($email);

    // 信件內容
    $mail->isHTML(true);
    $mail->Subject = "帳號註冊成功通知";
    $mail->Body = "
        <h2>歡迎加入記帳系統！</h2>
        <p>您的帳號已建立成功：</p>
        <p><b>帳號：</b> {$account}</p>
        <p><b>Email：</b> {$email}</p>
        <br>
        <p>請妥善保存您的帳號資訊。</p>
    ";

    $mail->send();

    ob_end_clean();
    echo json_encode([
        "status" => "success",
        "msg" => "註冊成功，已寄出信件"
    ]);
    exit;

} catch (Exception $e) {

    ob_end_clean();
    echo json_encode([
        "status" => "warning",
        "msg" => "註冊成功，但寄信失敗：" . $e->getMessage()
    ]);
    exit;
}

?>
