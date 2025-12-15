<?php
header("Content-Type: application/json; charset=utf-8");
ob_start();

require __DIR__ . '/bootstrap.php';  // ← 載入 DB、session、env()

/* ====== 讀取前端 JSON ====== */
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    ob_end_clean();
    echo json_encode(["status" => "error", "msg" => "未收到資料"]);
    exit;
}

$account  = trim($data["account"] ?? "");
$pwd      = trim($data["password"] ?? "");
$remember = intval($data["remember"] ?? 0);

/* ====== 欄位檢查 ====== */
if ($account === "" || $pwd === "") {
    ob_end_clean();
    echo json_encode(["status" => "error", "msg" => "帳號或密碼不可空白"]);
    exit;
}

/* ====== 查詢帳號 ====== */
$pdo = db();

$sql = "SELECT * FROM member WHERE account = ? LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([$account]);

if ($stmt->rowCount() == 0) {
    ob_end_clean();
    echo json_encode(["status" => "error", "msg" => "帳號不存在"]);
    exit;
}

$user = $stmt->fetch(PDO::FETCH_ASSOC);

/* ====== 密碼驗證 ====== */
if (!password_verify($pwd, $user["pwd"])) {
    ob_end_clean();
    echo json_encode(["status" => "error", "msg" => "密碼錯誤"]);
    exit;
}

/* ====== 建立 Session ====== */
$_SESSION["user"] = [
    "id"      => intval($user["id"]),
    "account" => $user["account"],
    "email"   => $user["email"],
];

/* ====== 如果沒勾保持登入 → 結束 ====== */
if ($remember == 0) {
    ob_end_clean();
    echo json_encode(["status" => "success", "msg" => "登入成功"]);
    exit;
}

/* ====== ★★★ 產生 money_key（保持登入用） ★★★ */
/*
    結構：   id.token.timestamp.hmac
*/
$uid  = $user["id"];
$token = bin2hex(random_bytes(16)); // 隨機安全字串
$time  = time();
$secret = env("SECRET_KEY", "fallback_secret"); // 來自 .env

$hmac  = hash_hmac("sha256", $uid . $token . $time, $secret);
$money_key = "{$uid}.{$token}.{$time}.{$hmac}";

/* ====== 寫入 DB ====== */
$sql = "UPDATE member SET money_key = ? WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$money_key, $uid]);

/* ====== 寫入 Cookie（一年） ====== */
setcookie(
    "money_key",
    $money_key,
    time() + (365 * 24 * 60 * 60),
    "/",        // 全站
    "",         // domain
    false,      // secure (若你用 HTTPS 可改 true)
    true        // httponly
);

ob_end_clean();
echo json_encode(["status" => "success", "msg" => "登入成功"]);
exit;

