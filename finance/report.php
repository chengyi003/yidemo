<?php
// ====== 1. 載入 Composer ======
require __DIR__ . '/vendor/autoload.php';
session_start();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// ====== 2. 資料庫連線 ======
$dsn = "mysql:host=localhost;dbname=finance_db;charset=utf8";
$pdo = new PDO($dsn, 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// ====== 3. 撈資料（跟你 index.php 幾乎一樣） ======
$sql = "
SELECT 
    daily_account.*,
    category.name AS category_name,
    payment_method.name AS payment_method_name
FROM daily_account
JOIN category ON daily_account.category = category.id
JOIN payment_method ON daily_account.payment_method = payment_method.id
WHERE daily_account.member_id = {$_SESSION['user']['id']}
ORDER BY date DESC, time DESC
";

$expenses = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// ====== 4. 建立 Excel ======
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('消費紀錄');

// 表頭
$headers = [
    '品項','金額','商店','分類','貨幣',
    '付款方式','類型','付款帳戶','日期','時間'
];

$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $sheet->getStyle($col . '1')->getFont()->setBold(true);
    $sheet->getColumnDimension($col)->setAutoSize(true);
    $col++;
}

// 資料列
$row = 2;
foreach ($expenses as $exp) {
    $sheet->setCellValue("A{$row}", $exp['item']);
    $sheet->setCellValue("B{$row}", $exp['payment']);
    $sheet->setCellValue("C{$row}", $exp['store']);
    $sheet->setCellValue("D{$row}", $exp['category_name']);
    $sheet->setCellValue("E{$row}", $exp['currency']);
    $sheet->setCellValue("F{$row}", $exp['payment_method_name']);
    $sheet->setCellValue("G{$row}", $exp['type']);
    $sheet->setCellValue("H{$row}", $exp['account']);
    $sheet->setCellValue("I{$row}", $exp['date']);
    $sheet->setCellValue("J{$row}", $exp['time']);
    $row++;
}

// 金額格式（千分位）
$sheet->getStyle("B2:B{$row}")
      ->getNumberFormat()
      ->setFormatCode('#,##0');

// ====== 5. 直接下載（重點） ======
$filename = 'finance_report_' . date('Ymd_His') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"{$filename}\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
