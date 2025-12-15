<?php
// auth_check.php
require __DIR__ . '/bootstrap.php';

// 若 session 已登入，直接放行
if (!empty($_SESSION['user'])) {
    return;
}

// 沒有 session，嘗試用 cookie 自動登入
$key = $_COOKIE['money_key'] ?? '';
if ($key === '') {
    header("Location: login.php");
    exit;
}

// money_key 格式：{id}.{token}.{time}.{hmac}
$parts = explode('.', $key, 4);
if (count($parts) !== 4) {
    header("Location: login.php");
    exit;
}
[$uid, $token, $time, $hmac] = $parts;

// 驗證 HMAC
$secret = env('SECRET_KEY', 'fallback_secret'); // 記得把 .env 換成自己的值
$expect = hash_hmac('sha256', $uid . $token . $time, $secret);
if (!hash_equals($expect, $hmac)) {
    header("Location: login.php");
    exit;
}

// 也可在此加入「過期機制」，例如：一年內有效
// if ((time() - (int)$time) > 365*24*60*60) { header("Location: login.php"); exit; }

// 確認 DB 中的 money_key 仍一致（避免被撤銷）
$pdo = db();
$stmt = $pdo->prepare("SELECT id, account, email FROM member WHERE id = ? AND money_key = ? LIMIT 1");
$stmt->execute([$uid, $key]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: login.php");
    exit;
}

// 建立 session，讓後續頁面使用
$_SESSION['user'] = [
    'id'      => (int)$user['id'],
    'account' => $user['account'],
    'email'   => $user['email'] ?? null,
];

// 放行
return;
