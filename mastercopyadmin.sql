-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2024 at 12:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mastercopyadmin`
--

-- --------------------------------------------------------

--
-- Table structure for table `cashfree_config`
--

CREATE TABLE `cashfree_config` (
  `id` int(11) NOT NULL,
  `app_id` varchar(255) NOT NULL,
  `secret_key` varchar(255) NOT NULL,
  `environment` enum('production','test') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cashfree_config`
--

INSERT INTO `cashfree_config` (`id`, `app_id`, `secret_key`, `environment`) VALUES
(1, '786358334c5019980c2492445d853687', 'cfsk_ma_prod_9d0bd533560cbcfad76d9bd81e41c126_8a93c584', 'production'),
(2, 'TEST10335909554c120aa78be2ef598290953301', 'cfsk_ma_test_84c977a036f03380442c162349b31bf5_606583f6', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `cashfree_payment`
--

CREATE TABLE `cashfree_payment` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `order_amount` decimal(10,2) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `cf_order_id` varchar(255) NOT NULL,
  `payment_session_id` text NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `payment_time` datetime DEFAULT NULL,
  `callback_response` text DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cashfree_payment`
--

INSERT INTO `cashfree_payment` (`id`, `product_id`, `order_id`, `order_amount`, `customer_id`, `customer_name`, `customer_email`, `customer_phone`, `cf_order_id`, `payment_session_id`, `payment_status`, `payment_time`, `callback_response`, `created_on`) VALUES
(27, 8, 'ORD_6739524236758', 250.00, '6739524236774', 'user', 'user@gmail.com', '9170982808', '3496576449', 'session_HxyGOR-rxyQIJLU2pL9atgp7sp1l1hp_P4_cdQKju87EPZxrAqByJzOEbAO8SPRD1jXp8AdsyshbWPUVKyf6ldiHYM9unxofrLn2fTHEq5YH', 'pending', NULL, NULL, '2024-11-17 02:17:38'),
(28, 8, 'ORD_67395b628ca5c', 0.00, '', '', '', '', '', '', 'SUCCESS', '2024-11-17 08:26:38', NULL, '2024-11-17 02:57:02'),
(29, 8, 'ORD_67395c4067071', 1.00, '67395c406707d', 'user', 'user@gmail.com', '9170982808', '3496653167', 'session_2JH_KRa-FvMorhm6bqvhpDI6wjvXX7tgXMTzY8HSeTYrt11XiIUSTFsntEuwVsmIAtCd2vlv8D9SzFueAcG0FdMc0VXEhyc4b-KTtTKPvIdp', 'pending', NULL, NULL, '2024-11-17 03:00:16'),
(30, 8, 'ORD_67395d60daf52', 5.00, '67395d60daf5f', 'user', 'user@gmail.com', '9170982808', '3496661936', 'session_U38c1wnpRTxzN1tUg3HBpcGZRIvdoE5Glz-RBXYFcZR_gjsM2NxIpCMIiU1Ue0DFAoF8sC3wOxfRDh_-b6AG_XPmojIyA-VPaLvqwkH4F3II', 'pending', NULL, NULL, '2024-11-17 03:05:05');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','deleted') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `comments`
--
DELIMITER $$
CREATE TRIGGER `after_comment_delete` AFTER DELETE ON `comments` FOR EACH ROW BEGIN
    UPDATE posts SET comments = comments - 1 WHERE id = OLD.post_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_comment_insert` AFTER INSERT ON `comments` FOR EACH ROW BEGIN
    UPDATE posts SET comments = comments + 1 WHERE id = NEW.post_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

CREATE TABLE `contact_submissions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `project` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` datetime DEFAULT current_timestamp(),
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `footer_content`
--

CREATE TABLE `footer_content` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `copyright_text` text DEFAULT NULL,
  `designer_name` varchar(255) NOT NULL,
  `designer_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `footer_content`
--

INSERT INTO `footer_content` (`id`, `company_name`, `description`, `address`, `phone`, `email`, `copyright_text`, `designer_name`, `designer_url`) VALUES
(1, 'Online Growth Hub', 'Empowering Businesses Through Design and Strategy', 'Madhuban Marg, Belthararoad', '9032666855', 'onlinegrowthhub@gmail.com', '© 2024 Online Growth Hub. All rights reserved.', 'Online Growth Hub', 'https://onlinegrowthhub.in'),
(2, 'Online Growth Hub', 'Empowering Businesses Through Design and Strategy', 'Madhuban Marg, Belthararoad', '9032666855', 'onlinegrowthhub@gmail.com', '© 2024 Online Growth Hub. All rights reserved.', 'Online Growth Hub', 'https://onlinegrowthhub.in'),
(3, 'Online Growth Hub', 'Empowering Businesses Through Design and Strategy', 'Madhuban Marg, Belthararoad', '9032666855', 'onlinegrowthhub@gmail.com', '© 2024 Online Growth Hub. All rights reserved.', 'Online Growth Hub', 'https://onlinegrowthhub.in'),
(4, 'Online Growth Hub', 'Empowering Businesses Through Design and Strategy', 'Madhuban Marg, Belthararoad', '9032666855', 'onlinegrowthhub@gmail.com', '© 2024 Online Growth Hub. All rights reserved.', 'Online Growth Hub', 'https://onlinegrowthhub.in'),
(5, 'Online Growth Hub', 'Empowering Businesses Through Design and Strategy', 'Madhuban Marg, Belthararoad', '9032666855', 'onlinegrowthhub@gmail.com', '© 2024 Online Growth Hub. All rights reserved.', 'Online Growth Hub', 'https://onlinegrowthhub.in');

-- --------------------------------------------------------

--
-- Table structure for table `footer_links`
--

CREATE TABLE `footer_links` (
  `id` int(11) NOT NULL,
  `link_text` varchar(255) NOT NULL,
  `link_url` varchar(255) NOT NULL,
  `link_type` enum('external','internal') NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `icon_class` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `footer_links`
--

INSERT INTO `footer_links` (`id`, `link_text`, `link_url`, `link_type`, `display_order`, `icon_class`) VALUES
(1, 'About us', 'about.php', 'internal', 1, 'fas fa-info-circle'),
(2, 'Contact us', 'contact.php', 'internal', 2, 'fas fa-envelope'),
(3, 'Our Services', 'service.php', 'internal', 3, 'fas fa-cogs'),
(4, 'Our Projects', 'project.php', 'internal', 4, 'fas fa-briefcase'),
(5, 'Latest Blog', 'blog.php', 'internal', 5, 'fas fa-blog');

-- --------------------------------------------------------

--
-- Table structure for table `help_links`
--

CREATE TABLE `help_links` (
  `id` int(11) NOT NULL,
  `link_text` varchar(255) NOT NULL,
  `link_url` varchar(255) NOT NULL,
  `display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `help_links`
--

INSERT INTO `help_links` (`id`, `link_text`, `link_url`, `display_order`) VALUES
(1, 'Terms Of Use', 'terms.php', 1),
(2, 'Privacy Policy', 'privacy.php', 2),
(3, 'Help', 'help.php', 3),
(4, 'FAQs', 'faq.php', 4),
(8, 'Refund Policy', 'refund-cancellation-policy.php', 2);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `invoice_number` varchar(20) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `client_address` text NOT NULL,
  `client_contact` varchar(20) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `item_price` decimal(10,2) NOT NULL,
  `item_quantity` int(11) NOT NULL,
  `item_subtotal` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `payment_instructions` text DEFAULT NULL,
  `terms_conditions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `invoice_date`, `due_date`, `client_name`, `client_address`, `client_contact`, `item_name`, `item_price`, `item_quantity`, `item_subtotal`, `total_amount`, `tax`, `payment_instructions`, `terms_conditions`) VALUES
(1, 'INV0001', '2024-10-10', '2024-11-10', 'Institute of Management and Research, Ghaziabad', '8th Mile Stone, Delhi-Meerut Road, Duhai, Ghaziabad, 75030 22011, 75030 45604', '75030 22011, 75030 4', 'Social Media Post', 0.00, 15, 0.00, 5000.00, 0.00, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `short_description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `category_slug` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `shares` int(11) DEFAULT 0,
  `comments` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('draft','published') DEFAULT 'draft',
  `views` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `live_preview_url` varchar(255) DEFAULT NULL,
  `download_file` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `image`, `description`, `live_preview_url`, `download_file`, `price`, `status`, `created`) VALUES
(8, 'Convert Your Website into an Android App | Get AIA File Free', 'convert.jpeg', 'Transform your website into an engaging Android app effortlessly! With this AIA file, you can quickly convert your site into a captivating mobile experience using Kodular. Your app will include a customized splash screen, external link sharing capabilities, and a streamlined design that reflects your brand’s identity. This file provides you with a head start, saving hours of coding and setup. Perfect for anyone looking to boost user engagement, reach a broader audience, and offer a seamless mobile experience. No advanced coding skills required!', 'https://www.youtube.com/watch?v=a_ws_NvA77I', 'https://drive.google.com/file/d/1b4eHDriM7n_1ZfAtKaBn_GcZwe3w3j6_/view?usp=drive_link', 250.00, 1, '2024-11-03 19:21:16'),
(11, 'Custom Certificate Generator', '67395ae41894e_1731812068.jpg', 'This Custom Certificate Generator is a versatile tool that allows users to easily create professional certificates for any occasion. Whether you\'re creating certificates for events, workshops, awards, or recognition programs, this tool offers a simple and effective solution. It supports Hindi fonts (like Noto Serif Devanagari) to ensure your certificates cater to diverse audiences.\r\n\r\nKey Features:\r\n\r\nSupports Hindi Fonts for creating multilingual certificates.\r\nCustomizable Templates for various use cases (events, awards, courses, etc.).\r\nEasy-to-Use Interface to design and personalize certificates in minutes.\r\nCSS Styling for a polished and professional look.\r\nPDF Download functionality using FileSaver.js.\r\nAdd Logos and Images to make each certificate unique and branded.\r\nPerfect for businesses, educational institutions, event organizers, and anyone who needs to generate custom certificates quickly and efficiently.', 'http://certificate-generator.onlinegrowthhub.in/', 'https://drive.google.com/file/d/1BK_HuFrWYKNG21daAi_GeLvAEtAyZiDu/view?usp=drive_link', 850.00, 1, '2024-11-16 15:44:02');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `site_title` varchar(255) NOT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `site_icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_title`, `keywords`, `description`, `site_icon`, `created_at`, `updated_at`) VALUES
(1, 'Online Growth Hub: Empowering Businesses Through Design and Strategy', 'Website Design, Graphic Design, Digital Marketing, SEO, SEM, SMM', 'Welcome to Online Growth Hub! I’m Dheeraj, a passionate Website and Graphic Designer from Ballia, Uttar Pradesh. I specialize in helping businesses create powerful online presences with a unique blend of creativity and technical know-how. Here’s how I can help you take your digital presence to the next level: Web Design & Development Services I create custom websites that not only look stunning but are also highly functional and responsive. With a deep understanding of both design and backend development, I ensure each site is optimized for performance, security, and scalability. My goal is to build websites that deliver exceptional user experiences and drive tangible results. Whether you\'re looking to build a new website or revamp an existing one, I offer tailored solutions that suit your needs and budget. Digital Marketing Services In today’s digital world, an effective marketing strategy is essential for growth. I provide comprehensive Digital Marketing services, including Social Media Marketing (SMM) and Search Engine Optimization (SEO), to boost your online visibility and attract the right audience. From content creation to analytics-driven strategies, I help you establish a strong and consistent presence on various digital platforms, making sure your business stays ahead of the competition. Graphic Design Services Visual identity is crucial in making a lasting impression. I offer a full range of Graphic Design services to help your brand communicate its message with clarity and style. From logos and branding to marketing materials, my designs are crafted to reflect your brand’s essence and captivate your target audience. Using the latest design techniques, I bring together colors, typography, and imagery to create visually compelling graphics that resonate with your customers. SEO & SEM Services Search engines are a major source of traffic and leads, and I’m here to help you tap into that potential. I offer Search Engine Optimization (SEO) and Search Engine Marketing (SEM) services that include everything from keyword research and on-page optimization to pay-per-click campaigns. My approach combines strategic planning with technical insights to improve your search engine rankings, enhance your online visibility, and drive quality traffic to your website. Social Media Optimization & Management Social Media is an essential part of any business\'s digital strategy. With my Social Media Optimization (SMO) and Social Media Marketing (SMM) services, I help you connect with your audience on a personal level, building brand loyalty and engagement. I manage content creation, scheduling, and audience interaction, ensuring that your brand stays relevant and active across platforms like Facebook, Instagram, Twitter, and LinkedIn. At Online Growth Hub, I’m dedicated to delivering top-notch digital solutions that help businesses succeed. If you\'re ready to start building a strong online presence, let’s talk! I’m here to provide personalized service and expert guidance every step of the way. Feel free to reach out at 📞 9032666855 or 📧 onlinegrowthhub@gmail.com, or visit my website at onlinegrowthhub.in to learn more about how I can help you grow your business online.', 'logo.png', '2024-10-19 12:38:19', '2024-10-19 12:38:19');

-- --------------------------------------------------------

--
-- Table structure for table `topbar`
--

CREATE TABLE `topbar` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `facebook_link` varchar(255) DEFAULT NULL,
  `twitter_link` varchar(255) DEFAULT NULL,
  `linkedin_link` varchar(255) DEFAULT NULL,
  `instagram_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `topbar`
--

INSERT INTO `topbar` (`id`, `company_name`, `address`, `email`, `facebook_link`, `twitter_link`, `linkedin_link`, `instagram_link`) VALUES
(1, 'Online Growth Hub', 'Madhuban Rd, Belthara, Uttar Pradesh 221715', 'info@onlinegrowthhub.in', 'https://www.facebook.com/onlinegrowthhub', 'https://twitter.com/onlinegrowthhub', 'https://www.linkedin.com/company/onlinegrowthhub', 'https://www.instagram.com/onlinegrowthhub');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `mobile`, `photo`, `password`, `token`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Dheeraj Singh', '', 'admin@gmail.com', '', '1729346516.png', '$2y$10$s4wQlHBmqNUwtzrZSVM5G.ufhytg6NP2kp8wrhAPKFj7NrWWYc4ji', NULL, 'admin', 'active', '2024-10-16 10:53:11', '2024-10-31 17:13:22'),
(2, 'user', 'Dheeraj Singh', 'user@gmail.com', '9170982808', 'user_2_1730394905.jpg', '$2y$10$7SnY.rEbFUJ9lZ/opydXXOS173QtnGJeRo.j.qVHT.M5sTr//pqDK', 'e0abb9d336e1044af5273da587aa68ca59c4534826e0084052d965ed9c47892f977d16e739c26a56ae8361da4a00daa13f2b', 'user', 'active', '2024-10-31 17:13:26', '2024-10-31 17:15:05'),
(3, 'fnfOzvSR', 'fnfOzvSR', 'testing@example.com', '987-65-4329', 'default_photo.jpg', '$2y$10$5A.0.eBBcNCN4pu60FTxg.PNJS9/PJGwsXT0O8poLtCU/R35s/1y6', 'ae67c35e926613debc8d347f0ce784dc8094dec8f1f4c681e3cad029be977bc53d2734cb62f43db6bd984a51c00f036693b8', 'user', 'inactive', '2024-11-15 15:47:11', '2024-11-15 15:47:11');

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_settings`
--

CREATE TABLE `whatsapp_settings` (
  `id` int(11) NOT NULL,
  `whatsapp_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `whatsapp_settings`
--

INSERT INTO `whatsapp_settings` (`id`, `whatsapp_number`) VALUES
(1, '9032666855');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cashfree_config`
--
ALTER TABLE `cashfree_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cashfree_payment`
--
ALTER TABLE `cashfree_payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer_content`
--
ALTER TABLE `footer_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer_links`
--
ALTER TABLE `footer_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_links`
--
ALTER TABLE `help_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `topbar`
--
ALTER TABLE `topbar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `whatsapp_settings`
--
ALTER TABLE `whatsapp_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cashfree_config`
--
ALTER TABLE `cashfree_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cashfree_payment`
--
ALTER TABLE `cashfree_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=312;

--
-- AUTO_INCREMENT for table `footer_content`
--
ALTER TABLE `footer_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `footer_links`
--
ALTER TABLE `footer_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `help_links`
--
ALTER TABLE `help_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `topbar`
--
ALTER TABLE `topbar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `whatsapp_settings`
--
ALTER TABLE `whatsapp_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cashfree_payment`
--
ALTER TABLE `cashfree_payment`
  ADD CONSTRAINT `cashfree_payment_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
