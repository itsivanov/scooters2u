-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 08 2014 г., 13:50
-- Версия сервера: 10.1.1-MariaDB-1~precise-wsrep-log
-- Версия PHP: 5.5.9-1ubuntu4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `katclaims`
--

-- --------------------------------------------------------

--
-- Структура таблицы `vt_categories`
--

CREATE TABLE IF NOT EXISTS `vt_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `lft` int(20) DEFAULT NULL,
  `rght` int(20) DEFAULT NULL,
  `parent_id` int(20) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `vt_categories`
--

INSERT INTO `vt_categories` (`id`, `title`, `cover`, `description`, `lft`, `rght`, `parent_id`, `created`) VALUES
(1, 'Personal', '/uploads/slide3.jpg', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable you are going to use.', 1, 2, NULL, '2014-11-25 20:02:48'),
(2, 'Commercial', '/uploads/slide1.jpg', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable you are going to use.', 3, 4, NULL, '2014-11-25 20:03:23');

-- --------------------------------------------------------

--
-- Структура таблицы `vt_claimants_informations`
--

CREATE TABLE IF NOT EXISTS `vt_claimants_informations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `claims_requests_id` int(11) NOT NULL,
  `claimant` varchar(255) NOT NULL,
  `phone_1` varchar(50) DEFAULT NULL,
  `phone_2` varchar(50) DEFAULT NULL,
  `phone_3` varchar(50) DEFAULT NULL,
  `city_state_zip` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `vt_claims`
--

CREATE TABLE IF NOT EXISTS `vt_claims` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `main_service_id` int(11) NOT NULL,
  `parent_service_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `party_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `link` text,
  `lft` int(20) DEFAULT NULL,
  `rght` int(20) DEFAULT NULL,
  `parent_id` int(20) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `vt_claims`
--

INSERT INTO `vt_claims` (`id`, `main_service_id`, `parent_service_id`, `category_id`, `party_id`, `title`, `cover`, `description`, `name`, `link`, `lft`, `rght`, `parent_id`, `created`) VALUES
(1, 1, 4, 1, 1, 'Claim 3', '/uploads/slide2.jpg', '', NULL, NULL, 1, 2, NULL, '2014-12-08 11:49:56'),
(2, 1, 4, 1, 1, 'Claim 2', '/uploads/slide2.jpg', '', NULL, NULL, 3, 4, NULL, '2014-12-08 11:50:14');

-- --------------------------------------------------------

--
-- Структура таблицы `vt_claims_requests`
--

CREATE TABLE IF NOT EXISTS `vt_claims_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `viewed` smallint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `vt_cliens_informations`
--

CREATE TABLE IF NOT EXISTS `vt_cliens_informations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `claims_requests` int(11) NOT NULL,
  `company_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `adjuster_email` varchar(255) NOT NULL,
  `adjuster_name` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city_state_zip` varchar(255) DEFAULT NULL,
  `extension` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `vt_comments`
--

CREATE TABLE IF NOT EXISTS `vt_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `posts_id` int(11) NOT NULL,
  `parent_id` tinyint(4) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `active` tinyint(4) DEFAULT '0',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `vt_contacts`
--

CREATE TABLE IF NOT EXISTS `vt_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `time` time NOT NULL,
  `state` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `comment` text NOT NULL,
  `type` varchar(15) CHARACTER SET utf8 NOT NULL,
  `viewed` smallint(1) NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `vt_contacts`
--

INSERT INTO `vt_contacts` (`id`, `name`, `email`, `phone`, `time`, `state`, `address`, `city`, `subject`, `comment`, `type`, `viewed`, `data`, `created`) VALUES
(1, 'alex', 'alex@gmail.com', '', '00:00:00', '', NULL, NULL, 'test', 'test', '', 1, '', '2014-11-27 12:51:10'),
(2, 'alex', 'alex@gmail.com', '', '00:00:00', '', NULL, NULL, 'test', 'test', '', 1, '', '2014-11-27 12:51:25');

-- --------------------------------------------------------

--
-- Структура таблицы `vt_coverages_informations`
--

CREATE TABLE IF NOT EXISTS `vt_coverages_informations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `claims_requests` int(11) NOT NULL,
  `date_of_loss` datetime NOT NULL,
  `policy_nbr` varchar(255) NOT NULL,
  `type_of_policy` varchar(255) NOT NULL,
  `type_of_policy2` varchar(255) NOT NULL,
  `effective_dates_start` datetime NOT NULL,
  `effective_dates_end` double NOT NULL,
  `other` varchar(255) DEFAULT NULL,
  `coverage_amounts_a` float NOT NULL,
  `coverage_amounts_b` float NOT NULL,
  `coverage_amounts_c` float NOT NULL,
  `coverage_amounts_d` float NOT NULL,
  `deductible` varchar(255) NOT NULL,
  `lien_holder` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `vt_faqs`
--

CREATE TABLE IF NOT EXISTS `vt_faqs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `question` text,
  `answer` text,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `vt_groups`
--

CREATE TABLE IF NOT EXISTS `vt_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `headline` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT '0',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `vt_groups`
--

INSERT INTO `vt_groups` (`id`, `name`, `headline`, `order`, `created`) VALUES
(1, 'admin', 'Administrators', 0, '2013-08-23 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `vt_insureds_informations`
--

CREATE TABLE IF NOT EXISTS `vt_insureds_informations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `claims_requests` int(11) NOT NULL,
  `insured` varchar(255) NOT NULL,
  `phone_1` varchar(50) DEFAULT NULL,
  `phone_2` varchar(50) DEFAULT NULL,
  `phone_3` varchar(50) DEFAULT NULL,
  `city_state_zip` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `vt_losses_informations`
--

CREATE TABLE IF NOT EXISTS `vt_losses_informations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `claims_requests` int(11) NOT NULL,
  `claimant` datetime NOT NULL,
  `loss_location` varchar(255) NOT NULL,
  `type_of_invest1` varchar(255) NOT NULL,
  `type_of_invest2` varchar(255) NOT NULL,
  `property_causalty` varchar(255) NOT NULL,
  `example` varchar(255) NOT NULL,
  `description_of_loss` varchar(255) NOT NULL,
  `special_unstruction` varchar(255) NOT NULL,
  `attachments` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `vt_menus`
--

CREATE TABLE IF NOT EXISTS `vt_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(20) DEFAULT NULL,
  `lft` int(20) DEFAULT NULL,
  `rght` int(20) DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `site_menu_id` int(20) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ;

--
-- Дамп данных таблицы `vt_menus`
--

INSERT INTO `vt_menus` (`id`, `parent_id`, `lft`, `rght`, `name`, `url`, `site_menu_id`, `created`, `modified`, `active`) VALUES
(27, NULL, 1, 2, 'buy', 'buy.html', 2, '2013-11-28 13:18:23', '2014-03-13 14:05:03', 1),
(49, NULL, 1, 2, 'home', '/', 1, '2014-03-03 19:31:43', '2014-03-04 16:38:20', 1),
(50, NULL, 3, 4, 'services', '/services.html', 1, '2014-03-04 16:33:34', '2014-11-26 18:48:33', 1),
(51, NULL, 5, 6, 'about us', '/about.html', 1, '2014-03-04 16:33:53', '2014-11-26 18:48:57', 1),
(52, NULL, 3, 4, 'contact', '/contact.html', 2, '2014-03-04 16:34:42', '2014-03-13 14:05:13', 1),
(53, NULL, 5, 6, 'faq', '/faq.html', 2, '2014-03-04 16:34:54', '2014-03-13 14:05:19', 1),
(54, NULL, 7, 8, 'Assigments', '/assigments.html', 1, '2014-11-26 18:49:54', '2014-11-26 18:50:18', 1),
(55, NULL, 9, 10, 'news', '/news.html', 1, '2014-11-26 18:50:10', '2014-11-26 18:50:10', 1),
(56, NULL, 11, 12, 'Contact us', '/contact.html', 1, '2014-11-26 18:50:37', '2014-11-26 18:50:37', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `vt_news_posts`
--

CREATE TABLE IF NOT EXISTS `vt_news_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `short_content` text,
  `content` text NOT NULL,
  `media_content` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `key` varchar(255) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_keywords` text,
  `meta_description` text,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `vt_news_posts`
--

INSERT INTO `vt_news_posts` (`id`, `title`, `cover`, `short_content`, `content`, `media_content`, `active`, `key`, `meta_title`, `meta_keywords`, `meta_description`, `modified`, `created`) VALUES
(1, 'new 1', '/uploads/news-img.jpg', NULL, '<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators.</p>\r\n            <img src="/img/text-img.jpg"><p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators.</p>\r\n            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators.</p>\r\n            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators.</p>\r\n            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators.</p>\r\n            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators.</p>', '', 1, '', NULL, '', '', '2014-11-26 17:43:52', '2014-11-25 19:53:02'),
(2, 'new 2', '/uploads/contact.jpg', NULL, '<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators.</p>\r\n            <img src="/img/text-img.jpg"><p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators.</p>\r\n            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators.</p>\r\n            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators.</p>\r\n            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators.</p>\r\n            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators.</p>', '', 1, '', NULL, '', '', '2014-11-26 17:41:44', '2014-11-25 19:53:19'),
(3, 'news 3', '/uploads/news-img.jpg', NULL, '<div>Monotonectally empower strategic convergence after principle-centered partnerships. Appropriately underwhelm extensible platforms whereas future-proof infrastructures. Proactively initiate enterprise channels vis-a-vis error-free imperatives. Progressively formulate compelling value rather than covalent e-markets. Rapidiously syndicate reliable paradigms for accurate expertise.</div><div><br></div><div>Dynamically exploit web-enabled strategic theme areas through resource maximizing action items. Competently strategize client-centric convergence without exceptional deliverables. Energistically facilitate long-term high-impact outsourcing for premium supply chains. Monotonectally implement installed base information and innovative services. Uniquely morph equity invested e-markets rather than compelling niche markets.</div><div><br></div><div>Interactively enable cross-media process improvements without state of the art collaboration and idea-sharing. Seamlessly leverage existing seamless technology with cross-media services. Assertively promote out-of-the-box e-services after high-payoff initiatives. Seamlessly communicate multimedia based leadership skills and interoperable e-markets. Continually utilize interoperable ideas before bricks-and-clicks opportunities.</div><div><br></div><div>Completely harness intermandated applications via team driven human capital. Distinctively architect enterprise-wide sources for just in time alignments. Dynamically incentivize plug-and-play schemas for premier niche markets. Distinctively build market positioning results before an expanded array of quality vectors. Phosfluorescently procrastinate virtual systems via open-source sources.</div><div><br></div><div>Dynamically facilitate seamless e-business for next-generation e-tailers. Monotonectally communicate backward-compatible collaboration and idea-sharing via resource sucking methods of empowerment. Holisticly extend functionalized applications rather than professional leadership. Phosfluorescently scale accurate technologies via standards compliant best practices. Monotonectally streamline innovative leadership whereas effective action items.</div><div><br></div><div>Intrinsicly actualize virtual meta-services via superior metrics. Distinctively monetize global synergy and global information. Conveniently e-enable backward-compatible scenarios with alternative mindshare. Enthusiastically re-engineer resource maximizing niches via customer directed scenarios. Proactively leverage other''s integrated experiences without progressive synergy.</div><div><br></div><div>Phosfluorescently brand standards compliant paradigms whereas real-time results. Credibly transition highly efficient information and premier collaboration and idea-sharing. Appropriately drive top-line portals for empowered expertise. Intrinsicly transition viral solutions whereas virtual expertise. Monotonectally aggregate empowered methods of empowerment vis-a-vis effective value.</div><div><br></div><div>Efficiently orchestrate leveraged strategic theme areas rather than reliable best practices. Professionally reinvent progressive experiences through excellent meta-services. Globally maximize client-centered technologies via an expanded array of manufactured.</div>', '', 1, '', NULL, '', '', '2014-11-27 08:00:31', '2014-11-26 20:28:00');

-- --------------------------------------------------------

--
-- Структура таблицы `vt_options`
--

CREATE TABLE IF NOT EXISTS `vt_options` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  `value` text,
  `group` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `vt_options`
--

INSERT INTO `vt_options` (`id`, `key`, `value`, `group`, `created`, `modified`) VALUES
(1, 'facebook_link', 'https://www.facebook.com', 'link', NULL, '2013-11-29 11:05:51'),
(2, 'twitter_link', 'https://twitter.com', 'link', NULL, '2013-11-29 11:06:01'),
(3, 'linkedin_link', 'http://www.linkedin.com', 'link', NULL, '2013-11-29 11:06:09'),
(4, 'slogan', '', 'info', '2013-07-05 12:49:27', '2014-11-26 18:54:08'),
(5, 'site_info_email', 'info@katclaims.com', 'info', '2013-11-29 00:00:00', '2014-11-26 18:54:19'),
(6, 'rss', '', 'info', '2013-11-29 00:00:00', '2013-12-22 16:13:11'),
(7, 'phone', '888.777.8899', 'info', NULL, '2014-11-26 18:54:31'),
(8, 'google_link', '#', 'link', NULL, '2013-11-29 15:15:06'),
(9, 'adress', '', 'info', NULL, '2014-11-26 18:55:07');

-- --------------------------------------------------------

--
-- Структура таблицы `vt_pages`
--

CREATE TABLE IF NOT EXISTS `vt_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `layout` varchar(255) NOT NULL DEFAULT 'default',
  `banner` varchar(255) DEFAULT NULL,
  `banner_desc` varchar(255) NOT NULL,
  `short_content` text,
  `content` text NOT NULL,
  `media_content` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `key` varchar(255) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_keywords` text,
  `meta_description` text,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `vt_pages`
--

INSERT INTO `vt_pages` (`id`, `title`, `layout`, `banner`, `banner_desc`, `short_content`, `content`, `media_content`, `active`, `key`, `meta_title`, `meta_keywords`, `meta_description`, `modified`, `created`) VALUES
(1, 'About us', 'about', '/uploads/about_us.jpg', 'About Spartan P&C Claim Services', NULL, '<div class="sub-title">\r\n        <p>Spartan PnC Services, Inc. is a long established fact that a reader will be distracted by the<br>readable hen looking content of a page when looking at its layout. </p>\r\n    </div>\r\n    <p>There are many variations of passages of Lorem \r\n        Ipsum available, but the majority have suffered alteration in some form,\r\n        by injected humour, or randomised words which don''t look even slightly believable.\r\n        If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything \r\n        embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to\r\n        repeat predefined chunks as necessary, making this the first true generator.\r\n    </p>', '', 1, 'about', NULL, '', '', '2014-11-27 09:34:40', '2014-11-25 20:12:43'),
(2, 'CONTACT US', 'contact', '/uploads/contact.jpg', 'Contact Spartan P&C Claim Services', NULL, '<p>Weâ€™d like to hear from you!  Contact us via phone, or submit a question on the form to the right <br>and weâ€™ll get back to you.</p>', '', 1, 'contact', NULL, '', '', '2014-11-27 09:34:23', '2014-11-25 20:52:24'),
(3, 'SPARTAN PNC SERVICES', 'home', '', '', NULL, '<h2><strong>Welcome</strong> to Spartan PnC Services, Inc.</h2>\r\n<p><strong>All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator.</strong></p>\r\n<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator.</p>\r\n<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator.</p>', '', 1, 'home', NULL, '', '', '2014-11-27 09:34:09', '2014-11-26 18:45:33'),
(4, 'Assigments', 'assigments', '/uploads/services.jpg', 'Spartan P&C Claim Services', NULL, '<h2><strong>Lorem ipsum</strong> to Spartan PnC Services, Inc.</h2>\r\n    <p><strong>All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator. </strong></p>\r\n    <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator.</p>\r\n    <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator. </p>', '', 1, 'assigments', NULL, '', '', '2014-11-27 10:11:41', '2014-11-26 19:49:34');

-- --------------------------------------------------------

--
-- Структура таблицы `vt_services`
--

CREATE TABLE IF NOT EXISTS `vt_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `lft` int(20) DEFAULT NULL,
  `rght` int(20) DEFAULT NULL,
  `parent_id` int(20) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `vt_services`
--

INSERT INTO `vt_services` (`id`, `title`, `cover`, `description`, `lft`, `rght`, `parent_id`, `created`) VALUES
(1, 'Day claims', '/uploads/slide1.jpg', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator', 1, 6, NULL, '2014-12-01 18:13:43'),
(2, 'Cat claims', '/uploads/slide2.jpg', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator', 13, 18, NULL, '2014-12-01 18:16:43'),
(3, 'Desc claims /  TPA', '/uploads/slide3.jpg', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator', 7, 12, NULL, '2014-12-01 18:17:06'),
(4, 'PROPERTY CLAIM', '/uploads/slide1.jpg', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable you are going to use.', 2, 3, 1, '2014-12-06 11:24:01'),
(5, 'CASUALTY CLAIM', '/uploads/slide1.jpg', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable you are going to use.', 4, 5, 1, '2014-12-06 11:24:26'),
(6, 'PROPERTY CLAIM', '/uploads/slide2.jpg', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable you are going to use.', 8, 9, 3, '2014-12-06 11:26:00'),
(7, 'CASUALTY CLAIM', '/uploads/slide1.jpg', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable you are going to use.', 10, 11, 3, '2014-12-06 11:26:48'),
(8, 'PROPERTY CLAIM', '/uploads/slide1.jpg', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable you are going to use.', 14, 15, 2, '2014-12-06 11:26:48'),
(10, 'CASUALTY CLAIM', '/uploads/slide1.jpg', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable you are going to use.', 16, 17, 2, '2014-12-06 11:33:54');

-- --------------------------------------------------------

--
-- Структура таблицы `vt_service_categories`
--

CREATE TABLE IF NOT EXISTS `vt_service_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `lft` int(20) DEFAULT NULL,
  `rght` int(20) DEFAULT NULL,
  `parent_id` int(20) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `vt_service_categories`
--

INSERT INTO `vt_service_categories` (`id`, `title`, `cover`, `description`, `lft`, `rght`, `parent_id`, `created`) VALUES
(1, 'Day claims', '/uploads/slide1.jpg', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator', 1, 2, NULL, '2014-12-01 18:13:43'),
(2, 'Cat claims', '/uploads/slide2.jpg', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator', 3, 4, NULL, '2014-12-01 18:16:43'),
(3, 'Desc claims /  TPA', '/uploads/slide3.jpg', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don''t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn''t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator', 5, 6, NULL, '2014-12-01 18:17:06');

-- --------------------------------------------------------

--
-- Структура таблицы `vt_site_menus`
--

CREATE TABLE IF NOT EXISTS `vt_site_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `key` varchar(50) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `vt_site_menus`
--

INSERT INTO `vt_site_menus` (`id`, `name`, `key`, `parent_id`, `lft`, `rght`, `created`, `modified`, `active`) VALUES
(1, 'Header', 'header', NULL, 1, 2, NULL, NULL, 1),
(2, 'Footer', 'footer', NULL, 3, 4, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `vt_slides`
--

CREATE TABLE IF NOT EXISTS `vt_slides` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `src` text CHARACTER SET utf8,
  `alt` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `vt_users`
--

CREATE TABLE IF NOT EXISTS `vt_users` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bday` date DEFAULT NULL,
  `original_photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `group_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group_type_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_login` datetime NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `vt_users`
--

INSERT INTO `vt_users` (`id`, `email`, `username`, `first_name`, `last_name`, `bday`, `original_photo`, `avatar`, `password`, `group_id`, `group_type_id`, `last_login`, `created`, `modified`) VALUES
(1, 'admin@admin.loc', 'admin', NULL, NULL, NULL, NULL, NULL, 'e4cfd2942bd6f131c1efe6ce49416a1da43c635c', NULL, NULL, '0000-00-00 00:00:00', '2013-08-23 13:18:08', '2013-12-04 09:37:47');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
