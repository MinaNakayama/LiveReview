-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2021-11-10 19:27:10
-- サーバのバージョン： 10.4.21-MariaDB
-- PHP のバージョン: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `review`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `genre`
--

CREATE TABLE `genre` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `genre`
--

INSERT INTO `genre` (`id`, `name`, `created_at`, `update_at`) VALUES
(1, 'DVD/Blu-ray', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, '現場コンサート・ライブ', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- テーブルの構造 `goods`
--

CREATE TABLE `goods` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `like_flg` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `goods`
--

INSERT INTO `goods` (`id`, `user_id`, `post_id`, `like_flg`, `created_at`, `update_at`) VALUES
(23, 2, 3, 0, '2021-11-02 18:59:28', '2021-11-02 18:59:28'),
(75, 1, 2, 0, '2021-11-03 15:22:14', '2021-11-03 15:22:14'),
(95, 1, 6, 0, '2021-11-03 17:03:38', '2021-11-03 17:03:38'),
(97, 1, 4, 0, '2021-11-04 22:46:11', '2021-11-04 22:46:11'),
(103, 1, 8, 0, '2021-11-05 17:18:57', '2021-11-05 17:18:57');

-- --------------------------------------------------------

--
-- テーブルの構造 `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `image_name` varchar(255) DEFAULT NULL COMMENT 'ファイル名',
  `image_path` varchar(255) DEFAULT NULL COMMENT 'ファイルパス',
  `user_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `post`
--

INSERT INTO `post` (`id`, `title`, `description`, `image_name`, `image_path`, `user_id`, `genre_id`, `created_at`, `update_at`) VALUES
(1, 'ARASHI LIVE TOUR 2016-2017 Are you Happy？', '青春ブギ', '', '', 2, 2, '2021-10-27 22:19:49', '2021-10-29 00:10:07'),
(2, 'ARASHI LIVE TOUR 2017-2018 「untitled」', '今回の目玉は何といってもユニット曲でしょう！！<br />\r\n私の一番好きなコンビが組まれた奇跡！笑（だれかにのあい担は語りましょう、、？）<br />\r\nあのダンスはにのあいだからエモさ増し増し', 'IMG_1976.JPG', 'photodata/20211028234428IMG_1976.JPG', 1, 2, '2021-10-28 23:44:33', '2021-10-28 23:44:33'),
(3, '関ジャニ∞「JAM」', '初めましての関ジャニ∞のコンサートでしたが知らない曲でも盛り上がることが出来て楽しかったです！<br />\r\nバンド姿がかっこよかった～～～！！', 'IMG_8543.JPG', 'photodata/20211029235303IMG_8543.JPG', 2, 2, '2021-10-29 23:53:08', '2021-10-29 23:53:08'),
(4, 'ARASHI \"Japonism Show\" in ARENA', '当時9年ぶりの、嵐アリーナツアーJaponism Show<br />\r\nアリーナならではの近さと一体感は思い出すだけでも鳥肌です。<br />\r\n', 'IMG_0319.JPG', 'photodata/20211102000646IMG_0319.JPG', 1, 1, '2021-11-02 00:06:53', '2021-11-02 00:06:53'),
(5, '関ジャニ∞「JAM」', '初めての参戦でもめちゃめちゃ楽しかった！！！', 'IMG_8547.JPG', 'photodata/20211103162134IMG_8547.JPG', 1, 2, '2021-11-03 16:21:40', '2021-11-03 16:21:40'),
(6, '関ジャニ∞「JAM」', '患者に', 'IMG_8547.JPG', 'photodata/20211103170242IMG_8547.JPG', 1, 2, '2021-11-03 17:02:52', '2021-11-03 17:02:52'),
(7, '嵐フェス2020 at 新国立競技場', '7年ぶりに嵐が国立に帰ってきた！！<br />\r\n大規模な特効も屋外ならではの演出🔥<br />\r\nファンの投票でセットリストが決まる嵐フェスは普段のコンサートではなかなか聴けないカップリング曲やアルバム曲が目白押し！<br />\r\n東京オリンピック2020のNHKテーマソングの「カイト」も歌っているのでぜひ一度観てみてください！！', '', '', 1, 1, '2021-11-05 14:14:43', '2021-11-05 14:17:38'),
(8, '嵐フェス2020 at 新国立競技場', '7年ぶりに嵐が国立に帰ってきた！！<br /><br />\r\n大規模な特効も屋外ならではの演出🔥<br /><br />\r\nファンの投票でセットリストが決まる嵐フェスは普段のコンサートではなかなか聴けないカップリング曲やアルバム曲が目白押し！<br /><br />\r\n東京オリンピック2020のNHKテーマソングの「カイト」も歌っているのでぜひ一度観てみてください！！\">', 'a1.jpg', 'photodata/20211105141938a1.jpg', 1, 1, '2021-11-05 14:19:43', '2021-11-05 14:19:43'),
(9, 'ARASHI LIVE TOUR 2016-2017 Are you Happy', 'as', 'IMG_5885.JPG', 'photodata/20211105171824IMG_5885.JPG', 1, 2, '2021-11-05 17:18:29', '2021-11-05 17:18:29');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `birth` datetime DEFAULT NULL,
  `person` varchar(50) DEFAULT NULL,
  `role` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `birth`, `person`, `role`, `created_at`, `update_at`) VALUES
(1, 'たま', 'owner@owner.com', '$2y$10$UcuAzsiBPi8ROfrPB2HUbuRvhG..If4Ll4zYpQvDoFCzIN32qMaVS', '1999-09-15 00:00:00', '嵐', 1, '2021-10-21 22:33:59', '2021-11-03 16:31:46'),
(2, 'ネギ星人', 'local1@local.com', '$2y$10$5hSRdIlqWHK1j5S2vtgcy.yU6Qnf7aOF5bDdOwTt5h0uxi/fZpb/6', '1999-06-17 00:00:00', '', 0, '2021-10-22 15:38:33', '2021-11-04 17:19:44');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `path` (`path`);

--
-- テーブルのインデックス `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `genre`
--
ALTER TABLE `genre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- テーブルの AUTO_INCREMENT `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- テーブルの AUTO_INCREMENT `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
