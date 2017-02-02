-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 24, 2017 at 06:41 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `f_nttu`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort_order` smallint(6) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT NULL,
  `excerpt` longtext COLLATE utf8_unicode_ci,
  `content` longtext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` smallint(6) DEFAULT NULL COMMENT 'draft|publish|trash',
  `is_featured` tinyint(1) DEFAULT NULL,
  `excerpt` longtext COLLATE utf8_unicode_ci,
  `content` longtext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `post_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'post|page'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `title`, `slug`, `thumbnail`, `template`, `status`, `is_featured`, `excerpt`, `content`, `created_at`, `updated_at`, `link`, `location`, `start_date`, `post_type`) VALUES
(1, 'Lễ phát động sinh viên góp một quyển sách', 'le-phat-dong-sinh-vien-gop-1-quyen-sach', NULL, NULL, 2, 1, 'Giới thiệu ngắn lễ phát động', NULL, '2017-01-24 00:00:00', '2017-01-24 14:59:35', NULL, 'Hội trường chính', '2017-01-31 10:30:00', 'post'),
(2, 'Event #2', 'event-2', NULL, 'standard', 2, 1, 'Khai trương phòng truyền thông', NULL, '2017-01-24 09:18:04', '2017-01-24 15:31:31', NULL, 'Phòng truyền thông', '2017-01-30 18:50:00', 'post'),
(3, 'Sự kiện 3', 'su-kien-3', NULL, 'standard', 2, 1, 'sgs', NULL, '2017-01-24 15:36:35', '2017-01-24 15:36:35', NULL, 'Phòng A', '2017-02-08 09:00:00', 'post');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `access_ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `access_time` datetime NOT NULL,
  `event` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entity` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entity_id` bigint(20) NOT NULL,
  `field` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `access_ip`, `access_time`, `event`, `entity`, `entity_id`, `field`, `old_value`, `new_value`, `created_at`, `updated_at`) VALUES
(1, 1, '::1', '2017-01-18 16:13:52', 'Remove', 'AMZ\\PostBundle\\Entity\\Post', 62, NULL, NULL, NULL, '2017-01-18 16:13:52', '2017-01-18 16:13:52'),
(2, 1, '::1', '2017-01-18 16:14:17', 'Remove', 'AMZ\\PostBundle\\Entity\\Post', 61, NULL, NULL, NULL, '2017-01-18 16:14:17', '2017-01-18 16:14:17'),
(3, 1, '::1', '2017-01-18 16:20:05', 'Create', 'AMZ\\PostBundle\\Entity\\Post', 66, NULL, NULL, NULL, '2017-01-18 16:20:05', '2017-01-18 16:20:05'),
(4, 1, '::1', '2017-01-18 16:22:05', 'Create', 'AMZ\\PostBundle\\Entity\\Post', 67, NULL, NULL, NULL, '2017-01-18 16:22:05', '2017-01-18 16:22:05'),
(5, 1, '::1', '2017-01-18 16:23:01', 'Update', 'AMZ\\PostBundle\\Entity\\Post', 67, 'title', 'NTTU tuyển sinh Thạc sĩ CNTT khóa #K27', 'NTTU tuyển sinh đại học CNTT khóa #K27', '2017-01-18 16:23:01', '2017-01-18 16:23:01'),
(6, 1, '::1', '2017-01-18 16:24:37', 'Create', 'AMZ\\PostBundle\\Entity\\Post', 68, NULL, NULL, NULL, '2017-01-18 16:24:37', '2017-01-18 16:24:37'),
(7, 1, '::1', '2017-01-18 16:25:08', 'Update', 'AMZ\\PostBundle\\Entity\\Post', 68, 'thumbnail', NULL, 'http://localhost/nttu/web/upload/images/post_7.jpg', '2017-01-18 16:25:08', '2017-01-18 16:25:08'),
(8, 1, '::1', '2017-01-18 17:12:15', 'Create', 'AMZ\\PostBundle\\Entity\\Category', 22, NULL, NULL, NULL, '2017-01-18 17:12:15', '2017-01-18 17:12:15'),
(9, 1, '::1', '2017-01-18 17:14:48', 'Create', 'AMZ\\PostBundle\\Entity\\Category', 23, NULL, NULL, NULL, '2017-01-18 17:14:48', '2017-01-18 17:14:48'),
(10, 1, '::1', '2017-01-18 17:15:55', 'Create', 'AMZ\\PostBundle\\Entity\\Category', 24, NULL, NULL, NULL, '2017-01-18 17:15:55', '2017-01-18 17:15:55'),
(11, 1, '::1', '2017-01-18 17:44:46', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 13, 'sortOrder', '1', '1', '2017-01-18 17:44:46', '2017-01-18 17:44:46'),
(12, 1, '::1', '2017-01-18 17:44:46', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 13, 'isFeatured', '', '1', '2017-01-18 17:44:46', '2017-01-18 17:44:46'),
(13, 1, '::1', '2017-01-18 18:07:39', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 23, 'thumbnail', NULL, 'http://localhost/nttu/web/upload/images/post_KHXH.jpg', '2017-01-18 18:07:39', '2017-01-18 18:07:39'),
(14, 1, '::1', '2017-01-18 18:07:39', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 23, 'sortOrder', '1', '1', '2017-01-18 18:07:39', '2017-01-18 18:07:39'),
(15, 1, '::1', '2017-01-18 18:15:00', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 10, 'excerpt', NULL, 'NTTU - Là một trường ĐH định hướng ứng dụng và thực hành, chúng tôi hướng tới mục tiêu đáp ứng nhu cầu giáo dục đại học đại chúng, tạo lập một môi trường học tập tích cực và trải nghiệm thực tiễn cho mọi sinh viên, trang bị cho họ năng lực tự học, tinh th', '2017-01-18 18:15:00', '2017-01-18 18:15:00'),
(16, 1, '::1', '2017-01-18 18:15:00', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 10, 'link', NULL, 'phongdaotao.ntt.edu.vn', '2017-01-18 18:15:00', '2017-01-18 18:15:00'),
(17, 1, '::1', '2017-01-18 18:18:01', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 10, 'content', NULL, '<img class="img-fluid" src="dist/images/sidebar_1.jpg" alt="">\r\n                <h4 class="text-uppercase font-weight-bold">Năng lực đào tạo</h4>\r\n                <p>Hiện nay, nhà trường có đội ngũ hơn <b>2.000 CB, GV, CNV</b> trong đó hơn <b>62%</b> có t', '2017-01-18 18:18:01', '2017-01-18 18:18:01'),
(18, 1, '::1', '2017-01-18 18:34:54', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 13, 'sortOrder', '1', '1', '2017-01-18 18:34:54', '2017-01-18 18:34:54'),
(19, 1, '::1', '2017-01-18 18:34:54', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 13, 'isFeatured', '1', '', '2017-01-18 18:34:54', '2017-01-18 18:34:54'),
(20, 1, '::1', '2017-01-19 13:40:29', 'Create', 'AMZ\\PostBundle\\Entity\\Category', 25, NULL, NULL, NULL, '2017-01-19 13:40:29', '2017-01-19 13:40:29'),
(21, 1, '::1', '2017-01-19 13:41:17', 'Create', 'AMZ\\PostBundle\\Entity\\Category', 26, NULL, NULL, NULL, '2017-01-19 13:41:17', '2017-01-19 13:41:17'),
(22, 1, '::1', '2017-01-19 13:42:30', 'Create', 'AMZ\\PostBundle\\Entity\\Category', 27, NULL, NULL, NULL, '2017-01-19 13:42:30', '2017-01-19 13:42:30'),
(23, 1, '::1', '2017-01-19 13:43:28', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 14, 'sortOrder', '1', '2', '2017-01-19 13:43:28', '2017-01-19 13:43:28'),
(24, 1, '::1', '2017-01-19 13:44:06', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 13, 'sortOrder', '1', '3', '2017-01-19 13:44:06', '2017-01-19 13:44:06'),
(25, 1, '::1', '2017-01-19 16:58:45', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 27, 'sortOrder', '1', '6', '2017-01-19 16:58:45', '2017-01-19 16:58:45'),
(26, 1, '::1', '2017-01-19 16:59:16', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 26, 'sortOrder', '1', '5', '2017-01-19 16:59:16', '2017-01-19 16:59:16'),
(27, 1, '::1', '2017-01-19 17:00:31', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 23, 'sortOrder', '1', '4', '2017-01-19 17:00:31', '2017-01-19 17:00:31'),
(28, 1, '::1', '2017-01-19 17:17:42', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 25, 'thumbnail', NULL, 'http://localhost/nttu/web/upload/images/post_KHSK.jpg', '2017-01-19 17:17:42', '2017-01-19 17:17:42'),
(29, 1, '::1', '2017-01-19 17:17:42', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 25, 'sortOrder', '1', '1', '2017-01-19 17:17:42', '2017-01-19 17:17:42'),
(30, 1, '::1', '2017-01-19 17:17:42', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 25, 'excerpt', NULL, 'NTTU - Nhà trường và giảng viên thể hiện trong chương trình giảng dạy và mô hình tổ chức hợp lý đáp ứng nhu cầu xã hội nhằm mục đích bảo vệ và chăm sóc sức khỏe nhân dân có hiệu quả cao nhất trong quá trình hội nhập và phát triển đất nước.', '2017-01-19 17:17:42', '2017-01-19 17:17:42'),
(31, 1, '::1', '2017-01-19 17:19:10', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 27, 'thumbnail', NULL, 'http://localhost/nttu/web/upload/images/post_KHCB.jpg', '2017-01-19 17:19:10', '2017-01-19 17:19:10'),
(32, 1, '::1', '2017-01-19 17:19:10', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 27, 'sortOrder', '6', '6', '2017-01-19 17:19:10', '2017-01-19 17:19:10'),
(33, 1, '::1', '2017-01-19 17:19:10', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 27, 'excerpt', NULL, 'NTTU - Khoa học Cơ bản là một đơn vị độc lập trong hệ thống 15 Khoa đào tạo ngành trực thuộc Ban giám hiệu Trường Đại học Nguyễn Tất Thành, được nâng cấp, chuyển đổi sang cơ chế quản lý Trường ĐH Nguyễn Tất Thành theo quyết định số 621/QĐ-Ttg do thủ tướng', '2017-01-19 17:19:10', '2017-01-19 17:19:10'),
(34, 1, '::1', '2017-01-19 17:20:43', 'Create', 'AMZ\\PostBundle\\Entity\\Category', 28, NULL, NULL, NULL, '2017-01-19 17:20:43', '2017-01-19 17:20:43'),
(35, 1, '::1', '2017-01-20 02:42:46', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 10, 'content', '<img class="img-fluid" src="dist/images/sidebar_1.jpg" alt="">\r\n                <h4 class="text-uppercase font-weight-bold">Năng lực đào tạo</h4>\r\n                <p>Hiện nay, nhà trường có đội ngũ hơn <b>2.000 CB, GV, CNV</b> trong đó hơn <b>62%</b> có t', '<p><img alt="" class="img-fluid" src="/web/frontend/images/sidebar_1.jpg" /></p>\r\n\r\n<h4 class="text-uppercase font-weight-bold">Năng lực đ&agrave;o tạo</h4>\r\n\r\n<p>Hiện nay, nh&agrave; trường c&oacute; đội ngũ hơn <b>2.000 CB, GV, CNV</b> trong đ&oacute; h', '2017-01-20 02:42:46', '2017-01-20 02:42:46'),
(36, 1, '::1', '2017-01-20 02:56:19', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 10, 'content', '<p><img alt="" class="img-fluid" src="/web/frontend/images/sidebar_1.jpg" /></p>\r\n\r\n<h4 class="text-uppercase font-weight-bold">Năng lực đ&agrave;o tạo</h4>\r\n\r\n<p>Hiện nay, nh&agrave; trường c&oacute; đội ngũ hơn <b>2.000 CB, GV, CNV</b> trong đ&oacute; h', '<h4 class="text-uppercase font-weight-bold">Năng lực đ&agrave;o tạo</h4>\r\n\r\n<p>Hiện nay, nh&agrave; trường c&oacute; đội ngũ hơn <b>2.000 CB, GV, CNV</b> trong đ&oacute; hơn <b>62%</b> c&oacute; tr&igrave;nh độ <b>GS., PGS., TS., ThS.</b>, c&ograve;n lại ', '2017-01-20 02:56:19', '2017-01-20 02:56:19'),
(37, 1, '::1', '2017-01-21 04:04:29', 'Create', 'AMZ\\PostBundle\\Entity\\Category', 29, NULL, NULL, NULL, '2017-01-21 04:04:29', '2017-01-21 04:04:29'),
(38, 1, '::1', '2017-01-21 04:05:07', 'Create', 'AMZ\\PostBundle\\Entity\\Category', 30, NULL, NULL, NULL, '2017-01-21 04:05:07', '2017-01-21 04:05:07'),
(39, 1, '::1', '2017-01-21 04:06:04', 'Create', 'AMZ\\PostBundle\\Entity\\Category', 31, NULL, NULL, NULL, '2017-01-21 04:06:04', '2017-01-21 04:06:04'),
(40, 1, '::1', '2017-01-21 04:06:55', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 31, 'sortOrder', '3', '3', '2017-01-21 04:06:55', '2017-01-21 04:06:55'),
(41, 1, '::1', '2017-01-21 04:09:02', 'Create', 'AMZ\\PostBundle\\Entity\\Category', 32, NULL, NULL, NULL, '2017-01-21 04:09:02', '2017-01-21 04:09:02'),
(42, 1, '::1', '2017-01-21 13:33:34', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 20, 'sortOrder', '1', '1', '2017-01-21 13:33:34', '2017-01-21 13:33:34'),
(43, 1, '::1', '2017-01-21 13:33:34', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 20, 'content', '<p>&lt;article class=&quot;p-4&quot;&gt;<br />\r\n&lt;img class=&quot;img-fluid&quot; src=&quot;dist/images/sidebar_2.jpg&quot; alt=&quot;&quot;&gt;<br />\r\n&lt;h4 class=&quot;text-uppercase font-weight-bold mb-2&quot;&gt;Năng lực&lt;/h4&gt;<br />\r\n&lt;ul cl', '<article class="p-4">\r\n<img class="img-fluid" src="dist/images/sidebar_2.jpg" alt="">\r\n<h4 class="text-uppercase font-weight-bold mb-2">Năng lực</h4>\r\n<ul class="list-unstyled font-weight-normal">\r\n    <li>. Giảng viên:  51</li>\r\n    <li>. Sinh viên:  987', '2017-01-21 13:33:34', '2017-01-21 13:33:34'),
(44, 1, '::1', '2017-01-21 19:18:21', 'Create', 'AMZ\\PostBundle\\Entity\\Post', 69, NULL, NULL, NULL, '2017-01-21 19:18:21', '2017-01-21 19:18:21'),
(45, 1, '::1', '2017-01-21 19:35:21', 'Update', 'AMZ\\PostBundle\\Entity\\Post', 69, 'thumbnail', NULL, '/nttu/web/upload/images/news_2.jpg', '2017-01-21 19:35:21', '2017-01-21 19:35:21'),
(46, 1, '::1', '2017-01-21 19:38:39', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 29, 'thumbnail', NULL, '/nttu/web/upload/images/post_5.jpg', '2017-01-21 19:38:39', '2017-01-21 19:38:39'),
(47, 1, '::1', '2017-01-21 19:38:39', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 29, 'sortOrder', '1', '1', '2017-01-21 19:38:39', '2017-01-21 19:38:39'),
(48, 1, '::1', '2017-01-21 19:38:39', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 29, 'isFeatured', '', '1', '2017-01-21 19:38:39', '2017-01-21 19:38:39'),
(49, 1, '::1', '2017-01-21 19:38:39', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 29, 'excerpt', NULL, 'Giới thiệu ngắn bậc sau đại học', '2017-01-21 19:38:39', '2017-01-21 19:38:39'),
(50, 1, '::1', '2017-01-21 19:38:39', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 29, 'content', NULL, '<p>Chi tiết bậc sau đại học</p>', '2017-01-21 19:38:39', '2017-01-21 19:38:39'),
(51, 1, '::1', '2017-01-21 19:38:39', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 29, 'link', NULL, 'http://google.com', '2017-01-21 19:38:39', '2017-01-21 19:38:39'),
(52, 1, '::1', '2017-01-22 04:26:00', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 12, 'thumbnail', NULL, '/nttu/web/upload/images/post_CNTT.jpg', '2017-01-22 04:26:00', '2017-01-22 04:26:00'),
(53, 1, '::1', '2017-01-22 04:26:00', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 12, 'sortOrder', '2', '2', '2017-01-22 04:26:00', '2017-01-22 04:26:00'),
(54, 1, '::1', '2017-01-22 04:26:00', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 12, 'excerpt', NULL, 'Giới thiệu ngắn - nghiên cứu', '2017-01-22 04:26:00', '2017-01-22 04:26:00'),
(55, 1, '::1', '2017-01-22 04:26:00', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 12, 'content', NULL, '<p>M&ocirc; tả nội dung nghi&ecirc;n cứu</p>', '2017-01-22 04:26:00', '2017-01-22 04:26:00'),
(56, 1, '::1', '2017-01-22 04:43:43', 'Create', 'AMZ\\PostBundle\\Entity\\Category', 33, NULL, NULL, NULL, '2017-01-22 04:43:43', '2017-01-22 04:43:43'),
(57, 1, '::1', '2017-01-22 05:05:07', 'Create', 'AMZ\\PostBundle\\Entity\\Post', 70, NULL, NULL, NULL, '2017-01-22 05:05:07', '2017-01-22 05:05:07'),
(58, 1, '::1', '2017-01-22 05:10:30', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 32, 'thumbnail', NULL, '/nttu/web/upload/images/post_10.jpg', '2017-01-22 05:10:30', '2017-01-22 05:10:30'),
(59, 1, '::1', '2017-01-22 05:10:30', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 32, 'sortOrder', '1', '1', '2017-01-22 05:10:30', '2017-01-22 05:10:30'),
(60, 1, '::1', '2017-01-22 05:10:30', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 32, 'excerpt', NULL, 'đề tài cấp bộ', '2017-01-22 05:10:30', '2017-01-22 05:10:30'),
(61, 1, '::1', '2017-01-22 05:10:30', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 32, 'link', NULL, 'http://google.com', '2017-01-22 05:10:30', '2017-01-22 05:10:30'),
(62, 1, '::1', '2017-01-22 05:27:13', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 11, 'thumbnail', NULL, '/nttu/web/upload/images/post_1.jpg', '2017-01-22 05:27:13', '2017-01-22 05:27:13'),
(63, 1, '::1', '2017-01-22 05:27:13', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 11, 'sortOrder', '1', '1', '2017-01-22 05:27:13', '2017-01-22 05:27:13'),
(64, 1, '::1', '2017-01-22 05:27:13', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 11, 'excerpt', NULL, 'Giơi thiệu ngắn tuyển sinh', '2017-01-22 05:27:13', '2017-01-22 05:27:13'),
(65, 1, '::1', '2017-01-22 05:27:13', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 11, 'content', NULL, '<p>Nội dung tuyển sinh</p>', '2017-01-22 05:27:13', '2017-01-22 05:27:13'),
(66, 1, '::1', '2017-01-22 05:27:13', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 11, 'link', NULL, 'http://google.com', '2017-01-22 05:27:13', '2017-01-22 05:27:13'),
(67, 1, '::1', '2017-01-22 05:34:44', 'Create', 'AMZ\\PostBundle\\Entity\\Category', 34, NULL, NULL, NULL, '2017-01-22 05:34:44', '2017-01-22 05:34:44'),
(68, 1, '::1', '2017-01-22 05:37:16', 'Create', 'AMZ\\PostBundle\\Entity\\Post', 71, NULL, NULL, NULL, '2017-01-22 05:37:16', '2017-01-22 05:37:16'),
(69, 1, '::1', '2017-01-22 05:37:55', 'Update', 'AMZ\\PostBundle\\Entity\\Post', 71, 'thumbnail', NULL, '/nttu/web/upload/images/post_DHCD.jpg', '2017-01-22 05:37:55', '2017-01-22 05:37:55'),
(70, 1, '::1', '2017-01-22 05:37:55', 'Update', 'AMZ\\PostBundle\\Entity\\Post', 71, 'isFeatured', '', '1', '2017-01-22 05:37:55', '2017-01-22 05:37:55'),
(71, 1, '::1', '2017-01-22 05:37:55', 'Update', 'AMZ\\PostBundle\\Entity\\Post', 71, 'content', '<div class="media-body">\r\n			<h5 class="title mt-0"><a href="">Cơ sở chính</a></h5>\r\n			<b class="text-underline c-black">NTTU :</b>\r\n			Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore ma', '<div class="media-body">\r\n<h5 class="title mt-0"><a href="">Cơ sở ch&iacute;nh</a></h5>\r\n<b class="text-underline c-black">NTTU :</b> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna', '2017-01-22 05:37:55', '2017-01-22 05:37:55'),
(72, 1, '::1', '2017-01-22 05:50:20', 'Update', 'AMZ\\PostBundle\\Entity\\Post', 71, 'content', '<div class="media-body">\r\n<h5 class="title mt-0"><a href="">Cơ sở ch&iacute;nh</a></h5>\r\n<b class="text-underline c-black">NTTU :</b> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna', '<div class="media-body">\r\n<b class="text-underline c-black">NTTU :</b> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.\r\n\r\n<ul class="list-unstyled list-sub mt', '2017-01-22 05:50:20', '2017-01-22 05:50:20'),
(73, 1, '::1', '2017-01-22 06:52:00', 'Create', 'AMZ\\PostBundle\\Entity\\Category', 35, NULL, NULL, NULL, '2017-01-22 06:52:00', '2017-01-22 06:52:00'),
(74, 1, '::1', '2017-01-22 06:54:02', 'Create', 'AMZ\\PostBundle\\Entity\\Post', 72, NULL, NULL, NULL, '2017-01-22 06:54:02', '2017-01-22 06:54:02'),
(75, 1, '::1', '2017-01-22 10:12:53', 'Create', 'AMZ\\PostBundle\\Entity\\Category', 36, NULL, NULL, NULL, '2017-01-22 10:12:53', '2017-01-22 10:12:53'),
(76, 1, '::1', '2017-01-22 10:14:45', 'Create', 'AMZ\\PostBundle\\Entity\\Post', 73, NULL, NULL, NULL, '2017-01-22 10:14:45', '2017-01-22 10:14:45'),
(77, 1, '::1', '2017-01-22 10:53:14', 'Create', 'AMZ\\PostBundle\\Entity\\Category', 37, NULL, NULL, NULL, '2017-01-22 10:53:14', '2017-01-22 10:53:14'),
(78, 1, '::1', '2017-01-22 16:15:39', 'Create', 'AMZ\\PostBundle\\Entity\\Post', 74, NULL, NULL, NULL, '2017-01-22 16:15:40', '2017-01-22 16:15:40'),
(79, 1, '::1', '2017-01-23 15:47:24', 'Create', 'AMZ\\PostBundle\\Entity\\Category', 38, NULL, NULL, NULL, '2017-01-23 15:47:24', '2017-01-23 15:47:24'),
(80, 1, '::1', '2017-01-23 15:53:07', 'Create', 'AMZ\\PostBundle\\Entity\\Post', 75, NULL, NULL, NULL, '2017-01-23 15:53:07', '2017-01-23 15:53:07'),
(81, 1, '::1', '2017-01-23 16:02:55', 'Update', 'AMZ\\PostBundle\\Entity\\Post', 75, 'isFeatured', '', '1', '2017-01-23 16:02:55', '2017-01-23 16:02:55'),
(82, 1, '::1', '2017-01-23 16:08:35', 'Update', 'AMZ\\PostBundle\\Entity\\Post', 76, 'thumbnail', '/nttu/web/upload/images/slide_4.jpg', '/nttu/web/upload/images/slide_5.jpg', '2017-01-23 16:08:35', '2017-01-23 16:08:35'),
(83, 1, '::1', '2017-01-23 16:08:35', 'Update', 'AMZ\\PostBundle\\Entity\\Post', 76, 'content', 'Được học tập trong môi trường khang trang hiện đại, học phí ổn định...', '<p>Được học tập trong m&ocirc;i trường khang trang hiện đại, học ph&iacute; ổn định...</p>', '2017-01-23 16:08:35', '2017-01-23 16:08:35'),
(84, 1, '::1', '2017-01-23 16:09:40', 'Create', 'AMZ\\PostBundle\\Entity\\Post', 77, NULL, NULL, NULL, '2017-01-23 16:09:40', '2017-01-23 16:09:40'),
(85, 1, '::1', '2017-01-24 09:18:04', 'Create', 'AMZ\\PostBundle\\Entity\\Event', 2, NULL, NULL, NULL, '2017-01-24 09:18:04', '2017-01-24 09:18:04'),
(86, 1, '::1', '2017-01-24 14:26:44', 'Update', 'AMZ\\PostBundle\\Entity\\Category', 10, 'link', 'phongdaotao.ntt.edu.vn', 'http://phongdaotao.ntt.edu.vn', '2017-01-24 14:26:44', '2017-01-24 14:26:44'),
(89, 1, '::1', '2017-01-24 14:59:35', 'Update', 'AMZ\\PostBundle\\Entity\\Event', 1, 'startDate', '2017-01-31 10:30:00', '2017-01-31 10:30:00', '2017-01-24 14:59:35', '2017-01-24 14:59:35'),
(90, 1, '::1', '2017-01-24 14:59:35', 'Update', 'AMZ\\PostBundle\\Entity\\Event', 1, 'excerpt', NULL, 'Giới thiệu ngắn lễ phát động', '2017-01-24 14:59:35', '2017-01-24 14:59:35'),
(97, 1, '::1', '2017-01-24 15:30:38', 'Update', 'AMZ\\PostBundle\\Entity\\Event', 2, 'startDate', '2017-01-03 09:00:00', '2017-01-12 05:20:00', '2017-01-24 15:30:38', '2017-01-24 15:30:38'),
(98, 1, '::1', '2017-01-24 15:31:31', 'Update', 'AMZ\\PostBundle\\Entity\\Event', 2, 'startDate', '2017-01-12 05:20:00', '2017-01-30 06:50:00', '2017-01-24 15:31:31', '2017-01-24 15:31:31'),
(99, 1, '::1', '2017-01-24 15:36:35', 'Create', 'AMZ\\PostBundle\\Entity\\Event', 3, NULL, NULL, NULL, '2017-01-24 15:36:35', '2017-01-24 15:36:35'),
(100, 1, '::1', '2017-01-24 16:35:21', 'Update', 'AMZ\\SliderBundle\\Entity\\Item', 1, 'fullSizeThumbnail', 'http://develop.maxxgroup.com.vn/website/nangtamvocviet.vn/web/upload/images/homepage-header-banner-1.jpg', '/nttu/web/upload/images/slide_1.jpg', '2017-01-24 16:35:21', '2017-01-24 16:35:21'),
(101, 1, '::1', '2017-01-24 16:35:21', 'Update', 'AMZ\\SliderBundle\\Entity\\Item', 1, 'sortOrder', '1', '1', '2017-01-24 16:35:21', '2017-01-24 16:35:21'),
(102, 1, '::1', '2017-01-24 16:35:21', 'Update', 'AMZ\\SliderBundle\\Entity\\Item', 1, 'link', NULL, 'http://abc.com', '2017-01-24 16:35:21', '2017-01-24 16:35:21'),
(103, 1, '::1', '2017-01-24 16:35:21', 'Update', 'AMZ\\SliderBundle\\Entity\\Item', 1, 'title', NULL, 'Phát động cuộc thi tìm hiểu về chiến lược của NTTU', '2017-01-24 16:35:21', '2017-01-24 16:35:21'),
(104, 1, '::1', '2017-01-24 16:35:21', 'Update', 'AMZ\\SliderBundle\\Entity\\Item', 1, 'content', NULL, '<p>Cuộc thi sẽ diễn ra từ ng&agrave;y 21-11-2016 đến 13-12-2016. Lễ trao giải sẽ diễn ra l&uacute;c 9 giờ s&aacute;ng thứ Bảy 17-12-2016 ...</p>', '2017-01-24 16:35:21', '2017-01-24 16:35:21'),
(105, 1, '::1', '2017-01-24 16:36:26', 'Update', 'AMZ\\SliderBundle\\Entity\\Item', 1, 'sortOrder', '1', '1', '2017-01-24 16:36:26', '2017-01-24 16:36:26'),
(106, 1, '::1', '2017-01-24 16:37:49', 'Remove', 'AMZ\\SliderBundle\\Entity\\Item', 6, NULL, NULL, NULL, '2017-01-24 16:37:49', '2017-01-24 16:37:49'),
(107, 1, '::1', '2017-01-24 16:38:01', 'Remove', 'AMZ\\SliderBundle\\Entity\\Item', 5, NULL, NULL, NULL, '2017-01-24 16:38:01', '2017-01-24 16:38:01'),
(108, 1, '::1', '2017-01-24 16:38:13', 'Remove', 'AMZ\\SliderBundle\\Entity\\Item', 4, NULL, NULL, NULL, '2017-01-24 16:38:13', '2017-01-24 16:38:13'),
(109, 1, '::1', '2017-01-24 16:38:24', 'Remove', 'AMZ\\SliderBundle\\Entity\\Item', 3, NULL, NULL, NULL, '2017-01-24 16:38:24', '2017-01-24 16:38:24'),
(110, 1, '::1', '2017-01-24 16:48:00', 'Create', 'AMZ\\SliderBundle\\Entity\\Item', 7, NULL, NULL, NULL, '2017-01-24 16:48:01', '2017-01-24 16:48:01');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` longtext COLLATE utf8_unicode_ci,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'image|text|textarea|editor',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `name`, `value`, `type`, `created_at`, `updated_at`) VALUES
(1, 'file-mau-xuat-danh-sach-chuan-lop', 'http://develop.maxxgroup.com.vn/website/nangtamvocviet.vn/web/upload/file/danh-sach-hoc-sinh-chuan.xlsx', 'file', '2016-12-01 00:00:00', '2016-12-06 22:53:23'),
(2, 'file-mau-xuat-danh-sach-chuan-can-do', 'http://develop.maxxgroup.com.vn/website/nangtamvocviet.vn/web/upload/file/danh-sach-hoc-sinh-chuan-can-do.xlsx', 'file', '2016-12-01 00:00:00', '2016-12-06 22:53:35');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `post_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'post|page',
  `template` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` smallint(6) DEFAULT NULL COMMENT 'draft|publish|trash',
  `is_featured` tinyint(1) DEFAULT NULL,
  `excerpt` longtext COLLATE utf8_unicode_ci,
  `content` longtext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `seo_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seo_meta_keyword` longtext COLLATE utf8_unicode_ci,
  `seo_meta_description` longtext COLLATE utf8_unicode_ci,
  `seo_facebook_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seo_facebook_description` longtext COLLATE utf8_unicode_ci,
  `seo_facebook_thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `title`, `slug`, `thumbnail`, `post_type`, `template`, `status`, `is_featured`, `excerpt`, `content`, `created_at`, `updated_at`, `seo_title`, `seo_meta_keyword`, `seo_meta_description`, `seo_facebook_title`, `seo_facebook_description`, `seo_facebook_thumbnail`, `link`) VALUES
(59, 'tin dao tao', 'tin-dao-tao', 'http://localhost/nttu/web/upload/images/homepage-footer-bottom-right.jpg', 'post', NULL, 2, 0, 'ffds', '<p>dfg</p>', '2017-01-13 00:00:00', '2017-01-16 11:10:27', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(60, 'tin dao tao 2', 'tin-dao-tao-2', 'http://localhost/nttu/web/upload/images/news6.jpg', 'post', NULL, 2, 0, 'ffds', '<p>dfg</p>', '2017-01-13 00:00:00', '2017-01-16 11:09:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(63, 'NTTU tuyển sinh Thạc sĩ CNTT khóa #K27', 'tuyen-sinh-thac-sy-cntt-khoa-27', 'http://localhost/nttu/web/upload/images/post_1.jpg', 'post', 'standard', 2, 1, 'Nhằm tạo điều kiện cho các học viên cao học theo ngành công nghệ Thông tin, Ban Giám hiệu trường ...', NULL, '2017-01-17 17:57:06', '2017-01-17 17:57:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(64, 'NTTU tuyển sinh Cử nhân CNTT khóa #K27', 'tuyen-sinh-cu-nhan-cntt-khoa-27', 'http://localhost/nttu/web/upload/images/post_2.jpg', 'post', 'standard', 2, 1, 'Nhằm tạo điều kiện cho các học viên cao học theo ngành công nghệ Thông tin, Ban Giám hiệu trường ...', NULL, '2017-01-17 17:58:47', '2017-01-17 17:58:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(65, 'NTTU tuyển sinh Giáo sư CNTT khóa #K27', 'tuyen-sinh-giao-su-cntt-khoa-27', 'http://localhost/nttu/web/upload/images/post_3.jpg', 'post', 'standard', 2, 1, 'sdgs', NULL, '2017-01-17 18:00:31', '2017-01-17 18:00:31', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(66, 'NTTU tuyển sinh Thạc sĩ CNTT khóa #K27', 'ntt-tuyen-sinh-thac-sy', 'http://localhost/nttu/web/upload/images/post_5.jpg', 'post', 'standard', 2, 1, 'Nhằm tạo điều kiện cho các học viên cao học theo ngành công nghệ Thông tin, Ban Giám hiệu trường ...', '<p>Nhằm tạo điều kiện cho c&aacute;c học vi&ecirc;n cao học theo ng&agrave;nh c&ocirc;ng nghệ Th&ocirc;ng tin, Ban Gi&aacute;m hiệu trường ...</p>', '2017-01-18 16:20:05', '2017-01-18 16:20:05', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(67, 'NTTU tuyển sinh đại học CNTT khóa #K27', 'nttu-tuyen-sinh-dai-hoc', 'http://localhost/nttu/web/upload/images/post_6.jpg', 'post', 'standard', 2, 1, 'Nhằm tạo điều kiện cho các học viên cao học theo ngành công nghệ Thông tin, Ban Giám hiệu trường ...', '<p><span style="color: rgb(99, 99, 99); font-family: &quot;Open Sans&quot;, sans-serif;">Nhằm tạo điều kiện cho c&aacute;c <strong>học</strong> vi&ecirc;n cao học theo ng&agrave;nh c&ocirc;ng nghệ Th&ocirc;ng tin, Ban Gi&aacute;m hiệu trường ...</span></p>', '2017-01-18 16:22:05', '2017-01-18 16:23:01', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 'NTTU tuyển sinh cao đẳng CNTT khóa #K27', 'tuyen-sinh-cao-dang-cntt-khoa-27', 'http://localhost/nttu/web/upload/images/post_7.jpg', 'post', 'standard', 2, 1, 'Nhằm tạo điều kiện cho các học viên cao học theo ngành công nghệ Thông tin, Ban Giám hiệu trường ...', '<p><span style="color: rgb(99, 99, 99); font-family: &quot;Open Sans&quot;, sans-serif;">Nhằm tạo điều kiện cho c&aacute;c học vi&ecirc;n cao học theo ng&agrave;nh c&ocirc;ng nghệ Th&ocirc;ng tin, Ban Gi&aacute;m hiệu trường ...</span></p>', '2017-01-18 16:24:37', '2017-01-18 16:25:08', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(69, 'Tin tuyển sinh cao học', 'tin-tuyen-sinh-cao-hoc', '/nttu/web/upload/images/news_2.jpg', 'post', 'standard', 2, 1, 'Giới thiệu ngắn bậc cao học', '<p><u>Giới</u> <strong>thiệu</strong> chi tiết <em>bậc</em> cao học</p>', '2017-01-21 19:18:21', '2017-01-21 19:35:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(70, 'Chương trình Đào tạo doanh nhân toàn cầu: Senior Entrepreneurship Training (SET) và Junior Entrepreneurship Training (JET)', 'chuong-trinh-dao-tao-doanh-nhan-toan-cau', '/nttu/web/upload/images/news_1.jpg', 'post', 'standard', 2, 1, 'Đây là 2 chương trình nằm trong chuỗi đào tạo doanh nhân toàn cầu do chính phủ Hàn Quốc và Tổ chức Giáo dục Khoa học và Văn hóa của Liên hợp quốc ( UNESCO) tài trợ tại Việt Nam.', '<p>Nội dung ch&iacute;nh</p>', '2017-01-22 05:05:07', '2017-01-22 05:05:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(71, 'Cơ sở chính', 'co-so-chinh', '/nttu/web/upload/images/post_DHCD.jpg', 'post', 'standard', 2, 1, NULL, '<div class="media-body">\r\n<b class="text-underline c-black">NTTU :</b> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.\r\n\r\n<ul class="list-unstyled list-sub mt-1 mb-3">\r\n	<li class="p-0"><i class="fa fa-map-marker c-lime-a5" aria-hidden="true"></i><span class="c-black">: 300A Nguyễn Tất Th&agrave;nh, P13, Q4, Tp HCM</span></li>\r\n	<li class="p-0"><i class="fa fa-phone c-lime-a5" aria-hidden="true"></i><span class="c-black">: (08). 62 619 423 - (08). 39 411 189</span></li>\r\n</ul>\r\n\r\n<p><i class="fa fa-angle-double-right"></i><a href="#">xem bản đồ hướng dẫn</a></p>\r\n</div>', '2017-01-22 05:37:16', '2017-01-22 05:50:20', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(72, 'NTTU chú trọng về môi trường học tập cho sinh viên', 'nttu-chu-trong-moi-truong-hoc-tap', '/nttu/web/upload/images/post_2.jpg', 'post', 'standard', 2, 1, 'Giới thiệu ngắn', '<p>Nội dung</p>', '2017-01-22 06:54:02', '2017-01-22 06:54:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(73, 'Những cột mốc phát triển', 'nhung-cot-moc-phat-trien', NULL, 'post', 'standard', 2, 1, '<ul class="list-unstyled">\r\n				<li><b class="text-underline c-black">1999</b>\r\n				 - Trung tâm Đào tạo Công nhân may thuộc Công ty Dệt May Sài Gòn.</li>\r\n				 <li><b class="text-underline c-black">2002</b>\r\n				 - Trường Kinh tế Kỹ thuật Nghiệp vụ Bán công Nguyễn Tất Thành.</li>\r\n				 <li><b class="text-underline c-black">2005</b>\r\n				 - Trường Cao đẳng Kinh tế Kỹ thuật Nghiệp vụ Bán côngNguyễn Tất Thành</li>\r\n				 <li><b class="text-underline c-black">2006</b>\r\n				 - Trường Cao đẳng Nguyễn Tất Thành</li>\r\n				 <li><b class="text-underline c-black">2011</b>\r\n				 - Trường Đại học Nguyễn Tất Thành</li>\r\n			</ul>', '<p>Những cột mốc ph&aacute;t triển content</p>', '2017-01-22 10:14:45', '2017-01-22 10:14:45', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 'Tổng quan', 'tong-quan', '/nttu/web/upload/images/thumb-170.jpg', 'post', 'standard', 2, 1, 'Giới thiệu ngắn tổng quan', '<p>Nội dung tổng quan</p>', '2017-01-22 16:15:39', '2017-01-22 16:15:39', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 'Hạnh phúc của doanh nghiệp', 'hanh-phuc-cua-doanh-nghiep', '/nttu/web/upload/images/slide_4.jpg', 'post', 'standard', 2, 1, 'Tìm được những sinh viên có năng lực, kỹ năng làm việc...', '<p><span style="color: rgb(99, 99, 99); font-family: &quot;Open Sans&quot;, sans-serif; font-size: 16px; text-align: center; background-color: rgb(241, 241, 241);">T&igrave;m được những sinh vi&ecirc;n c&oacute; năng lực, kỹ năng l&agrave;m việc...</span></p>', '2017-01-23 15:53:06', '2017-01-23 16:02:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(76, 'Hạnh phúc của sinh viên', 'hanh-phuc-cua-sinh-vien', '/nttu/web/upload/images/slide_5.jpg', 'post', 'standard', 2, 1, 'Được học tập trong môi trường khang trang hiện đại, học phí ổn định...', '<p>Được học tập trong m&ocirc;i trường khang trang hiện đại, học ph&iacute; ổn định...</p>', '2017-01-23 15:53:06', '2017-01-23 16:08:35', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(77, 'Hạnh phúc của nhà trường', 'hanh-phuc-cua-nha-truong', '/nttu/web/upload/images/slide_6.jpg', 'post', 'standard', 2, 1, NULL, NULL, '2017-01-23 16:09:40', '2017-01-23 16:09:40', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `posts_categories`
--

CREATE TABLE `posts_categories` (
  `post_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `posts_categories`
--

INSERT INTO `posts_categories` (`post_id`, `category_id`) VALUES
(59, 10),
(60, 13),
(63, 20),
(64, 20),
(65, 20),
(66, 11),
(67, 11),
(68, 11),
(69, 29),
(70, 33),
(71, 34),
(72, 35),
(73, 36),
(74, 37),
(75, 38),
(76, 38),
(77, 38);

-- --------------------------------------------------------

--
-- Table structure for table `posts_tags`
--

CREATE TABLE `posts_tags` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_category`
--

CREATE TABLE `post_category` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort_order` smallint(6) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT NULL,
  `excerpt` longtext COLLATE utf8_unicode_ci,
  `content` longtext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `seo_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seo_meta_keyword` longtext COLLATE utf8_unicode_ci,
  `seo_meta_description` longtext COLLATE utf8_unicode_ci,
  `seo_facebook_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seo_facebook_description` longtext COLLATE utf8_unicode_ci,
  `seo_facebook_thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `level` smallint(6) DEFAULT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `post_category`
--

INSERT INTO `post_category` (`id`, `parent_id`, `title`, `slug`, `thumbnail`, `sort_order`, `is_featured`, `excerpt`, `content`, `created_at`, `updated_at`, `seo_title`, `seo_meta_keyword`, `seo_meta_description`, `seo_facebook_title`, `seo_facebook_description`, `seo_facebook_thumbnail`, `level`, `link`) VALUES
(10, NULL, 'Đào tạo', 'dao-tao', NULL, NULL, 0, 'NTTU - Là một trường ĐH định hướng ứng dụng và thực hành, chúng tôi hướng tới mục tiêu đáp ứng nhu cầu giáo dục đại học đại chúng, tạo lập một môi trường học tập tích cực và trải nghiệm thực tiễn cho mọi sinh viên, trang bị cho họ năng lực tự học, tinh thần sáng tạo khởi nghiệp.', '<h4 class="text-uppercase font-weight-bold">Năng lực đ&agrave;o tạo</h4>\r\n\r\n<p>Hiện nay, nh&agrave; trường c&oacute; đội ngũ hơn <b>2.000 CB, GV, CNV</b> trong đ&oacute; hơn <b>62%</b> c&oacute; tr&igrave;nh độ <b>GS., PGS., TS., ThS.</b>, c&ograve;n lại l&agrave; Kỹ sư v&agrave; Cử nh&acirc;n, với quy m&ocirc; hơn <b>26.000 HSSV.</b></p>\r\n\r\n<h4 class="text-uppercase font-weight-bold">Chất lượng đ&agrave;o tạo</h4>\r\n\r\n<p>X&acirc;y dựng v&agrave; triển khai &aacute;p dụng hệ thống quản l&yacute; chất lượng theo ti&ecirc;u chuẩn ISO 9001:2008.</p>\r\n\r\n<p>Đạt chuẩn khi tham gia kiểm định chất lượng theo c&aacute;c ti&ecirc;u chuẩn của quốc gia v&agrave; quốc tế.</p>\r\n\r\n<ul class="list-unstyled">\r\n	<li><a class="c-lime-a5" href="#">. QS - Star</a></li>\r\n	<li><a class="c-lime-a5" href="#">. ISO 9001 : 2008</a></li>\r\n</ul>\r\n\r\n<p>N&acirc;ng cao chất lượng gi&aacute;o dục đ&agrave;o tạo của trường theo hướng chuẩn h&oacute;a, hiện đại h&oacute;a, d&acirc;n chủ h&oacute;a v&agrave; hội nhập quốc tế.</p>', '2017-01-13 00:00:00', '2017-01-24 14:26:44', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'http://phongdaotao.ntt.edu.vn'),
(11, NULL, 'Tuyển sinh', 'tuyen-sinh', '/nttu/web/upload/images/post_1.jpg', 1, 0, 'Giơi thiệu ngắn tuyển sinh', '<p>Nội dung tuyển sinh</p>', '2017-01-12 18:01:57', '2017-01-22 05:27:13', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'http://google.com'),
(12, NULL, 'Nghiên cứu', 'nghien-cuu', '/nttu/web/upload/images/post_CNTT.jpg', 2, 0, 'Giới thiệu ngắn - nghiên cứu', '<p>M&ocirc; tả nội dung nghi&ecirc;n cứu</p>', '2017-01-12 18:05:16', '2017-01-22 04:26:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL),
(13, 10, 'Khối ngành công nghệ kỹ thuật', 'cong-nghe-ky-thuat', 'http://localhost/nttu/web/upload/images/post_CNTT.jpg', 3, 0, 'NTTU - Bước vào thiên niên kỷ thứ ba, khoa học và kỹ thuật đã trở thành yếu tố cốt tử của sự phát triển, là lực lượng sản xuất trực tiếp của nền kinh tế toàn cầu.', NULL, '2017-01-12 18:07:51', '2017-01-19 13:44:06', NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL),
(14, 10, 'Khối ngành Kinh tế - Tài chính', 'y-te', 'http://localhost/nttu/web/upload/images/post_KTTC.jpg', 2, 0, 'NTTU - Tại ĐH Nguyễn Tất Thành Sinh viên sẽ được giảng dạy theo phương pháp sáng tạo, khơi dậy tư duy, tiếp cận với những công nghệ tân tiến nhất.', NULL, '2017-01-12 18:27:54', '2017-01-19 13:43:28', NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL),
(20, 13, 'Khoa công nghệ thông tin', 'khoa-cong-nghe-thong-tin', 'http://localhost/nttu/web/upload/images/cate_CNTT.jpg', 1, 0, 'TOP 5 Khoa trọng điểm - Có nhiệm vụ đào tạo nguồn nhân lực công nghệ thông tin chất lượng cao góp phần tích cực vào sự phát triển của nền công nghiệp công nghệ thông tin Việt Nam, góp phần đẩy mạnh sự nghiệp công nghiệp hóa, hiện đại hóa đất nước.', '<article class="p-4">\r\n<img class="img-fluid" src="dist/images/sidebar_2.jpg" alt="">\r\n<h4 class="text-uppercase font-weight-bold mb-2">Năng lực</h4>\r\n<ul class="list-unstyled font-weight-normal">\r\n    <li>. Giảng viên:  51</li>\r\n    <li>. Sinh viên:  987</li>\r\n</ul>\r\n<h4 class="text-uppercase font-weight-bold mb-2">Đào tạo</h4>\r\n<ul class="list-unstyled font-weight-normal">\r\n    <li>. Thạc sỹ CNTT</li>\r\n    <li>. Cử nhân CNTT</li>\r\n</ul>\r\n<h4 class="text-uppercase font-weight-bold mb-2">Các bộ môn</h4>\r\n<ul class="list-unstyled font-weight-normal">\r\n    <li>. Cơ sở ngành</li>\r\n    <li>. Kỹ thuật máy tính</li>\r\n    <li>. Kỹ thuật phần mềm</li>\r\n</ul>\r\n<h4 class="text-uppercase font-weight-bold mb-2">Cơ sở đào tạo</h4>\r\n<ul class="list-unstyled font-weight-normal">\r\n    <li><b>. Cơ sở 1</b><br>\r\n        <p class="pl-2">300A Nguyễn Tất Thành, P.13, Q.4, TP.HCM</p></li>\r\n    <li><b>. Cơ sở 2</b><br>\r\n        <p class="pl-2">38 Tôn Thất Huyết, P.16, Quận 4, TP.HCM</p></li>\r\n</ul>\r\n<a class="font-weight-bold c-black" href="http://fit.ntt.edu.vn/"><i class="fa fa-chevron-circle-right mr-2 c-red"></i>fit.ntt.edu.vn</a>\r\n</article>', '2017-01-16 09:33:52', '2017-01-21 13:33:34', NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL),
(21, 14, 'Khoa Tài chính Kế toán', 'khoa-tai-chinh-ke-toan', NULL, 1, 0, NULL, NULL, '2017-01-16 09:35:41', '2017-01-16 09:36:26', NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL),
(22, 13, 'Khoa Kiến trúc - XD - Mỹ thuật ứng dụng', 'khoa-kien-truc-xd-my-thuat-ung-dung', NULL, 1, 0, NULL, NULL, '2017-01-18 17:12:15', '2017-01-18 17:12:15', NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL),
(23, 10, 'Khối ngành Khoa học Xã hội và Nhân văn', 'khoi-nganh-khoa-hoc-xa-hoi-va-nhan-van', 'http://localhost/nttu/web/upload/images/post_KHXH.jpg', 4, 0, 'NTTU - Là trung tâm đào tạo bậc cử nhân các ngành khoa học xã hội và nhân văn có quy mô lớn, uy tín nhất khu vực phía nam. Hiện nay, NTTU đào tạo 54 chương trình giáo dục thuộc 2 ngành đào tạo các hệ chính quy tập trung, văn bằng hai chính quy, liên thông/ hoàn thiện đại học (chính quy/ vừa làm vừa học)', NULL, '2017-01-18 17:14:48', '2017-01-19 17:00:31', NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL),
(24, 23, 'Khoa Ngoại ngữ', 'khoa-ngoai-ngu', NULL, 1, 0, NULL, NULL, '2017-01-18 17:15:55', '2017-01-18 17:15:55', NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL),
(25, 10, 'Sức khỏe', 'suc-khoe', 'http://localhost/nttu/web/upload/images/post_KHSK.jpg', 1, 0, 'NTTU - Nhà trường và giảng viên thể hiện trong chương trình giảng dạy và mô hình tổ chức hợp lý đáp ứng nhu cầu xã hội nhằm mục đích bảo vệ và chăm sóc sức khỏe nhân dân có hiệu quả cao nhất trong quá trình hội nhập và phát triển đất nước.', NULL, '2017-01-19 13:40:29', '2017-01-19 17:17:42', NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL),
(26, 10, 'Nghệ thuật - Mỹ thuật', 'nghe-thuat-my-thuat', NULL, 5, 0, NULL, NULL, '2017-01-19 13:41:17', '2017-01-19 16:59:16', NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL),
(27, 10, 'Khoa học cơ bản', 'khoa-hoc-co-ban', 'http://localhost/nttu/web/upload/images/post_KHCB.jpg', 6, 0, 'NTTU - Khoa học Cơ bản là một đơn vị độc lập trong hệ thống 15 Khoa đào tạo ngành trực thuộc Ban giám hiệu Trường Đại học Nguyễn Tất Thành, được nâng cấp, chuyển đổi sang cơ chế quản lý Trường ĐH Nguyễn Tất Thành theo quyết định số 621/QĐ-Ttg do thủ tướng chính phủ ban hành và ký ngày 26/04/2011.', NULL, '2017-01-19 13:42:30', '2017-01-19 17:19:10', NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL),
(28, 25, 'Khoa Y', 'khoa-y', NULL, 1, 0, NULL, NULL, '2017-01-19 17:20:43', '2017-01-19 17:20:43', NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL),
(29, 11, 'Bậc sau đại học', 'bac-sau-dai-hoc', '/nttu/web/upload/images/post_5.jpg', 1, 1, 'Giới thiệu ngắn bậc sau đại học', '<p>Chi tiết bậc sau đại học</p>', '2017-01-21 04:04:29', '2017-01-21 19:38:39', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'http://google.com'),
(30, 11, 'Bậc đại học', 'bac-dai-hoc', NULL, 2, 0, NULL, NULL, '2017-01-21 04:05:07', '2017-01-21 04:05:07', NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL),
(31, 27, 'Bậc cao đẳng và liên thông', 'bac-cao-dang-va-lien-thong', NULL, 3, 0, NULL, NULL, '2017-01-21 04:06:04', '2017-01-21 04:06:55', NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL),
(32, 12, 'Đề tài cấp bộ', 'de-tai-cap-bo', '/nttu/web/upload/images/post_10.jpg', 1, 0, 'đề tài cấp bộ', NULL, '2017-01-21 04:09:02', '2017-01-22 05:10:30', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'http://google.com'),
(33, NULL, 'Hợp tác doanh nghiệp', 'hop-tac-doanh-nghiep', '/nttu/web/upload/images/cate_HTDN.jpg', 1, 0, 'NTTU - Mối quan hệ hợp tác giữa nhà trường và doanh nghiệp là điều có ý nghĩa rất quan trọng. Sự hiệp lực giữa trường đại học với nhà nước và các doanh nghiệp được coi là động lực cốt yếu của những xã hội và những nền kinh tế dựa trên tri thức.', '<p><span style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, sans-serif; text-align: justify; background-color: rgb(239, 236, 232);">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy ...</span></p>\r\n\r\n<p><span style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, sans-serif; text-align: justify; background-color: rgb(239, 236, 232);">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy ...</span></p>\r\n\r\n<p><span style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, sans-serif; text-align: justify; background-color: rgb(239, 236, 232);">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy ...</span></p>', '2017-01-22 04:43:43', '2017-01-22 04:43:43', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'http://google.com'),
(34, NULL, 'Cơ sở đào tạo', 'co-so-dao-tao', NULL, 1, 0, 'NTTU : Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.', '<p>Cơ sở đ&agrave;o tạo nội dung</p>', '2017-01-22 05:34:44', '2017-01-22 05:34:44', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'http://google.com'),
(35, NULL, 'Môi trường học tập', 'moi-truong-hoc-tap', NULL, 1, 0, 'NTTU - Với mục đích bổ sung và nâng cao kiến thức khoa học Công nghệ Thông tin và trang bị những kiến thức cơ bản phục vụ cho công việc nghiên cứu khoa học, tạo tiền đề cho nghiên cứu sinh tiến sĩ ...', '<p>Nội dung m&ocirc;i trường học tập cat</p>', '2017-01-22 06:52:00', '2017-01-22 06:52:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL),
(36, NULL, 'Giới thiệu', 'gioi-thieu', NULL, 1, 0, 'Giới thiệu ngắn', '<p>M&ocirc; tả giới thiệu</p>', '2017-01-22 10:12:53', '2017-01-22 10:12:53', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'http://google.com'),
(37, NULL, 'Giới thiệu chung', 'gioi-thieu-chung', NULL, 1, 0, 'Giới thiệu chung', '<p>Nội dung giới thiệu chung</p>', '2017-01-22 10:53:14', '2017-01-22 10:53:14', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL),
(38, NULL, '5 lý do', '5-ly-do', '/nttu/web/upload/images/slide_4.jpg', 1, 0, 'Giới thiệu ngắn', '<p><span style="color: rgb(99, 99, 99); font-family: &quot;Open Sans&quot;, sans-serif; font-size: 16px; text-align: center; background-color: rgb(241, 241, 241);">T&igrave;m được những sinh vi&ecirc;n c&oacute; năng lực, kỹ năng l&agrave;m việc...</span></p>', '2017-01-23 15:47:24', '2017-01-23 15:47:24', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `post_gallery`
--

CREATE TABLE `post_gallery` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `small_size_thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `full_size_thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_tag`
--

CREATE TABLE `post_tag` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `seo_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seo_meta_keyword` longtext COLLATE utf8_unicode_ci,
  `seo_meta_description` longtext COLLATE utf8_unicode_ci,
  `seo_facebook_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seo_facebook_description` longtext COLLATE utf8_unicode_ci,
  `seo_facebook_thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` smallint(6) DEFAULT NULL COMMENT 'male|female',
  `date_of_birth` date DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `last_height` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_weight` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_bmi` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_result` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_day_weight` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slider_items`
--

CREATE TABLE `slider_items` (
  `id` int(11) NOT NULL,
  `small_size_thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `full_size_thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort_order` smallint(6) DEFAULT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `slider_items`
--

INSERT INTO `slider_items` (`id`, `small_size_thumbnail`, `full_size_thumbnail`, `sort_order`, `link`, `content`, `created_at`, `updated_at`, `title`) VALUES
(1, NULL, '/nttu/web/upload/images/slide_1.jpg', 1, 'http://abc.com', '<p>Cuộc thi sẽ diễn ra từ ng&agrave;y 21-11-2016 đến 13-12-2016. Lễ trao giải sẽ diễn ra l&uacute;c 9 giờ s&aacute;ng thứ Bảy 17-12-2016 ...</p>', '2016-11-24 11:05:04', '2017-01-24 16:36:26', 'Phát động cuộc thi tìm hiểu về chiến lược của NTTU'),
(2, NULL, '/nttu/web/upload/images/slide_3.jpg', 1, NULL, NULL, '2016-11-24 11:05:04', '2016-11-24 11:05:04', NULL),
(7, NULL, '/nttu/web/upload/images/slide_2.jpg', 2, 'http://2342.com', NULL, '2017-01-24 16:48:00', '2017-01-24 16:48:00', 'Cuộc thi ABC');

-- --------------------------------------------------------

--
-- Table structure for table `slider_position`
--

CREATE TABLE `slider_position` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `slider_position`
--

INSERT INTO `slider_position` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Trang chủ - Header banner', 'homepage-header-banner', '2016-11-24 11:05:04', '2016-11-24 11:05:04'),
(2, 'Trang chủ - Footer - Hình trái lớn', 'homepage-footer-left-big-banner', '2016-11-24 11:05:04', '2016-11-24 11:05:04'),
(3, 'Trang chủ - Footer - Hình phải trên', 'homepage-footer-top-right', '2016-11-24 11:05:04', '2016-11-24 11:05:04'),
(4, 'Trang chủ - Footer - Hình phải dưới', 'homepage-footer-bottom-right', '2016-11-24 11:05:04', '2016-11-24 11:05:04');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `full_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `locked` tinyint(1) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL,
  `job` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `work_place` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `salt`, `role`, `first_name`, `last_name`, `full_name`, `email`, `address`, `phone`, `deleted`, `locked`, `created_date`, `updated_date`, `job`, `work_place`, `description`) VALUES
(1, 'admin@nttu.com', '36ev21brv7o/yMcXYL6RIiv/UccW+/Cd7XMTr+bkh7jeo1XaQN42jICDSSRiOGQmhPf+LPpvd1/OaKf9sT7UhA==', '7f6c5a2786ba5d65d41d951d09ceeafc', 'ROLE_ADMIN', NULL, NULL, 'Administrator', NULL, '15/10 Thống Nhất, P11, Gò Vấp', '0984335530', 0, 0, '2017-01-15 09:25:18', '2017-01-15 09:25:18', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_64C19C1727ACA70` (`parent_id`),
  ADD KEY `search_idx` (`slug`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `search_idx` (`slug`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F08FC65CA76ED395` (`user_id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `search_idx` (`slug`);

--
-- Indexes for table `posts_categories`
--
ALTER TABLE `posts_categories`
  ADD PRIMARY KEY (`post_id`,`category_id`),
  ADD KEY `IDX_A8C3AA464B89032C` (`post_id`),
  ADD KEY `IDX_A8C3AA4612469DE2` (`category_id`);

--
-- Indexes for table `posts_tags`
--
ALTER TABLE `posts_tags`
  ADD PRIMARY KEY (`post_id`,`tag_id`),
  ADD KEY `IDX_D5ECAD9F4B89032C` (`post_id`),
  ADD KEY `IDX_D5ECAD9FBAD26311` (`tag_id`);

--
-- Indexes for table `post_category`
--
ALTER TABLE `post_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B9A19060727ACA70` (`parent_id`),
  ADD KEY `search_idx` (`slug`);

--
-- Indexes for table `post_gallery`
--
ALTER TABLE `post_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7AC3CF094B89032C` (`post_id`);

--
-- Indexes for table `post_tag`
--
ALTER TABLE `post_tag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `search_idx` (`slug`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slider_items`
--
ALTER TABLE `slider_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slider_position`
--
ALTER TABLE `slider_position`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;
--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;
--
-- AUTO_INCREMENT for table `post_category`
--
ALTER TABLE `post_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `post_gallery`
--
ALTER TABLE `post_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `post_tag`
--
ALTER TABLE `post_tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `slider_items`
--
ALTER TABLE `slider_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `slider_position`
--
ALTER TABLE `slider_position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `FK_64C19C1727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `FK_F08FC65CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts_categories`
--
ALTER TABLE `posts_categories`
  ADD CONSTRAINT `FK_A8C3AA4612469DE2` FOREIGN KEY (`category_id`) REFERENCES `post_category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_A8C3AA464B89032C` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts_tags`
--
ALTER TABLE `posts_tags`
  ADD CONSTRAINT `FK_D5ECAD9F4B89032C` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_D5ECAD9FBAD26311` FOREIGN KEY (`tag_id`) REFERENCES `post_tag` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_category`
--
ALTER TABLE `post_category`
  ADD CONSTRAINT `FK_B9A19060727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `post_category` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_gallery`
--
ALTER TABLE `post_gallery`
  ADD CONSTRAINT `FK_7AC3CF094B89032C` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
