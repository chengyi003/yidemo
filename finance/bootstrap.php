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

/* ----------------------------------------------------
   env() — 支援 .env + 系統 getenv()
---------------------------------------------------- */
function env(string $key, ?string $default = null): ?string
{
    static $ENV = null;

    // 第一次呼叫時載入 .env
    if ($ENV === null) {
        $path = __DIR__ . '/.env';
        if (is_file($path)) {
            $ENV = parse_ini_file($path, false, INI_SCANNER_RAW);
        } else {
            $ENV = [];
        }
    }

    // 先查 .env
    if (array_key_exists($key, $ENV)) {
        return $ENV[$key];
    }

    // 再查系統環境變數
    $value = getenv($key);
    if ($value !== false) {
        return $value;
    }

    // 否則回傳預設
    return $default;
}

/* ----------------------------------------------------
   db() — 建立 PDO 單例
---------------------------------------------------- */
function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = "mysql:host=localhost;dbname=finance_db;charset=utf8";
    $pdo = new PDO($dsn, "root", "", [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}
