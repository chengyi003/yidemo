<?php
require __DIR__ . '/bootstrap.php';

// 若有登入就把 DB 的 money_key 清空、再清 Cookie
if (!empty($_SESSION['user']['id'])) {
    $pdo = db();
    $stmt = $pdo->prepare("UPDATE member SET money_key = NULL WHERE id = ?");
    $stmt->execute([$_SESSION['user']['id']]);
}

// 清除 cookie（設為過期）
setcookie('money_key', '', time() - 3600, '/', '', false, true);

// 清掉 session
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

// 回登入頁
header("Location: login.php");
exit;
