<?php
// auth_check.php
require __DIR__ . '/bootstrap.php';

// ===============================
// 1. 若 session 已登入 → 直接放行
// ===============================
if (!empty($_SESSION['user'])) {
    return; // 使用者已登入
}

// ===============================
// 2. 無 session → 嘗試 cookie 自動登入
// ===============================
$key = $_COOKIE['money_key'] ?? '';

if ($key === '') {
    return; // 無 cookie → 視為訪客，直接放行（不導向 login）
}

// money_key 格式：{id}.{token}.{time}.{hmac}
$parts = explode('.', $key, 4);
if (count($parts) !== 4) {
    return; // 格式不符 → 當訪客處理
}

[$uid, $token, $time, $hmac] = $parts;

// 驗證 HMAC
$secret = env('SECRET_KEY', 'fallback_secret');
$expect = hash_hmac('sha256', $uid . $token . $time, $secret);

if (!hash_equals($expect, $hmac)) {
    return; // 驗證失敗 → 視為訪客
}

// ===============================
// 3. 確認 DB money_key 是否仍有效
// ===============================
$pdo = db();
$stmt = $pdo->prepare("
    SELECT id, account, email 
    FROM member 
    WHERE id = ? AND money_key = ?
    LIMIT 1
");
$stmt->execute([$uid, $key]);
$user = $stmt->fetch();

// ⚠️ 重點：查無資料 → 不導向 login，仍然視為訪客
if (!$user) {
    return;
}

// ===============================
// 4. 建立 session，正式登入
// ===============================
$_SESSION['user'] = [
    'id'      => (int)$user['id'],
    'account' => $user['account'],
    'email'   => $user['email'] ?? null,
];

// 完成！登入成功
return;
