-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-12-05 04:52:40
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `finance_db`
--

-- --------------------------------------------------------

--
-- 資料表結構 `category`
--

CREATE TABLE `category` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `category`
--

INSERT INTO `category` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, '3C', '2025-11-21 00:51:44', '2025-11-21 00:51:44'),
(2, '交通', '2025-11-21 00:51:44', '2025-11-21 00:51:44'),
(3, '日用品', '2025-11-21 00:51:44', '2025-11-21 00:51:44'),
(4, '食品', '2025-11-21 00:51:44', '2025-11-21 00:51:44'),
(5, '飲料', '2025-11-21 00:51:44', '2025-11-21 00:51:44'),
(6, '餐飲', '2025-11-21 00:51:44', '2025-11-21 00:51:44');

-- --------------------------------------------------------

--
-- 資料表結構 `daily_account`
--

CREATE TABLE `daily_account` (
  `id` int(11) NOT NULL,
  `time` time NOT NULL,
  `currency` varchar(10) NOT NULL,
  `store` text DEFAULT NULL,
  `date` date NOT NULL,
  `item` text DEFAULT NULL,
  `payment` int(11) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `desc` text DEFAULT NULL,
  `account` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `daily_account`
--

INSERT INTO `daily_account` (`id`, `time`, `currency`, `store`, `date`, `item`, `payment`, `payment_method`, `category`, `type`, `desc`, `account`) VALUES
(1, '09:12:00', 'TWD', '全家便利商店', '2025-12-03', '早餐三明治', 65, 2, 6, '支出', '上班前買早餐', '錢包'),
(2, '12:28:00', 'TWD', '麥當勞', '2025-12-03', '超值午餐', 129, 1, 6, '支出', '', '台新信用卡'),
(3, '15:40:00', 'TWD', '星巴克', '2025-12-03', '拿鐵', 135, 3, 5, '支出', '用 Starbucks App', '悠遊付'),
(4, '18:55:00', 'TWD', '家樂福', '2025-12-03', '衛生紙', 99, 1, 3, '支出', '', '國泰世華卡'),
(5, '20:10:00', 'TWD', '7-11', '2025-12-04', '飲料', 35, 2, 5, '支出', '', '錢包'),
(6, '09:05:00', 'TWD', '早餐店', '2025-12-04', '蛋餅', 40, 2, 6, '支出', '', '錢包'),
(7, '12:20:00', 'TWD', '便當店', '2025-12-04', '雞腿便當', 110, 3, 6, '支出', '', 'Line Pay'),
(8, '14:33:00', 'TWD', '全聯', '2025-12-05', '咖啡豆', 299, 1, 4, '支出', '', '玉山信用卡'),
(9, '17:50:00', 'TWD', '捷運悠遊卡', '2025-12-05', '儲值', 200, 2, 2, '支出', '', '錢包'),
(10, '21:15:00', 'TWD', 'PChome', '2025-12-05', '鍵盤', 899, 1, 1, '支出', '特價購入', '台新信用卡'),
(11, '08:55:00', 'TWD', '早餐店', '2025-12-06', '豆漿飯糰', 55, 2, 6, '支出', '', '錢包'),
(12, '12:10:00', 'TWD', '公司餐廳', '2025-12-06', '自助餐', 85, 3, 6, '支出', '', '一卡通'),
(13, '16:45:00', 'TWD', '星巴克', '2025-12-06', '美式咖啡', 110, 3, 5, '支出', '', '悠遊付'),
(14, '19:30:00', 'TWD', '家樂福', '2025-12-02', '洗衣精', 120, 1, 3, '支出', '', '國泰世華卡'),
(15, '20:50:00', 'TWD', '7-11', '2025-12-02', '果汁', 28, 2, 5, '支出', '', '錢包'),
(16, '09:20:00', 'TWD', '全家', '2025-12-02', '地瓜', 35, 3, 6, '支出', '', 'Line Pay'),
(17, '12:18:00', 'TWD', '牛肉麵店', '2025-12-02', '牛肉麵', 130, 2, 6, '支出', '', '錢包'),
(18, '15:55:00', 'TWD', '手搖飲料店', '2025-12-04', '珍珠奶茶', 60, 3, 5, '支出', '', '悠遊付'),
(19, '17:10:00', 'TWD', 'Uber Eats', '2025-12-05', '雞塊套餐', 159, 1, 6, '支出', '外送', '台新信用卡'),
(20, '21:00:00', 'TWD', 'Momo', '2025-12-06', '滑鼠', 499, 1, 1, '支出', '', '永豐信用卡');

-- --------------------------------------------------------

--
-- 資料表結構 `payment_method`
--

CREATE TABLE `payment_method` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(24) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `payment_method`
--

INSERT INTO `payment_method` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, '信用卡', '2025-11-21 01:15:06', '2025-11-21 01:15:06'),
(2, '現金', '2025-11-21 01:15:06', '2025-11-21 01:15:06'),
(3, '電子支付', '2025-11-21 01:15:06', '2025-11-21 01:15:06');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `daily_account`
--
ALTER TABLE `daily_account`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `daily_account`
--
ALTER TABLE `daily_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
