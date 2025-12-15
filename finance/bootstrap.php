<?php
// bootstrap.php
declare(strict_types=1);

// 顯示錯誤（開發期）
ini_set('display_errors', '1');
error_reporting(E_ALL);

// 啟動 Session（若尚未啟動）
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 載入 .env（簡單 parser）
function env(string $key, ?string $default = null): ?string {
    static $ENV = null;
    if ($ENV === null) {
        $path = __DIR__ . '/.env';
        $ENV = is_file($path) ? parse_ini_file($path, false, INI_SCANNER_RAW) : [];
    }
    return $ENV[$key] ?? $default;
}

// 建立 PDO
function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;

    $dsn = "mysql:host=localhost;dbname=finance_db;charset=utf8";
    $pdo = new PDO($dsn, "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
}
