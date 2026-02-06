-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2026 at 12:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shu_website`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `admin_id`, `action`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, NULL, 'failed_login', 'Failed login attempt for username: admin', '127.0.0.1', NULL, '2026-01-21 17:03:30'),
(2, NULL, 'failed_login', 'Failed login attempt for username: admin', '127.0.0.1', NULL, '2026-01-21 17:03:58'),
(3, NULL, 'failed_login', 'Failed login attempt for username: admin', '127.0.0.1', NULL, '2026-01-21 17:04:32'),
(4, NULL, 'failed_login', 'Failed login attempt for username: admin', '127.0.0.1', NULL, '2026-01-21 17:06:02'),
(5, NULL, 'failed_login', 'Failed login attempt for username: admin', '127.0.0.1', NULL, '2026-01-21 17:11:13'),
(6, NULL, 'failed_login', 'Failed login attempt for username: jose', '127.0.0.1', NULL, '2026-01-21 17:11:29'),
(7, NULL, 'failed_login', 'Failed login attempt for username: jose', '127.0.0.1', NULL, '2026-01-21 17:19:40'),
(8, 5, 'login', 'Admin logged in', '127.0.0.1', NULL, '2026-01-21 17:20:16'),
(9, NULL, 'failed_login', 'Failed login attempt for username: joseph@gmail.com', '127.0.0.1', NULL, '2026-01-22 05:10:49'),
(10, NULL, 'failed_login', 'Failed login attempt for username: joseph@gmail.com', '127.0.0.1', NULL, '2026-01-22 05:13:49'),
(11, NULL, 'failed_login', 'Failed login attempt for username: joseph@gmail.com', '127.0.0.1', NULL, '2026-01-22 05:15:09'),
(12, 5, 'login', 'Admin logged in', '127.0.0.1', NULL, '2026-01-22 05:15:24'),
(13, 5, 'update_post', 'Updated post: Distributing Hygiene Kits to 200 Girls in Wakiso', NULL, NULL, '2026-01-22 05:20:42'),
(14, 5, 'update_post', 'Updated post: Distributing Hygiene Kits to 200 Girls in Wakiso', NULL, NULL, '2026-01-22 05:20:47'),
(15, 5, 'update_post', 'Updated post: Women&#039;s Cooperative Achieves Food Security', NULL, NULL, '2026-01-22 05:21:21'),
(16, 5, 'update_post', 'Updated post: Community WASH Training Reaches 500 Households', NULL, NULL, '2026-01-22 05:21:39'),
(17, NULL, 'failed_login', 'Failed login attempt for username: Joseph', '127.0.0.1', NULL, '2026-01-22 08:58:35'),
(18, NULL, 'failed_login', 'Failed login attempt for username: joseph', '127.0.0.1', NULL, '2026-01-22 08:58:58'),
(19, NULL, 'failed_login', 'Failed login attempt for username: Joseph', '127.0.0.1', NULL, '2026-01-22 08:59:41'),
(20, 5, 'login', 'Admin logged in', '127.0.0.1', NULL, '2026-01-22 09:00:29'),
(21, 5, 'logout', 'Admin logged out', '127.0.0.1', NULL, '2026-01-22 11:56:28'),
(22, 5, 'login', 'Admin logged in', '127.0.0.1', NULL, '2026-01-22 11:56:44'),
(23, NULL, 'failed_login', 'Failed login attempt for username: Joetech', '127.0.0.1', NULL, '2026-02-02 09:54:27'),
(24, NULL, 'failed_login', 'Failed login attempt for username: admin', '127.0.0.1', NULL, '2026-02-02 09:55:48'),
(25, NULL, 'failed_login', 'Failed login attempt for username: admin', '127.0.0.1', NULL, '2026-02-02 09:56:15'),
(26, NULL, 'failed_login', 'Failed login attempt for username: passy', '127.0.0.1', NULL, '2026-02-02 09:57:09'),
(27, NULL, 'failed_login', 'Failed login attempt for username: admin', '127.0.0.1', NULL, '2026-02-02 09:58:34'),
(28, 5, 'login', 'Admin logged in', '127.0.0.1', NULL, '2026-02-02 09:59:24'),
(29, 5, 'login', 'Admin logged in', '127.0.0.1', NULL, '2026-02-02 18:36:14'),
(30, 5, 'volunteer_mgmt', 'Deleted volunteer application ID: 5', NULL, NULL, '2026-02-02 19:13:19'),
(31, 5, 'volunteer_mgmt', 'Changed status of volunteer ID: 4 to approved', NULL, NULL, '2026-02-02 19:13:25'),
(32, 5, 'volunteer_mgmt', 'Changed status of volunteer ID: 3 to rejected', NULL, NULL, '2026-02-02 19:13:59');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('super_admin','admin','editor') DEFAULT 'editor',
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `full_name`, `role`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@servinghearts.ug', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'super_admin', 1, NULL, '2026-01-21 16:09:46', '2026-01-21 16:09:46'),
(2, 'jose', 'jose@gmail.com', 'jose1234', 'akandwanaho', 'admin', 1, NULL, '2026-01-21 17:11:00', '2026-01-21 17:11:00'),
(5, 'joseph', 'joseph@gmail.com', '$2y$10$g53TyU9b7rfpiK/PhzNQn.hIfdQhY2DizrI8T5wSrE.pq3DLn97SC', 'Akandwanaho Joseph', 'admin', 1, '2026-02-02 21:36:13', '2026-01-21 17:19:28', '2026-02-02 18:36:13');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read','replied','archived') DEFAULT 'unread',
  `replied_by` int(11) DEFAULT NULL,
  `replied_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`, `replied_by`, `replied_at`, `created_at`) VALUES
(1, 'Peter Ssemakula', 'peter.s@email.com', NULL, 'Partnership Inquiry', 'We would like to explore partnership opportunities with SHU for our upcoming CSR program.', 'unread', NULL, NULL, '2026-01-21 16:09:46'),
(2, 'Grace Atim', 'grace.a@email.com', NULL, 'Volunteer Question', 'What are the requirements to volunteer with your organization?', 'unread', NULL, NULL, '2026-01-21 16:09:46');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `donor_name` varchar(100) NOT NULL,
  `donor_email` varchar(100) NOT NULL,
  `donor_phone` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) DEFAULT 'UGX',
  `payment_method` enum('mobile_money','bank_transfer','cash','other') DEFAULT 'mobile_money',
  `transaction_reference` varchar(100) DEFAULT NULL,
  `payment_status` enum('pending','confirmed','failed','refunded') DEFAULT 'pending',
  `is_anonymous` tinyint(1) DEFAULT 0,
  `message` text DEFAULT NULL,
  `confirmed_by` int(11) DEFAULT NULL,
  `confirmed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `donor_name`, `donor_email`, `donor_phone`, `amount`, `currency`, `payment_method`, `transaction_reference`, `payment_status`, `is_anonymous`, `message`, `confirmed_by`, `confirmed_at`, `created_at`, `updated_at`) VALUES
(1, 'John Mukasa', 'john.mukasa@email.com', '+256700123456', 100000.00, 'UGX', 'mobile_money', NULL, 'confirmed', 0, NULL, NULL, '2026-01-21 19:09:46', '2026-01-21 16:09:46', '2026-01-21 16:09:46'),
(2, 'Sarah Nambi', 'sarah.n@email.com', '+256750234567', 50000.00, 'UGX', 'mobile_money', NULL, 'confirmed', 0, NULL, NULL, '2026-01-21 19:09:46', '2026-01-21 16:09:46', '2026-01-21 16:09:46'),
(3, 'Anonymous Donor', 'anon@donor.com', '+256780345678', 200000.00, 'UGX', 'bank_transfer', NULL, 'confirmed', 0, NULL, NULL, '2026-01-21 19:09:46', '2026-01-21 16:09:46', '2026-01-21 16:09:46');

-- --------------------------------------------------------

--
-- Stand-in structure for view `donation_summary`
-- (See below for the actual view)
--
CREATE TABLE `donation_summary` (
`total_donations` bigint(21)
,`total_amount` decimal(32,2)
,`confirmed_count` bigint(21)
,`confirmed_amount` decimal(32,2)
,`pending_count` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `category` enum('office','field_work','events','community','general') DEFAULT 'general',
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery_images`
--

INSERT INTO `gallery_images` (`id`, `title`, `description`, `image_path`, `category`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'SHU Head Office', 'Our office in Kasangati Town Council, Wakiso District', 'assets/images/gallery/office-1.jpg', 'office', 1, 1, '2026-01-22 05:44:09', '2026-01-22 05:44:09'),
(2, 'Team Meeting', 'Monthly planning session with our dedicated team', 'assets/images/gallery/team-meeting.jpg', 'office', 2, 1, '2026-01-22 05:44:09', '2026-01-22 05:44:09'),
(3, 'Community Outreach', 'Distributing hygiene kits in rural communities', 'assets/images/gallery/outreach-1.jpg', 'field_work', 3, 1, '2026-01-22 05:44:09', '2026-01-22 05:44:09'),
(4, 'SHU Head Office', 'Our office in Kasangati Town Council, Wakiso District', 'assets/images/gallery/office-1.jpg', 'office', 1, 1, '2026-01-22 05:52:33', '2026-01-22 05:52:33'),
(5, 'Team Meeting', 'Monthly planning session with our dedicated team', 'assets/images/gallery/team-meeting.jpg', 'office', 2, 1, '2026-01-22 05:52:33', '2026-01-22 05:52:33'),
(6, 'Community Outreach', 'Distributing hygiene kits in rural communities', 'assets/images/gallery/outreach-1.jpg', 'field_work', 3, 1, '2026-01-22 05:52:33', '2026-01-22 05:52:33');

-- --------------------------------------------------------

--
-- Table structure for table `impact_stats`
--

CREATE TABLE `impact_stats` (
  `id` int(11) NOT NULL,
  `metric_name` varchar(100) NOT NULL,
  `metric_value` varchar(50) NOT NULL,
  `metric_description` varchar(200) DEFAULT NULL,
  `metric_category` enum('mhm','iga','wash','hiv','general') DEFAULT 'general',
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `impact_stats`
--

INSERT INTO `impact_stats` (`id`, `metric_name`, `metric_value`, `metric_description`, `metric_category`, `display_order`, `is_active`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Girls Reached', '5,000+', 'Girls supported with menstrual hygiene kits', 'mhm', 1, 1, NULL, '2026-01-21 16:09:46', '2026-01-21 16:09:46'),
(2, 'Families Supported', '300+', 'Families empowered with income-generating activities', 'iga', 2, 1, NULL, '2026-01-21 16:09:46', '2026-01-21 16:09:46'),
(3, 'Communities Served', '20+', 'Communities with improved WASH access', 'wash', 3, 1, NULL, '2026-01-21 16:09:46', '2026-01-21 16:09:46'),
(4, 'Years of Service', '10', 'Years serving Ugandan communities', 'general', 4, 1, NULL, '2026-01-21 16:09:46', '2026-01-21 16:09:46');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `status` enum('active','unsubscribed','bounced') DEFAULT 'active',
  `subscribe_ip` varchar(45) DEFAULT NULL,
  `unsubscribe_token` varchar(64) DEFAULT NULL,
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `unsubscribed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page_sections`
--

CREATE TABLE `page_sections` (
  `id` int(11) NOT NULL,
  `page_name` varchar(100) NOT NULL,
  `section_key` varchar(100) NOT NULL,
  `section_title` varchar(200) DEFAULT NULL,
  `section_content` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `page_sections`
--

INSERT INTO `page_sections` (`id`, `page_name`, `section_key`, `section_title`, `section_content`, `image_path`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'about', 'who_we_are_image', 'Who We Are Image', NULL, 'assets/images/about-team.jpg', 0, 1, '2026-01-22 05:44:09', '2026-01-22 05:44:09'),
(2, 'about', 'mission', 'Our Mission', 'To empower vulnerable communities through sustainable programs in menstrual hygiene management, income generation, water and sanitation, and health education, creating lasting positive change and promoting dignity for all.', NULL, 0, 1, '2026-01-22 05:44:09', '2026-01-22 05:44:09'),
(3, 'about', 'vision', 'Our Vision', 'A Uganda where every girl, woman, and family has access to essential resources, opportunities for sustainable livelihoods, and the dignity to live healthy, empowered lives free from poverty and stigma.', NULL, 0, 1, '2026-01-22 05:44:09', '2026-01-22 05:44:09');

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `logo_path` varchar(255) NOT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `category` enum('programs','success_story','events','news','announcement') DEFAULT 'news',
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `author_id` int(11) NOT NULL,
  `views` int(11) DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `category`, `status`, `author_id`, `views`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Distributing Hygiene Kits to 200 Girls in Wakiso', 'distributing-hygiene-kits-200-girls-wakiso', 'Our team successfully distributed menstrual hygiene kits and conducted education workshops for 200 schoolgirls across three schools.', '<p>In a landmark initiative this December, Serving Hearts-Uganda successfully distributed menstrual hygiene kits to 200 girls across three schools in Wakiso District. The program included comprehensive education workshops on menstrual health management, hygiene practices, and breaking the stigma around menstruation.</p><p>Each girl received a dignity kit containing reusable sanitary pads, soap, underwear, and educational materials. Our team also conducted training sessions with teachers and parents to create a supportive environment for girls during their menstrual cycles.</p><p>This intervention is expected to reduce school absenteeism by up to 30% and boost girls\' confidence and academic performance.</p>', 'uploads/posts/1769059242_IMG-20260122-WA0009.jpg', 'programs', 'published', 1, 0, '2026-01-22 08:20:47', '2026-01-21 16:09:46', '2026-01-22 05:20:47'),
(2, 'Women&#039;s Cooperative Achieves Food Security', 'womens-cooperative-achieves-food-security', 'A group of 25 women trained in vegetable farming and poultry keeping are now feeding their families and earning sustainable income.', '<p>Twenty-five women from Kasangati Town Council have transformed their lives through our Income Generating Activities program. After receiving three months of intensive training in vegetable farming and poultry keeping, these women formed a cooperative and launched their businesses.</p><p>Each woman received startup kits including seeds, tools, and chicks. Today, they are not only feeding their families nutritious meals but also selling surplus produce at local markets, generating an average monthly income of UGX 150,000 per household.</p><p>\"I can now pay my children\'s school fees and afford three meals a day,\" says Joyce, a 35-year-old mother of four and cooperative member.</p>', 'uploads/posts/1769059281_IMG-20260122-WA0013.jpg', 'success_story', 'published', 1, 0, '2026-01-22 08:21:21', '2026-01-21 16:09:46', '2026-01-22 05:21:21'),
(3, 'Community WASH Training Reaches 500 Households', 'community-wash-training-reaches-500-households', 'Our recent water, sanitation, and hygiene training program educated 500 households on proper handwashing and water treatment.', '<p>Serving Hearts-Uganda concluded a successful WASH training program that reached over 500 households in rural communities. The program focused on critical hygiene behaviors including proper handwashing techniques, safe water storage, and household sanitation.</p><p>Community Health Workers were trained as WASH champions to continue education efforts. Each participating household received handwashing stations and water purification tablets.</p><p>Follow-up surveys show a 65% improvement in handwashing practices and a 40% reduction in waterborne diseases in participating communities.</p>', 'uploads/posts/1769059299_IMG-20260122-WA0016.jpg', 'events', 'published', 1, 0, '2026-01-22 08:21:39', '2026-01-21 16:09:46', '2026-01-22 05:21:39');

-- --------------------------------------------------------

--
-- Stand-in structure for view `recent_posts`
-- (See below for the actual view)
--
CREATE TABLE `recent_posts` (
`id` int(11)
,`title` varchar(200)
,`slug` varchar(220)
,`excerpt` text
,`featured_image` varchar(255)
,`category` enum('programs','success_story','events','news','announcement')
,`published_at` datetime
,`views` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','number','boolean','json') DEFAULT 'text',
  `description` varchar(255) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `updated_by`, `updated_at`) VALUES
(1, 'site_name', 'Serving Hearts-Uganda Limited', 'text', 'Organization name', NULL, '2026-01-21 16:09:46'),
(2, 'site_email', 'info@servinghearts.ug', 'text', 'Primary contact email', NULL, '2026-01-21 16:09:46'),
(3, 'site_phone', '+256 XXX XXX XXX', 'text', 'Primary contact phone', NULL, '2026-01-21 16:09:46'),
(4, 'site_address', 'Wakiso District, Kasangati Town Council', 'text', 'Physical address', NULL, '2026-01-21 16:09:46'),
(5, 'facebook_url', '#', 'text', 'Facebook page URL', NULL, '2026-01-21 16:09:46'),
(6, 'twitter_url', '#', 'text', 'Twitter profile URL', NULL, '2026-01-21 16:09:46'),
(7, 'instagram_url', '#', 'text', 'Instagram profile URL', NULL, '2026-01-21 16:09:46'),
(8, 'linkedin_url', '#', 'text', 'LinkedIn profile URL', NULL, '2026-01-21 16:09:46'),
(9, 'mobile_money_number', '0700000000', 'text', 'Mobile Money donation number', NULL, '2026-01-21 16:09:46'),
(10, 'mobile_money_name', 'Serving Hearts Uganda', 'text', 'Mobile Money account name', NULL, '2026-01-21 16:09:46'),
(11, 'donation_enabled', 'true', 'boolean', 'Enable/disable donations', NULL, '2026-01-21 16:09:46'),
(12, 'volunteer_enabled', 'true', 'boolean', 'Enable/disable volunteer applications', NULL, '2026-01-21 16:09:46'),
(13, 'maintenance_mode', 'false', 'boolean', 'Enable/disable maintenance mode', NULL, '2026-01-21 16:09:46');

-- --------------------------------------------------------

--
-- Table structure for table `slider_images`
--

CREATE TABLE `slider_images` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `slider_images`
--

INSERT INTO `slider_images` (`id`, `title`, `description`, `image_path`, `button_text`, `button_link`, `display_order`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Empowering Communities, Transforming Lives', 'Supporting girls, women, and communities across Uganda through sustainable development programs', 'assets/images/slider/slide-1769063385.jpg', 'Learn More', 'about.php', 1, 1, NULL, '2026-01-22 05:44:09', '2026-01-22 06:29:45'),
(2, 'Breaking Period Poverty Barriers', 'Providing dignity and education to over 5,000 girls through menstrual hygiene management', 'assets/images/slider/slide-1769063444.jpg', 'Our Programs', 'programs.php', 2, 1, NULL, '2026-01-22 05:44:09', '2026-01-22 06:30:44'),
(3, 'Building Sustainable Livelihoods', 'Empowering 300+ families with income-generating activities for food security', 'assets/images/slider/slide-3.jpg', 'Get Involved', 'get-involved.php', 3, 1, NULL, '2026-01-22 05:44:09', '2026-01-22 05:44:09'),
(4, 'Empowering Communities, Transforming Lives', 'Supporting girls, women, and communities across Uganda through sustainable development programs', 'assets/images/slider/slide-1769063407.jpg', 'Learn More', 'about.php', 1, 1, NULL, '2026-01-22 05:46:47', '2026-01-22 06:30:07'),
(5, 'Breaking Period Poverty Barriers', 'Providing dignity and education to over 5,000 girls through menstrual hygiene management', 'assets/images/slider/slide-2.jpg', 'Our Programs', 'programs.php', 2, 1, NULL, '2026-01-22 05:46:47', '2026-01-22 05:46:47'),
(6, 'Building Sustainable Livelihoods', 'Empowering 300+ families with income-generating activities for food security', 'assets/images/slider/slide-3.jpg', 'Get Involved', 'get-involved.php', 3, 1, NULL, '2026-01-22 05:46:47', '2026-01-22 05:46:47');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `full_name`, `position`, `bio`, `photo_path`, `email`, `phone`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Executive Director', 'Founder & CEO', 'Leading SHU with passion and dedication to community empowerment since 2015.', 'assets/images/team/member-1.jpg', NULL, NULL, 1, 1, '2026-01-22 05:44:09', '2026-01-22 05:44:09'),
(2, 'Programs Manager', 'Head of Programs', 'Overseeing all community programs and ensuring quality implementation.', 'assets/images/team/member-2.jpg', NULL, NULL, 2, 1, '2026-01-22 05:44:09', '2026-01-22 05:44:09'),
(3, 'Finance Officer', 'Finance & Administration', 'Managing financial operations and ensuring transparency in all transactions.', 'assets/images/team/member-3.jpg', NULL, NULL, 3, 1, '2026-01-22 05:44:09', '2026-01-22 05:44:09'),
(4, 'Executive Director', 'Founder & CEO', 'Leading SHU with passion and dedication to community empowerment since 2015.', 'assets/images/team/member-1.jpg', NULL, NULL, 1, 1, '2026-01-22 05:48:51', '2026-01-22 05:48:51'),
(5, 'Programs Manager', 'Head of Programs', 'Overseeing all community programs and ensuring quality implementation.', 'assets/images/team/member-2.jpg', NULL, NULL, 2, 1, '2026-01-22 05:48:51', '2026-01-22 05:48:51'),
(6, 'Finance Officer', 'Finance & Administration', 'Managing financial operations and ensuring transparency in all transactions.', 'assets/images/team/member-3.jpg', NULL, NULL, 3, 1, '2026-01-22 05:48:51', '2026-01-22 05:48:51');

-- --------------------------------------------------------

--
-- Table structure for table `volunteers`
--

CREATE TABLE `volunteers` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other','prefer_not_to_say') DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `education_level` varchar(100) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `interests` text DEFAULT NULL,
  `availability` enum('weekdays','weekends','both','flexible') DEFAULT 'flexible',
  `hours_per_week` varchar(50) DEFAULT NULL,
  `motivation` text NOT NULL,
  `previous_experience` text DEFAULT NULL,
  `application_status` enum('pending','approved','rejected','inactive') DEFAULT 'pending',
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `volunteers`
--

INSERT INTO `volunteers` (`id`, `full_name`, `email`, `phone`, `date_of_birth`, `gender`, `location`, `education_level`, `skills`, `interests`, `availability`, `hours_per_week`, `motivation`, `previous_experience`, `application_status`, `reviewed_by`, `reviewed_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Alice Nakato', 'alice.n@email.com', '+256701111111', NULL, NULL, NULL, NULL, 'Teaching, Community Mobilization', 'Girls Education, MHM Programs', 'flexible', NULL, 'I want to give back to my community and empower young girls.', NULL, 'approved', NULL, NULL, NULL, '2026-01-21 16:09:46', '2026-01-21 16:09:46'),
(2, 'David Okello', 'david.o@email.com', '+256702222222', NULL, NULL, NULL, NULL, 'Web Development, Graphic Design', 'IT Support, Marketing', 'flexible', NULL, 'I can offer my tech skills to help SHU reach more people online.', NULL, 'pending', NULL, NULL, NULL, '2026-01-21 16:09:46', '2026-01-21 16:09:46'),
(3, 'Akandwanaho Joseph', 'jakandwanaho2@gmail.com', '', NULL, 'other', NULL, NULL, 'health worker', NULL, 'flexible', NULL, 'i want t collaborate', NULL, 'rejected', 5, '2026-02-02 22:13:59', NULL, '2026-01-28 08:51:07', '2026-02-02 19:13:59'),
(4, 'Akunda Joan', 'joan@gmail.com', '', NULL, 'other', NULL, NULL, 'ict', NULL, 'flexible', NULL, 'to collaboarate', NULL, 'approved', 5, '2026-02-02 22:13:25', NULL, '2026-01-28 08:51:48', '2026-02-02 19:13:25'),
(6, 'Akunda Joan', 'joan@gmail.com', '0756884382', NULL, 'other', NULL, NULL, 'ict', NULL, 'flexible', NULL, 'to collaboarate', NULL, 'pending', NULL, NULL, NULL, '2026-01-28 09:07:45', '2026-01-28 09:07:45');

-- --------------------------------------------------------

--
-- Stand-in structure for view `volunteer_summary`
-- (See below for the actual view)
--
CREATE TABLE `volunteer_summary` (
`total_applications` bigint(21)
,`pending_count` bigint(21)
,`approved_count` bigint(21)
,`rejected_count` bigint(21)
);

-- --------------------------------------------------------

--
-- Structure for view `donation_summary`
--
DROP TABLE IF EXISTS `donation_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `donation_summary`  AS SELECT count(0) AS `total_donations`, sum(`donations`.`amount`) AS `total_amount`, count(case when `donations`.`payment_status` = 'confirmed' then 1 end) AS `confirmed_count`, sum(case when `donations`.`payment_status` = 'confirmed' then `donations`.`amount` else 0 end) AS `confirmed_amount`, count(case when `donations`.`payment_status` = 'pending' then 1 end) AS `pending_count` FROM `donations` ;

-- --------------------------------------------------------

--
-- Structure for view `recent_posts`
--
DROP TABLE IF EXISTS `recent_posts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `recent_posts`  AS SELECT `posts`.`id` AS `id`, `posts`.`title` AS `title`, `posts`.`slug` AS `slug`, `posts`.`excerpt` AS `excerpt`, `posts`.`featured_image` AS `featured_image`, `posts`.`category` AS `category`, `posts`.`published_at` AS `published_at`, `posts`.`views` AS `views` FROM `posts` WHERE `posts`.`status` = 'published' ORDER BY `posts`.`published_at` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `volunteer_summary`
--
DROP TABLE IF EXISTS `volunteer_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `volunteer_summary`  AS SELECT count(0) AS `total_applications`, count(case when `volunteers`.`application_status` = 'pending' then 1 end) AS `pending_count`, count(case when `volunteers`.`application_status` = 'approved' then 1 end) AS `approved_count`, count(case when `volunteers`.`application_status` = 'rejected' then 1 end) AS `rejected_count` FROM `volunteers` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin_id` (`admin_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `replied_by` (`replied_by`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `confirmed_by` (`confirmed_by`),
  ADD KEY `idx_donor_email` (`donor_email`),
  ADD KEY `idx_payment_status` (`payment_status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `impact_stats`
--
ALTER TABLE `impact_stats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_category` (`metric_category`),
  ADD KEY `idx_display_order` (`display_order`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `unsubscribe_token` (`unsubscribe_token`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `page_sections`
--
ALTER TABLE `page_sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_page_section` (`page_name`,`section_key`),
  ADD KEY `idx_page` (`page_name`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_display_order` (`display_order`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_published_at` (`published_at`);
ALTER TABLE `posts` ADD FULLTEXT KEY `idx_search` (`title`,`excerpt`,`content`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_setting_key` (`setting_key`);

--
-- Indexes for table `slider_images`
--
ALTER TABLE `slider_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_display_order` (`display_order`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_display_order` (`display_order`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `volunteers`
--
ALTER TABLE `volunteers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviewed_by` (`reviewed_by`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_application_status` (`application_status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `impact_stats`
--
ALTER TABLE `impact_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `page_sections`
--
ALTER TABLE `page_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `slider_images`
--
ALTER TABLE `slider_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `volunteers`
--
ALTER TABLE `volunteers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `contact_messages_ibfk_1` FOREIGN KEY (`replied_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`confirmed_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `impact_stats`
--
ALTER TABLE `impact_stats`
  ADD CONSTRAINT `impact_stats_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD CONSTRAINT `site_settings_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `slider_images`
--
ALTER TABLE `slider_images`
  ADD CONSTRAINT `slider_images_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `volunteers`
--
ALTER TABLE `volunteers`
  ADD CONSTRAINT `volunteers_ibfk_1` FOREIGN KEY (`reviewed_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
