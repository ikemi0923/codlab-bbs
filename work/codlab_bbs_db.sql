-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: db
-- 生成日時: 2024 年 11 月 20 日 11:49
-- サーバのバージョン： 8.0.40
-- PHP のバージョン: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `codlab_bbs_db`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Tops'),
(2, 'Bottoms'),
(3, 'Accessories'),
(4, 'Shoes'),
(5, 'Hats'),
(6, 'Outerwear'),
(7, 'Jewelry');

-- --------------------------------------------------------

--
-- テーブルの構造 `inventory_images`
--

CREATE TABLE `inventory_images` (
  `id` int NOT NULL,
  `item_id` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `position` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `inventory_images`
--

INSERT INTO `inventory_images` (`id`, `item_id`, `image`, `position`) VALUES
(1, 1, 'image_1.jpg', 0),
(2, 2, 'image_2.jpg', 0),
(3, 3, 'image_3.jpg', 0),
(4, 4, 'image_4.jpg', 0),
(5, 5, 'image_5.jpg', 0),
(6, 6, 'image_6.jpg', 0),
(7, 7, 'image_7.jpg', 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `inventory_items`
--

CREATE TABLE `inventory_items` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `category_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` float NOT NULL,
  `threshold` int DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `inventory_items`
--

INSERT INTO `inventory_items` (`id`, `name`, `category_id`, `quantity`, `price`, `threshold`, `created`, `modified`) VALUES
(1, 'Vintage T-Shirt', 1, 10, 25, 5, '2024-11-20 18:17:31', '2024-11-20 18:17:31'),
(2, 'Denim Jeans', 2, 3, 40, 5, '2024-11-20 18:17:31', '2024-11-20 18:17:31'),
(3, 'Leather Belt', 3, 15, 15, 5, '2024-11-20 18:17:31', '2024-11-20 18:17:31'),
(4, 'Running Shoes', 4, 12, 60, 5, '2024-11-20 18:17:31', '2024-11-20 18:17:31'),
(5, 'Baseball Cap', 5, 18, 10, 5, '2024-11-20 18:17:31', '2024-11-20 18:17:31'),
(6, 'Winter Coat', 6, 8, 120, 5, '2024-11-20 18:17:31', '2024-11-20 18:17:31'),
(7, 'Silver Necklace', 7, 25, 50, 5, '2024-11-20 18:17:31', '2024-11-20 18:17:31');

-- --------------------------------------------------------

--
-- テーブルの構造 `inventory_notifications`
--

CREATE TABLE `inventory_notifications` (
  `id` int NOT NULL,
  `item_id` int NOT NULL,
  `threshold` int NOT NULL,
  `notify` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `inventory_notifications`
--

INSERT INTO `inventory_notifications` (`id`, `item_id`, `threshold`, `notify`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 0, '2024-11-20 18:17:31', '2024-11-20 18:17:31'),
(2, 2, 5, 1, '2024-11-20 18:17:31', '2024-11-20 18:17:31'),
(3, 3, 5, 0, '2024-11-20 18:17:31', '2024-11-20 18:17:31'),
(4, 4, 5, 0, '2024-11-20 18:17:31', '2024-11-20 18:17:31'),
(5, 5, 5, 0, '2024-11-20 18:17:31', '2024-11-20 18:17:31'),
(6, 6, 5, 0, '2024-11-20 18:17:31', '2024-11-20 18:17:31'),
(7, 7, 5, 0, '2024-11-20 18:17:31', '2024-11-20 18:17:31');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`, `updated_at`) VALUES
(1, 'ikemi', '$2y$10$JFJdq6LcjXvUr9AcPQZsk.3iU6oKwFu3lFx0MlG2yzl/ZZkBX3L12', 'ikemi@example.com', '2024-11-20 18:29:44', '2024-11-20 20:12:24');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `inventory_images`
--
ALTER TABLE `inventory_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- テーブルのインデックス `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- テーブルのインデックス `inventory_notifications`
--
ALTER TABLE `inventory_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- テーブルの AUTO_INCREMENT `inventory_images`
--
ALTER TABLE `inventory_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- テーブルの AUTO_INCREMENT `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- テーブルの AUTO_INCREMENT `inventory_notifications`
--
ALTER TABLE `inventory_notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `inventory_images`
--
ALTER TABLE `inventory_images`
  ADD CONSTRAINT `inventory_images_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`) ON DELETE CASCADE;

--
-- テーブルの制約 `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD CONSTRAINT `inventory_items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- テーブルの制約 `inventory_notifications`
--
ALTER TABLE `inventory_notifications`
  ADD CONSTRAINT `inventory_notifications_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
