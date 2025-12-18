<?php
header("Content-Type: application/json; charset=utf-8");
date_default_timezone_set('Asia/Taipei');


// ★★★★★ 防止雜訊輸出
ob_start();

/* ====== 載入 PHPMailer（手動版） ====== */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';
require __DIR__ . '/PHPMailer-master/src/Exception.php';

/* ====== 讀取 .env ====== */
$envPath = __DIR__ . '/.env';
$env = [];

if (file_exists($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        [$key, $value] = explode('=', $line, 2);
        $env[trim($key)] = trim($value);
    }
}

$gmail_user = $env['GMAIL_USER'] ?? null;
$gmail_pass = $env['GMAIL_PASS'] ?? null;

if (!$gmail_user || !$gmail_pass) {
    ob_end_clean();
    echo json_encode([
        "status" => "error",
        "msg" => ".env 未設定 GMAIL_USER / GMAIL_PASS"
    ]);
    exit;
}

/* ====== 接收 AJAX JSON ====== */
$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email'] ?? '');

if ($email === '') {
    ob_end_clean();
    echo json_encode([
        "status" => "error",
        "msg" => "Email 不可為空"
    ]);
    exit;
}

/* ====== 資料庫連線 ====== */
try {
    $dsn = "mysql:host=localhost;dbname=finance_db;charset=utf8";
    $pdo = new PDO($dsn, "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    ob_end_clean();
    echo json_encode([
        "status" => "error",
        "msg" => "資料庫連線失敗"
    ]);
    exit;
}

/* ====== 查詢 member（不洩漏是否存在） ====== */
$stmt = $pdo->prepare("SELECT id FROM member WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if ($member) {

    /* ====== 產生 token ====== */
    $token  = bin2hex(random_bytes(32));
    $expire = date('Y-m-d H:i:s', strtotime('+30 minutes'));

    /* ====== 寫入 token ====== */
    $stmt = $pdo->prepare("
        UPDATE member
        SET reset_token = ?, reset_expire = ?
        WHERE id = ?
    ");
    $stmt->execute([$token, $expire, $member['id']]);

    /* ====== 重設密碼連結 ====== */
    $resetLink = "http://localhost/finance/reset_password.php?token={$token}";

    /* ====== 寄送重設密碼信 ====== */
    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $gmail_user;
        $mail->Password = $gmail_pass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = "UTF-8";

        $mail->setFrom($gmail_user, '記帳系統');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "重設密碼通知";
        $mail->Body = "
            <h2>重設密碼申請</h2>
            <p>請點擊下方連結以重設您的密碼（30 分鐘內有效）：</p>
            <p><a href='{$resetLink}'>{$resetLink}</a></p>
            <p>若非本人操作，請忽略此信件。</p>
        ";

        $mail->send();

    } catch (Exception $e) {
        // 寄信失敗也不回錯（避免帳號探測）
    }
}

/* ====== 統一回傳成功 ====== */
ob_end_clean();
echo json_encode([
    "status" => "success"
]);
