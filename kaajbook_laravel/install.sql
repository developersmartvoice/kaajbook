-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2022 at 03:26 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vipspm_22`
--

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_03_02_125050_create_password_resets_table', 1),
(2, '2019_03_04_060910_create_users_table', 1),
(3, '2019_03_11_055248_create_roles_table', 1),
(4, '2019_03_12_082413_create_departments_table', 1),
(5, '2019_03_28_080744_create_department_role_table', 1),
(6, '2019_03_28_125110_create_mailboxs_table', 1),
(7, '2019_03_29_045017_create_mailboxs_attachment_table', 1),
(8, '2019_03_30_053414_create_menu_table', 1),
(9, '2019_04_08_061711_create_countries_table', 1),
(10, '2019_04_08_061732_create_languages_table', 1),
(11, '2019_04_08_061750_create_locales_table', 1),
(12, '2019_04_08_062349_create_currency_table', 1),
(13, '2019_04_09_063535_create_department_role_menu_table', 1),
(14, '2019_04_09_074557_create_files_table', 1),
(15, '2019_04_09_075257_create_filebrowser_table', 1),
(16, '2019_04_12_121310_create_user_role_department_table', 1),
(17, '2019_04_16_045924_create_announcements_table', 1),
(18, '2019_04_16_065823_create_holidays_table', 1),
(19, '2019_04_16_074824_create_meetings_table', 1),
(20, '2019_04_16_074836_create_meeting_member_table', 1),
(21, '2019_04_19_054404_create_user_activities_table', 1),
(22, '2019_04_19_063045_create_email_group_table', 1),
(23, '2019_04_19_063114_create_email_template_table', 1),
(24, '2019_04_20_045426_create_team_table', 1),
(25, '2019_04_20_045438_create_team_members_table', 1),
(26, '2019_04_26_051327_create_clients_table', 1),
(27, '2019_04_27_071955_create_settings_table', 1),
(28, '2019_04_29_233943_create_form_table', 1),
(29, '2019_04_29_233948_create_customfields_table', 1),
(30, '2019_05_02_233217_create_projects_table', 1),
(31, '2019_05_02_233608_create_project_user_table', 1),
(32, '2019_05_03_224431_create_project_comments_table', 1),
(33, '2019_05_04_002218_create_project_attachments_table', 1),
(34, '2019_05_04_024503_create_tasks_table', 1),
(35, '2019_05_04_024539_create_task_user_table', 1),
(36, '2019_05_09_210418_create_task_comments_table', 1),
(37, '2019_05_09_234326_create_translations_table', 1),
(38, '2019_05_10_034825_create_database_backup_table', 1),
(39, '2019_05_10_222358_create_task_attachments_table', 1),
(40, '2019_05_12_203806_create_defects_table', 1),
(41, '2019_05_12_205620_create_defect_user_table', 1),
(42, '2019_05_12_213505_create_defect_history_table', 1),
(43, '2019_05_15_213459_create_defect_comments_table', 1),
(44, '2019_05_15_231520_create_defect_attachments_table', 1),
(45, '2019_05_16_233252_create_knowledge_base_categories_table', 1),
(46, '2019_05_16_233452_create_knowledge_base_articles_table', 1),
(47, '2019_05_17_224924_create_project_planner_sprints_table', 1),
(48, '2019_05_17_225011_create_project_planner_sprint_members_table', 1),
(49, '2019_05_17_231408_create_project_sprint_tasks_table', 1),
(50, '2019_05_17_231416_create_project_sprint_task_members_table', 1),
(51, '2019_05_19_214115_create_incidents_table', 1),
(52, '2019_05_19_214751_create_incident_user_table', 1),
(53, '2019_05_19_214834_create_incident_history_table', 1),
(54, '2019_05_19_214924_create_incident_attachments_table', 1),
(55, '2019_05_19_214953_create_incident_comments_table', 1),
(56, '2019_07_31_104949_create_jobs_table', 1),
(57, '2020_02_06_182202_create_providers_table', 1),
(58, '2020_02_06_182238_create_appointment_table', 1),
(59, '2020_02_15_123556_create_modules_table', 1),
(60, '2020_02_15_160826_create_timesheets_table', 1),
(61, '2020_03_04_124408_create_todos_table', 1),
(62, '2020_07_24_085713_create_slack_integration_table', 1),
(63, '2020_08_04_175319_create_leave_types_table', 1),
(64, '2020_08_04_175332_create_leaves_table', 1),
(65, '2020_08_05_143719_create_taxes_table', 1),
(66, '2020_08_05_143823_create_items_table', 1),
(67, '2020_08_05_154127_create_item_tax_table', 1),
(68, '2020_09_14_123748_create_estimate_settings_table', 1),
(69, '2020_09_14_183143_create_estimates_table', 1),
(70, '2020_09_14_183217_create_estimate_items_table', 1),
(71, '2020_09_14_183546_create_estimate_item_taxes_table', 1),
(72, '2020_09_25_163042_create_invoice_setting_table', 1),
(73, '2020_09_25_163100_create_invoices_table', 1),
(74, '2020_09_25_163122_create_invoice_items_table', 1),
(75, '2020_09_25_163140_create_invoice_item_taxes_table', 1),
(76, '2020_10_13_182214_create_payment_methods_table', 1),
(77, '2020_10_14_102209_create_payments_table', 1),
(78, '2020_11_12_160446_create_leave_attachments_table', 1),
(79, '2020_11_21_162154_create_payment_gateway_settings_table', 1),
(80, '2020_12_01_151213_create_slack_settings_table', 1),
(81, '2020_12_05_205341_create_dashboard_settings_table', 1),
(82, '2020_12_16_165220_create_timer_logs_table', 1),
(83, '2022_03_24_113550_create_appointment_recurrence_table', 1),
(84, '2022_04_23_171512_create_notifications_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_announcements`
--

CREATE TABLE `vipspm_announcements` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=Unpublish,1=Publish,2=Completed',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `all_client` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=None,1=All Client',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_appointment`
--

CREATE TABLE `vipspm_appointment` (
  `id` int(10) UNSIGNED NOT NULL,
  `generated_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'Used for requester',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `attendees` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_date_time` datetime NOT NULL,
  `end_date_time` datetime NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` mediumtext COLLATE utf8_unicode_ci,
  `recurrence` tinyint(1) NOT NULL DEFAULT '0',
  `recurrence_occurrences` int(11) DEFAULT NULL,
  `recurrence_pattern` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '1=Reserved,2=Confirmed,3=Finished,4=Canceled',
  `send_confirmation` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_appointment_recurrence`
--

CREATE TABLE `vipspm_appointment_recurrence` (
  `id` int(10) UNSIGNED NOT NULL,
  `appointment_id` int(10) UNSIGNED NOT NULL,
  `start_date_time` datetime NOT NULL,
  `end_date_time` datetime NOT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '1=Reserved, 2=Confirmed, 3=Finished, 4=Canceled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_clients`
--

CREATE TABLE `vipspm_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `company_phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_zipcode` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_fax` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_address` text COLLATE utf8_unicode_ci,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `skype_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `linkedin` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hosting_company` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `host_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `host_username` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `host_password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `host_port` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_countries`
--

CREATE TABLE `vipspm_countries` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_countries`
--

INSERT INTO `vipspm_countries` (`id`, `code`, `name`) VALUES
(1, 'AF', 'Afghanistan'),
(2, 'AL', 'Albania'),
(3, 'DZ', 'Algeria'),
(4, 'AS', 'American Samoa'),
(5, 'AD', 'Andorra'),
(6, 'AO', 'Angola'),
(7, 'AI', 'Anguilla'),
(8, 'AQ', 'Antarctica'),
(9, 'AG', 'Antigua and Barbuda'),
(10, 'AR', 'Argentina'),
(11, 'AM', 'Armenia'),
(12, 'AW', 'Aruba'),
(13, 'AU', 'Australia'),
(14, 'AT', 'Austria'),
(15, 'AZ', 'Azerbaijan'),
(16, 'BS', 'Bahamas'),
(17, 'BH', 'Bahrain'),
(18, 'BD', 'Bangladesh'),
(19, 'BB', 'Barbados'),
(20, 'BY', 'Belarus'),
(21, 'BE', 'Belgium'),
(22, 'BZ', 'Belize'),
(23, 'BJ', 'Benin'),
(24, 'BM', 'Bermuda'),
(25, 'BT', 'Bhutan'),
(26, 'BO', 'Bolivia'),
(27, 'BA', 'Bosnia and Herzegovina'),
(28, 'BW', 'Botswana'),
(29, 'BV', 'Bouvet Island'),
(30, 'BR', 'Brazil'),
(31, 'BQ', 'British Antarctic Territory'),
(32, 'IO', 'British Indian Ocean Territory'),
(33, 'VG', 'British Virgin Islands'),
(34, 'BN', 'Brunei'),
(35, 'BG', 'Bulgaria'),
(36, 'BF', 'Burkina Faso'),
(37, 'BI', 'Burundi'),
(38, 'KH', 'Cambodia'),
(39, 'CM', 'Cameroon'),
(40, 'CA', 'Canada'),
(41, 'CT', 'Canton and Enderbury Islands'),
(42, 'CV', 'Cape Verde'),
(43, 'KY', 'Cayman Islands'),
(44, 'CF', 'Central African Republic'),
(45, 'TD', 'Chad'),
(46, 'CL', 'Chile'),
(47, 'CN', 'China'),
(48, 'CX', 'Christmas Island'),
(49, 'CC', 'Cocos [Keeling] Islands'),
(50, 'CO', 'Colombia'),
(51, 'KM', 'Comoros'),
(52, 'CG', 'Congo - Brazzaville'),
(53, 'CD', 'Congo - Kinshasa'),
(54, 'CK', 'Cook Islands'),
(55, 'CR', 'Costa Rica'),
(56, 'HR', 'Croatia'),
(57, 'CU', 'Cuba'),
(58, 'CY', 'Cyprus'),
(59, 'CZ', 'Czech Republic'),
(60, 'CI', 'Côte d’Ivoire'),
(61, 'DK', 'Denmark'),
(62, 'DJ', 'Djibouti'),
(63, 'DM', 'Dominica'),
(64, 'DO', 'Dominican Republic'),
(65, 'NQ', 'Dronning Maud Land'),
(66, 'DD', 'East Germany'),
(67, 'EC', 'Ecuador'),
(68, 'EG', 'Egypt'),
(69, 'SV', 'El Salvador'),
(70, 'GQ', 'Equatorial Guinea'),
(71, 'ER', 'Eritrea'),
(72, 'EE', 'Estonia'),
(73, 'ET', 'Ethiopia'),
(74, 'FK', 'Falkland Islands'),
(75, 'FO', 'Faroe Islands'),
(76, 'FJ', 'Fiji'),
(77, 'FI', 'Finland'),
(78, 'FR', 'France'),
(79, 'GF', 'French Guiana'),
(80, 'PF', 'French Polynesia'),
(81, 'TF', 'French Southern Territories'),
(82, 'FQ', 'French Southern and Antarctic Territories'),
(83, 'GA', 'Gabon'),
(84, 'GM', 'Gambia'),
(85, 'GE', 'Georgia'),
(86, 'DE', 'Germany'),
(87, 'GH', 'Ghana'),
(88, 'GI', 'Gibraltar'),
(89, 'GR', 'Greece'),
(90, 'GL', 'Greenland'),
(91, 'GD', 'Grenada'),
(92, 'GP', 'Guadeloupe'),
(93, 'GU', 'Guam'),
(94, 'GT', 'Guatemala'),
(95, 'GG', 'Guernsey'),
(96, 'GN', 'Guinea'),
(97, 'GW', 'Guinea-Bissau'),
(98, 'GY', 'Guyana'),
(99, 'HT', 'Haiti'),
(100, 'HM', 'Heard Island and McDonald Islands'),
(101, 'HN', 'Honduras'),
(102, 'HK', 'Hong Kong SAR China'),
(103, 'HU', 'Hungary'),
(104, 'IS', 'Iceland'),
(105, 'IN', 'India'),
(106, 'ID', 'Indonesia'),
(107, 'IR', 'Iran'),
(108, 'IQ', 'Iraq'),
(109, 'IE', 'Ireland'),
(110, 'IM', 'Isle of Man'),
(111, 'IL', 'Israel'),
(112, 'IT', 'Italy'),
(113, 'JM', 'Jamaica'),
(114, 'JP', 'Japan'),
(115, 'JE', 'Jersey'),
(116, 'JT', 'Johnston Island'),
(117, 'JO', 'Jordan'),
(118, 'KZ', 'Kazakhstan'),
(119, 'KE', 'Kenya'),
(120, 'KI', 'Kiribati'),
(121, 'KW', 'Kuwait'),
(122, 'KG', 'Kyrgyzstan'),
(123, 'LA', 'Laos'),
(124, 'LV', 'Latvia'),
(125, 'LB', 'Lebanon'),
(126, 'LS', 'Lesotho'),
(127, 'LR', 'Liberia'),
(128, 'LY', 'Libya'),
(129, 'LI', 'Liechtenstein'),
(130, 'LT', 'Lithuania'),
(131, 'LU', 'Luxembourg'),
(132, 'MO', 'Macau SAR China'),
(133, 'MK', 'Macedonia'),
(134, 'MG', 'Madagascar'),
(135, 'MW', 'Malawi'),
(136, 'MY', 'Malaysia'),
(137, 'MV', 'Maldives'),
(138, 'ML', 'Mali'),
(139, 'MT', 'Malta'),
(140, 'MH', 'Marshall Islands'),
(141, 'MQ', 'Martinique'),
(142, 'MR', 'Mauritania'),
(143, 'MU', 'Mauritius'),
(144, 'YT', 'Mayotte'),
(145, 'FX', 'Metropolitan France'),
(146, 'MX', 'Mexico'),
(147, 'FM', 'Micronesia'),
(148, 'MI', 'Midway Islands'),
(149, 'MD', 'Moldova'),
(150, 'MC', 'Monaco'),
(151, 'MN', 'Mongolia'),
(152, 'ME', 'Montenegro'),
(153, 'MS', 'Montserrat'),
(154, 'MA', 'Morocco'),
(155, 'MZ', 'Mozambique'),
(156, 'MM', 'Myanmar [Burma]'),
(157, 'NA', 'Namibia'),
(158, 'NR', 'Nauru'),
(159, 'NP', 'Nepal'),
(160, 'NL', 'Netherlands'),
(161, 'AN', 'Netherlands Antilles'),
(162, 'NT', 'Neutral Zone'),
(163, 'NC', 'New Caledonia'),
(164, 'NZ', 'New Zealand'),
(165, 'NI', 'Nicaragua'),
(166, 'NE', 'Niger'),
(167, 'NG', 'Nigeria'),
(168, 'NU', 'Niue'),
(169, 'NF', 'Norfolk Island'),
(170, 'KP', 'North Korea'),
(171, 'VD', 'North Vietnam'),
(172, 'MP', 'Northern Mariana Islands'),
(173, 'NO', 'Norway'),
(174, 'OM', 'Oman'),
(175, 'PC', 'Pacific Islands Trust Territory'),
(176, 'PK', 'Pakistan'),
(177, 'PW', 'Palau'),
(178, 'PS', 'Palestinian Territories'),
(179, 'PA', 'Panama'),
(180, 'PZ', 'Panama Canal Zone'),
(181, 'PG', 'Papua New Guinea'),
(182, 'PY', 'Paraguay'),
(183, 'YD', 'People\'s Democratic Republic of Yemen'),
(184, 'PE', 'Peru'),
(185, 'PH', 'Philippines'),
(186, 'PN', 'Pitcairn Islands'),
(187, 'PL', 'Poland'),
(188, 'PT', 'Portugal'),
(189, 'PR', 'Puerto Rico'),
(190, 'QA', 'Qatar'),
(191, 'RO', 'Romania'),
(192, 'RU', 'Russia'),
(193, 'RW', 'Rwanda'),
(194, 'RE', 'Réunion'),
(195, 'BL', 'Saint Barthélemy'),
(196, 'SH', 'Saint Helena'),
(197, 'KN', 'Saint Kitts and Nevis'),
(198, 'LC', 'Saint Lucia'),
(199, 'MF', 'Saint Martin'),
(200, 'PM', 'Saint Pierre and Miquelon'),
(201, 'VC', 'Saint Vincent and the Grenadines'),
(202, 'WS', 'Samoa'),
(203, 'SM', 'San Marino'),
(204, 'SA', 'Saudi Arabia'),
(205, 'SN', 'Senegal'),
(206, 'RS', 'Serbia'),
(207, 'CS', 'Serbia and Montenegro'),
(208, 'SC', 'Seychelles'),
(209, 'SL', 'Sierra Leone'),
(210, 'SG', 'Singapore'),
(211, 'SK', 'Slovakia'),
(212, 'SI', 'Slovenia'),
(213, 'SB', 'Solomon Islands'),
(214, 'SO', 'Somalia'),
(215, 'ZA', 'South Africa'),
(216, 'GS', 'South Georgia and the South Sandwich Islands'),
(217, 'KR', 'South Korea'),
(218, 'ES', 'Spain'),
(219, 'LK', 'Sri Lanka'),
(220, 'SD', 'Sudan'),
(221, 'SR', 'Suriname'),
(222, 'SJ', 'Svalbard and Jan Mayen'),
(223, 'SZ', 'Swaziland'),
(224, 'SE', 'Sweden'),
(225, 'CH', 'Switzerland'),
(226, 'SY', 'Syria'),
(227, 'ST', 'São Tomé and Príncipe'),
(228, 'TW', 'Taiwan'),
(229, 'TJ', 'Tajikistan'),
(230, 'TZ', 'Tanzania'),
(231, 'TH', 'Thailand'),
(232, 'TL', 'Timor-Leste'),
(233, 'TG', 'Togo'),
(234, 'TK', 'Tokelau'),
(235, 'TO', 'Tonga'),
(236, 'TT', 'Trinidad and Tobago'),
(237, 'TN', 'Tunisia'),
(238, 'TR', 'Turkey'),
(239, 'TM', 'Turkmenistan'),
(240, 'TC', 'Turks and Caicos Islands'),
(241, 'TV', 'Tuvalu'),
(242, 'UM', 'U.S. Minor Outlying Islands'),
(243, 'PU', 'U.S. Miscellaneous Pacific Islands'),
(244, 'VI', 'U.S. Virgin Islands'),
(245, 'UG', 'Uganda'),
(246, 'UA', 'Ukraine'),
(247, 'SU', 'Union of Soviet Socialist Republics'),
(248, 'AE', 'United Arab Emirates'),
(249, 'GB', 'United Kingdom'),
(250, 'US', 'United States'),
(251, 'ZZ', 'Unknown or Invalid Region'),
(252, 'UY', 'Uruguay'),
(253, 'UZ', 'Uzbekistan'),
(254, 'VU', 'Vanuatu'),
(255, 'VA', 'Vatican City'),
(256, 'VE', 'Venezuela'),
(257, 'VN', 'Vietnam'),
(258, 'WK', 'Wake Island'),
(259, 'WF', 'Wallis and Futuna'),
(260, 'EH', 'Western Sahara'),
(261, 'YE', 'Yemen'),
(262, 'ZM', 'Zambia'),
(263, 'ZW', 'Zimbabwe'),
(264, 'AX', 'Åland Islands');

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_currency`
--

CREATE TABLE `vipspm_currency` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `symbol` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_currency`
--

INSERT INTO `vipspm_currency` (`id`, `code`, `name`, `symbol`) VALUES
(1, 'AUD', 'Australian Dollar', '$'),
(2, 'BDT', 'Bangladesh', '৳'),
(3, 'BRL', 'Brazilian Real', 'R$'),
(4, 'CAD', 'Canadian Dollar', '$'),
(5, 'CHF', 'Swiss Franc', 'Fr.'),
(6, 'CLP', 'Chilean Peso', '$'),
(7, 'CNY', 'Chinese Yuan', '¥'),
(8, 'CZK', 'Czech Koruna', 'Kč'),
(9, 'DKK', 'Danish Krone', 'kr.'),
(10, 'EUR', 'Euro', '€'),
(11, 'GBP', 'British Pound', '£'),
(12, 'HKD', 'Hong Kong Dollar', '$'),
(13, 'HUF', 'Hungarian Forint', 'Ft'),
(14, 'IDR', 'Indonesian Rupiah', 'Rp'),
(15, 'ILS', 'Israeli New Shekel', '₪'),
(16, 'INR', 'Indian Rupee', '₹'),
(17, 'JPY', 'Japanese Yen', '¥'),
(18, 'KRW', 'Korean Won', '₩'),
(19, 'MXN', 'Mexican Peso', '$'),
(20, 'MYR', 'Malaysian Ringgit', 'RM'),
(21, 'NOK', 'Norwegian Krone', 'kr'),
(22, 'NZD', 'New Zealand Dollar', '$'),
(23, 'PHP', 'Philippine Peso', '₱'),
(24, 'PKR', 'Pakistan Rupee', '₨'),
(25, 'PLN', 'Polish Zloty', 'zł'),
(26, 'RUB', 'Russian Ruble', 'RUB'),
(27, 'SEK', 'Swedish Krona', 'kr'),
(28, 'SGD', 'Singapore Dollar', '$'),
(29, 'THB', 'Thai Baht', '฿'),
(30, 'TRY', 'Turkish Lira', '₺'),
(31, 'TWD', 'Taiwan Dollar', '$'),
(32, 'USD', 'US Dollar', '$'),
(33, 'VEF', 'Bolívar Fuerte', 'Bs.'),
(34, 'ZAR', 'South African Rand', 'R');

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_custom_fields`
--

CREATE TABLE `vipspm_custom_fields` (
  `id` int(10) UNSIGNED NOT NULL,
  `form_id` int(10) UNSIGNED NOT NULL,
  `field_label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `field_column` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `default_value` text COLLATE utf8_unicode_ci,
  `help_text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_type` enum('text','textarea','dropdown','date','checkbox','numeric','url') COLLATE utf8_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'false=Inactive,true=Active',
  `show_on_details` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_dashboard_settings`
--

CREATE TABLE `vipspm_dashboard_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `is_projects_widget` tinyint(1) NOT NULL DEFAULT '1',
  `is_tasks_widget` tinyint(1) NOT NULL DEFAULT '1',
  `is_defects_widget` tinyint(1) NOT NULL DEFAULT '1',
  `is_incidents_widget` tinyint(1) NOT NULL DEFAULT '1',
  `is_estimates_widget` tinyint(1) NOT NULL DEFAULT '1',
  `is_invoices_widget` tinyint(1) NOT NULL DEFAULT '1',
  `is_payments_widget` tinyint(1) NOT NULL DEFAULT '1',
  `is_leaves_widget` tinyint(1) NOT NULL DEFAULT '1',
  `is_users_widget` tinyint(1) NOT NULL DEFAULT '1',
  `is_clients_widget` tinyint(1) NOT NULL DEFAULT '1',
  `is_teams_widget` tinyint(1) NOT NULL DEFAULT '1',
  `is_articles_widget` tinyint(1) NOT NULL DEFAULT '1',
  `is_projects_status_chart` tinyint(1) NOT NULL DEFAULT '1',
  `is_tasks_status_chart` tinyint(1) NOT NULL DEFAULT '1',
  `is_monthly_report_chart` tinyint(1) NOT NULL DEFAULT '1',
  `is_projects_list` tinyint(1) NOT NULL DEFAULT '1',
  `is_tasks_list` tinyint(1) NOT NULL DEFAULT '1',
  `is_defects_list` tinyint(1) NOT NULL DEFAULT '1',
  `is_incidents_list` tinyint(1) NOT NULL DEFAULT '1',
  `is_estimates_list` tinyint(1) NOT NULL DEFAULT '1',
  `is_invoices_list` tinyint(1) NOT NULL DEFAULT '1',
  `is_todos_list` tinyint(1) NOT NULL DEFAULT '1',
  `is_activities_list` tinyint(1) NOT NULL DEFAULT '1',
  `is_meetings_list` tinyint(1) NOT NULL DEFAULT '1',
  `is_announcement_list` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_dashboard_settings`
--

INSERT INTO `vipspm_dashboard_settings` (`id`, `is_projects_widget`, `is_tasks_widget`, `is_defects_widget`, `is_incidents_widget`, `is_estimates_widget`, `is_invoices_widget`, `is_payments_widget`, `is_leaves_widget`, `is_users_widget`, `is_clients_widget`, `is_teams_widget`, `is_articles_widget`, `is_projects_status_chart`, `is_tasks_status_chart`, `is_monthly_report_chart`, `is_projects_list`, `is_tasks_list`, `is_defects_list`, `is_incidents_list`, `is_estimates_list`, `is_invoices_list`, `is_todos_list`, `is_activities_list`, `is_meetings_list`, `is_announcement_list`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2022-06-06 13:26:30', '2022-06-06 13:26:30');

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_database_backups`
--

CREATE TABLE `vipspm_database_backups` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_defects`
--

CREATE TABLE `vipspm_defects` (
  `id` int(10) UNSIGNED NOT NULL,
  `create_user_id` int(11) NOT NULL,
  `generated_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(11) NOT NULL,
  `project_version` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `defect_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=Defects,2=Enhancement',
  `defect_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `estimated_hours` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `actual_hours` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=Assigned,2=Closed,3=In Progress,4=Open,5=Solved,6=Re-open,7=Deferred',
  `assigned_group_id` int(11) DEFAULT NULL,
  `assign_member` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `severity` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=Low,2=Medium,3=High,4=Very High,5=Urgent',
  `notes` text COLLATE utf8_unicode_ci,
  `attachment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attachment_hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `orders` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_defects_history`
--

CREATE TABLE `vipspm_defects_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `defect_id` int(10) UNSIGNED NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created_by_id` int(11) NOT NULL,
  `commented_by_id` int(11) DEFAULT NULL,
  `solved_by_id` int(11) DEFAULT NULL,
  `closed_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_defect_attachments`
--

CREATE TABLE `vipspm_defect_attachments` (
  `id` int(10) UNSIGNED NOT NULL,
  `defect_id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_extension` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_defect_comments`
--

CREATE TABLE `vipspm_defect_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `defect_id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `attachments` text COLLATE utf8_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_defect_user`
--

CREATE TABLE `vipspm_defect_user` (
  `defect_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_departments`
--

CREATE TABLE `vipspm_departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_departments`
--

INSERT INTO `vipspm_departments` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administration', '2022-06-06 13:26:43', '2022-06-06 13:26:43', NULL),
(2, 'Project', '2022-06-06 13:26:44', '2022-06-06 13:26:44', NULL),
(3, 'HR', '2022-06-06 13:26:44', '2022-06-06 13:26:44', NULL),
(4, 'Sales', '2022-06-06 13:26:44', '2022-06-06 13:26:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_departments_roles`
--

CREATE TABLE `vipspm_departments_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_departments_roles`
--

INSERT INTO `vipspm_departments_roles` (`id`, `role_id`, `department_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 2),
(5, 5, 3),
(6, 6, 4);

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_department_role_menu`
--

CREATE TABLE `vipspm_department_role_menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `menu_id` int(10) UNSIGNED NOT NULL,
  `view` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `edited` int(11) NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_department_role_menu`
--

INSERT INTO `vipspm_department_role_menu` (`id`, `department_id`, `role_id`, `menu_id`, `view`, `created`, `edited`, `deleted`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1),
(2, 1, 2, 1, 1, 0, 0, 0),
(3, 1, 3, 1, 1, 0, 0, 0),
(4, 2, 4, 1, 1, 0, 0, 0),
(5, 3, 5, 1, 1, 0, 0, 0),
(6, 4, 6, 1, 1, 0, 0, 0),
(7, 1, 1, 2, 2, 2, 2, 2),
(8, 1, 2, 2, 2, 0, 0, 0),
(9, 1, 3, 2, 2, 0, 0, 0),
(10, 2, 4, 2, 2, 0, 0, 0),
(11, 3, 5, 2, 2, 0, 0, 0),
(12, 4, 6, 2, 2, 0, 0, 0),
(13, 1, 1, 3, 3, 3, 3, 3),
(14, 1, 2, 3, 3, 0, 0, 0),
(15, 1, 3, 3, 3, 0, 0, 0),
(16, 2, 4, 3, 3, 0, 0, 0),
(17, 3, 5, 3, 3, 0, 0, 0),
(18, 4, 6, 3, 3, 0, 0, 0),
(19, 1, 1, 4, 4, 4, 4, 4),
(20, 1, 2, 4, 4, 0, 0, 0),
(21, 1, 3, 4, 4, 0, 0, 0),
(22, 2, 4, 4, 4, 0, 0, 0),
(23, 3, 5, 4, 4, 0, 0, 0),
(24, 4, 6, 4, 4, 0, 0, 0),
(25, 1, 1, 5, 5, 5, 5, 5),
(26, 1, 2, 5, 5, 0, 0, 0),
(27, 1, 3, 5, 5, 0, 0, 0),
(28, 2, 4, 5, 5, 0, 0, 0),
(29, 3, 5, 5, 5, 0, 0, 0),
(30, 4, 6, 5, 5, 0, 0, 0),
(31, 1, 1, 6, 6, 6, 6, 6),
(32, 1, 2, 6, 6, 0, 0, 0),
(33, 1, 3, 6, 6, 0, 0, 0),
(34, 2, 4, 6, 6, 0, 0, 0),
(35, 3, 5, 6, 6, 0, 0, 0),
(36, 4, 6, 6, 6, 0, 0, 0),
(37, 1, 1, 7, 7, 7, 7, 7),
(38, 1, 2, 7, 7, 0, 0, 0),
(39, 1, 3, 7, 7, 0, 0, 0),
(40, 2, 4, 7, 7, 0, 0, 0),
(41, 3, 5, 7, 7, 0, 0, 0),
(42, 4, 6, 7, 7, 0, 0, 0),
(43, 1, 1, 9, 9, 9, 9, 9),
(44, 1, 2, 9, 9, 0, 0, 0),
(45, 1, 3, 9, 9, 0, 0, 0),
(46, 2, 4, 9, 9, 0, 0, 0),
(47, 3, 5, 9, 9, 0, 0, 0),
(48, 4, 6, 9, 9, 0, 0, 0),
(49, 1, 1, 10, 10, 10, 10, 10),
(50, 1, 2, 10, 10, 0, 0, 0),
(51, 1, 3, 10, 10, 0, 0, 0),
(52, 2, 4, 10, 10, 0, 0, 0),
(53, 3, 5, 10, 10, 0, 0, 0),
(54, 4, 6, 10, 10, 0, 0, 0),
(55, 1, 1, 11, 11, 11, 11, 11),
(56, 1, 2, 11, 11, 0, 0, 0),
(57, 1, 3, 11, 11, 0, 0, 0),
(58, 2, 4, 11, 11, 0, 0, 0),
(59, 3, 5, 11, 11, 0, 0, 0),
(60, 4, 6, 11, 11, 0, 0, 0),
(61, 1, 1, 12, 12, 12, 12, 12),
(62, 1, 2, 12, 12, 0, 0, 0),
(63, 1, 3, 12, 12, 0, 0, 0),
(64, 2, 4, 12, 12, 0, 0, 0),
(65, 3, 5, 12, 12, 0, 0, 0),
(66, 4, 6, 12, 12, 0, 0, 0),
(67, 1, 1, 13, 13, 13, 13, 13),
(68, 1, 2, 13, 13, 0, 0, 0),
(69, 1, 3, 13, 13, 0, 0, 0),
(70, 2, 4, 13, 13, 0, 0, 0),
(71, 3, 5, 13, 13, 0, 0, 0),
(72, 4, 6, 13, 13, 0, 0, 0),
(73, 1, 1, 14, 14, 14, 14, 14),
(74, 1, 2, 14, 14, 0, 0, 0),
(75, 1, 3, 14, 14, 0, 0, 0),
(76, 2, 4, 14, 14, 0, 0, 0),
(77, 3, 5, 14, 14, 0, 0, 0),
(78, 4, 6, 14, 14, 0, 0, 0),
(79, 1, 1, 15, 15, 15, 15, 15),
(80, 1, 2, 15, 15, 0, 0, 0),
(81, 1, 3, 15, 15, 0, 0, 0),
(82, 2, 4, 15, 15, 0, 0, 0),
(83, 3, 5, 15, 15, 0, 0, 0),
(84, 4, 6, 15, 15, 0, 0, 0),
(85, 1, 1, 16, 16, 16, 16, 16),
(86, 1, 2, 16, 16, 0, 0, 0),
(87, 1, 3, 16, 16, 0, 0, 0),
(88, 2, 4, 16, 16, 0, 0, 0),
(89, 3, 5, 16, 16, 0, 0, 0),
(90, 4, 6, 16, 16, 0, 0, 0),
(91, 1, 1, 17, 17, 17, 17, 17),
(92, 1, 2, 17, 17, 0, 0, 0),
(93, 1, 3, 17, 17, 0, 0, 0),
(94, 2, 4, 17, 17, 0, 0, 0),
(95, 3, 5, 17, 17, 0, 0, 0),
(96, 4, 6, 17, 17, 0, 0, 0),
(97, 1, 1, 18, 18, 18, 18, 18),
(98, 1, 2, 18, 18, 0, 0, 0),
(99, 1, 3, 18, 18, 0, 0, 0),
(100, 2, 4, 18, 18, 0, 0, 0),
(101, 3, 5, 18, 18, 0, 0, 0),
(102, 4, 6, 18, 18, 0, 0, 0),
(103, 1, 1, 19, 19, 19, 19, 19),
(104, 1, 2, 19, 19, 0, 0, 0),
(105, 1, 3, 19, 19, 0, 0, 0),
(106, 2, 4, 19, 19, 0, 0, 0),
(107, 3, 5, 19, 19, 0, 0, 0),
(108, 4, 6, 19, 19, 0, 0, 0),
(109, 1, 1, 20, 20, 20, 20, 20),
(110, 1, 2, 20, 20, 0, 0, 0),
(111, 1, 3, 20, 20, 0, 0, 0),
(112, 2, 4, 20, 20, 0, 0, 0),
(113, 3, 5, 20, 20, 0, 0, 0),
(114, 4, 6, 20, 20, 0, 0, 0),
(115, 1, 1, 21, 21, 21, 21, 21),
(116, 1, 2, 21, 21, 0, 0, 0),
(117, 1, 3, 21, 21, 0, 0, 0),
(118, 2, 4, 21, 21, 0, 0, 0),
(119, 3, 5, 21, 21, 0, 0, 0),
(120, 4, 6, 21, 21, 0, 0, 0),
(121, 1, 1, 22, 22, 22, 22, 22),
(122, 1, 2, 22, 22, 0, 0, 0),
(123, 1, 3, 22, 22, 0, 0, 0),
(124, 2, 4, 22, 22, 0, 0, 0),
(125, 3, 5, 22, 22, 0, 0, 0),
(126, 4, 6, 22, 22, 0, 0, 0),
(127, 1, 1, 23, 23, 23, 23, 23),
(128, 1, 2, 23, 23, 0, 0, 0),
(129, 1, 3, 23, 23, 0, 0, 0),
(130, 2, 4, 23, 23, 0, 0, 0),
(131, 3, 5, 23, 23, 0, 0, 0),
(132, 4, 6, 23, 23, 0, 0, 0),
(133, 1, 1, 24, 24, 24, 24, 24),
(134, 1, 2, 24, 24, 0, 0, 0),
(135, 1, 3, 24, 24, 0, 0, 0),
(136, 2, 4, 24, 24, 0, 0, 0),
(137, 3, 5, 24, 24, 0, 0, 0),
(138, 4, 6, 24, 24, 0, 0, 0),
(139, 1, 1, 25, 25, 25, 25, 25),
(140, 1, 2, 25, 25, 0, 0, 0),
(141, 1, 3, 25, 25, 0, 0, 0),
(142, 2, 4, 25, 25, 0, 0, 0),
(143, 3, 5, 25, 25, 0, 0, 0),
(144, 4, 6, 25, 25, 0, 0, 0),
(145, 1, 1, 27, 27, 27, 27, 27),
(146, 1, 2, 27, 27, 0, 0, 0),
(147, 1, 3, 27, 27, 0, 0, 0),
(148, 2, 4, 27, 27, 0, 0, 0),
(149, 3, 5, 27, 27, 0, 0, 0),
(150, 4, 6, 27, 27, 0, 0, 0),
(151, 1, 1, 28, 28, 28, 28, 28),
(152, 1, 2, 28, 28, 0, 0, 0),
(153, 1, 3, 28, 28, 0, 0, 0),
(154, 2, 4, 28, 28, 0, 0, 0),
(155, 3, 5, 28, 28, 0, 0, 0),
(156, 4, 6, 28, 28, 0, 0, 0),
(157, 1, 1, 29, 29, 29, 29, 29),
(158, 1, 2, 29, 29, 0, 0, 0),
(159, 1, 3, 29, 29, 0, 0, 0),
(160, 2, 4, 29, 29, 0, 0, 0),
(161, 3, 5, 29, 29, 0, 0, 0),
(162, 4, 6, 29, 29, 0, 0, 0),
(163, 1, 1, 30, 30, 30, 30, 30),
(164, 1, 2, 30, 30, 0, 0, 0),
(165, 1, 3, 30, 30, 0, 0, 0),
(166, 2, 4, 30, 30, 0, 0, 0),
(167, 3, 5, 30, 30, 0, 0, 0),
(168, 4, 6, 30, 30, 0, 0, 0),
(169, 1, 1, 31, 31, 31, 31, 31),
(170, 1, 2, 31, 31, 0, 0, 0),
(171, 1, 3, 31, 31, 0, 0, 0),
(172, 2, 4, 31, 31, 0, 0, 0),
(173, 3, 5, 31, 31, 0, 0, 0),
(174, 4, 6, 31, 31, 0, 0, 0),
(175, 1, 1, 32, 32, 32, 32, 32),
(176, 1, 2, 32, 32, 0, 0, 0),
(177, 1, 3, 32, 32, 0, 0, 0),
(178, 2, 4, 32, 32, 0, 0, 0),
(179, 3, 5, 32, 32, 0, 0, 0),
(180, 4, 6, 32, 32, 0, 0, 0),
(181, 1, 1, 33, 33, 33, 33, 33),
(182, 1, 2, 33, 33, 0, 0, 0),
(183, 1, 3, 33, 33, 0, 0, 0),
(184, 2, 4, 33, 33, 0, 0, 0),
(185, 3, 5, 33, 33, 0, 0, 0),
(186, 4, 6, 33, 33, 0, 0, 0),
(187, 1, 1, 34, 34, 34, 34, 34),
(188, 1, 2, 34, 34, 0, 0, 0),
(189, 1, 3, 34, 34, 0, 0, 0),
(190, 2, 4, 34, 34, 0, 0, 0),
(191, 3, 5, 34, 34, 0, 0, 0),
(192, 4, 6, 34, 34, 0, 0, 0),
(193, 1, 1, 35, 35, 35, 35, 35),
(194, 1, 2, 35, 35, 0, 0, 0),
(195, 1, 3, 35, 35, 0, 0, 0),
(196, 2, 4, 35, 35, 0, 0, 0),
(197, 3, 5, 35, 35, 0, 0, 0),
(198, 4, 6, 35, 35, 0, 0, 0),
(199, 1, 1, 36, 36, 36, 36, 36),
(200, 1, 2, 36, 36, 0, 0, 0),
(201, 1, 3, 36, 36, 0, 0, 0),
(202, 2, 4, 36, 36, 0, 0, 0),
(203, 3, 5, 36, 36, 0, 0, 0),
(204, 4, 6, 36, 36, 0, 0, 0),
(205, 1, 1, 41, 41, 41, 41, 41),
(206, 1, 2, 41, 41, 0, 0, 0),
(207, 1, 3, 41, 41, 0, 0, 0),
(208, 2, 4, 41, 41, 0, 0, 0),
(209, 3, 5, 41, 41, 0, 0, 0),
(210, 4, 6, 41, 41, 0, 0, 0),
(211, 1, 1, 42, 42, 42, 42, 42),
(212, 1, 2, 42, 42, 0, 0, 0),
(213, 1, 3, 42, 42, 0, 0, 0),
(214, 2, 4, 42, 42, 0, 0, 0),
(215, 3, 5, 42, 42, 0, 0, 0),
(216, 4, 6, 42, 42, 0, 0, 0),
(217, 1, 1, 43, 43, 43, 43, 43),
(218, 1, 2, 43, 43, 0, 0, 0),
(219, 1, 3, 43, 43, 0, 0, 0),
(220, 2, 4, 43, 43, 0, 0, 0),
(221, 3, 5, 43, 43, 0, 0, 0),
(222, 4, 6, 43, 43, 0, 0, 0),
(223, 1, 1, 44, 44, 44, 44, 44),
(224, 1, 2, 44, 44, 0, 0, 0),
(225, 1, 3, 44, 44, 0, 0, 0),
(226, 2, 4, 44, 44, 0, 0, 0),
(227, 3, 5, 44, 44, 0, 0, 0),
(228, 4, 6, 44, 44, 0, 0, 0),
(229, 1, 1, 46, 46, 46, 46, 46),
(230, 1, 2, 46, 46, 0, 0, 0),
(231, 1, 3, 46, 46, 0, 0, 0),
(232, 2, 4, 46, 46, 0, 0, 0),
(233, 3, 5, 46, 46, 0, 0, 0),
(234, 4, 6, 46, 46, 0, 0, 0),
(235, 1, 1, 47, 47, 47, 47, 47),
(236, 1, 2, 47, 47, 0, 0, 0),
(237, 1, 3, 47, 47, 0, 0, 0),
(238, 2, 4, 47, 47, 0, 0, 0),
(239, 3, 5, 47, 47, 0, 0, 0),
(240, 4, 6, 47, 47, 0, 0, 0),
(241, 1, 1, 49, 49, 49, 49, 49),
(242, 1, 2, 49, 49, 0, 0, 0),
(243, 1, 3, 49, 49, 0, 0, 0),
(244, 2, 4, 49, 49, 0, 0, 0),
(245, 3, 5, 49, 49, 0, 0, 0),
(246, 4, 6, 49, 49, 0, 0, 0),
(247, 1, 1, 51, 51, 51, 51, 51),
(248, 1, 2, 51, 51, 0, 0, 0),
(249, 1, 3, 51, 51, 0, 0, 0),
(250, 2, 4, 51, 51, 0, 0, 0),
(251, 3, 5, 51, 51, 0, 0, 0),
(252, 4, 6, 51, 51, 0, 0, 0),
(253, 1, 1, 71, 71, 71, 71, 71),
(254, 1, 2, 71, 71, 0, 0, 0),
(255, 1, 3, 71, 71, 0, 0, 0),
(256, 2, 4, 71, 71, 0, 0, 0),
(257, 3, 5, 71, 71, 0, 0, 0),
(258, 4, 6, 71, 71, 0, 0, 0),
(259, 1, 1, 101, 101, 101, 101, 101),
(260, 1, 2, 101, 101, 0, 0, 0),
(261, 1, 3, 101, 101, 0, 0, 0),
(262, 2, 4, 101, 101, 0, 0, 0),
(263, 3, 5, 101, 101, 0, 0, 0),
(264, 4, 6, 101, 101, 0, 0, 0),
(265, 1, 1, 102, 102, 102, 102, 102),
(266, 1, 2, 102, 102, 0, 0, 0),
(267, 1, 3, 102, 102, 0, 0, 0),
(268, 2, 4, 102, 102, 0, 0, 0),
(269, 3, 5, 102, 102, 0, 0, 0),
(270, 4, 6, 102, 102, 0, 0, 0),
(271, 1, 1, 111, 111, 111, 111, 111),
(272, 1, 2, 111, 111, 0, 0, 0),
(273, 1, 3, 111, 111, 0, 0, 0),
(274, 2, 4, 111, 111, 0, 0, 0),
(275, 3, 5, 111, 111, 0, 0, 0),
(276, 4, 6, 111, 111, 0, 0, 0),
(277, 1, 1, 112, 112, 112, 112, 112),
(278, 1, 2, 112, 112, 0, 0, 0),
(279, 1, 3, 112, 112, 0, 0, 0),
(280, 2, 4, 112, 112, 0, 0, 0),
(281, 3, 5, 112, 112, 0, 0, 0),
(282, 4, 6, 112, 112, 0, 0, 0),
(283, 1, 1, 113, 113, 113, 113, 113),
(284, 1, 2, 113, 113, 0, 0, 0),
(285, 1, 3, 113, 113, 0, 0, 0),
(286, 2, 4, 113, 113, 0, 0, 0),
(287, 3, 5, 113, 113, 0, 0, 0),
(288, 4, 6, 113, 113, 0, 0, 0),
(289, 1, 1, 114, 114, 114, 114, 114),
(290, 1, 2, 114, 114, 0, 0, 0),
(291, 1, 3, 114, 114, 0, 0, 0),
(292, 2, 4, 114, 114, 0, 0, 0),
(293, 3, 5, 114, 114, 0, 0, 0),
(294, 4, 6, 114, 114, 0, 0, 0),
(295, 1, 1, 115, 115, 115, 115, 115),
(296, 1, 2, 115, 115, 0, 0, 0),
(297, 1, 3, 115, 115, 0, 0, 0),
(298, 2, 4, 115, 115, 0, 0, 0),
(299, 3, 5, 115, 115, 0, 0, 0),
(300, 4, 6, 115, 115, 0, 0, 0),
(301, 1, 1, 116, 116, 116, 116, 116),
(302, 1, 2, 116, 116, 0, 0, 0),
(303, 1, 3, 116, 116, 0, 0, 0),
(304, 2, 4, 116, 116, 0, 0, 0),
(305, 3, 5, 116, 116, 0, 0, 0),
(306, 4, 6, 116, 116, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_email_inbox`
--

CREATE TABLE `vipspm_email_inbox` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `to` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `from` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message_body` mediumtext COLLATE utf8_unicode_ci,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inbox' COMMENT 'Inbox, Sent, Draft, Trash',
  `view_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '2' COMMENT '1=Read 2=Unread',
  `favourites` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0=No, 1=Yes',
  `notify_me` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=On, 0=Off',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_email_inbox_attachment`
--

CREATE TABLE `vipspm_email_inbox_attachment` (
  `id` int(10) UNSIGNED NOT NULL,
  `mailbox_id` int(10) UNSIGNED DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_extension` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `file_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_email_template`
--

CREATE TABLE `vipspm_email_template` (
  `id` int(10) UNSIGNED NOT NULL,
  `email_group_id` int(10) UNSIGNED DEFAULT NULL,
  `template_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `template_subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template_body` text COLLATE utf8_unicode_ci,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_email_template`
--

INSERT INTO `vipspm_email_template` (`id`, `email_group_id`, `template_name`, `template_subject`, `template_body`, `type`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Activate Account', 'Activate Account', '<p>Welcome to {SITE_NAME}!</p><p>Thanks for joining {SITE_NAME}. We listed your sign in details below, make sure you keep them safe.</p><p>To verify your email address, please follow this link:<br><big><strong><a href=\"{ACTIVATE_URL}\">Finish your registration...</a></strong></big><br>Link doesn\'t work? Copy the following link to your browser address bar:<br><a href=\"{ACTIVATE_URL}\">{ACTIVATE_URL}</a></p><p><br>Please verify your email within {ACTIVATION_PERIOD} hours, otherwise your registration will become invalid and you will have to register again.<br>Your username: {USERNAME}<br>Your email address: {EMAIL}<br>Your password: {PASSWORD}<br><br>Have fun!<br>The {SITE_NAME} Team</p>', 'activate_account', NULL, NULL, NULL),
(2, 1, 'Change Email', 'Change Email', '<p>New email address on {SITE_NAME}</p><p>You have changed your email address for {SITE_NAME}.<br>Follow this link to confirm your new email address:<br><big><strong><a href=\"{NEW_EMAIL_KEY_URL}\">Confirm your new email</a></strong></big><br>Link doesn\'t work? Copy the following link to your browser address bar:<br><a href=\"{NEW_EMAIL_KEY_URL}\">{NEW_EMAIL_KEY_URL}</a><br><br>Your email address: {NEW_EMAIL}<br><br>You received this email, because it was requested by a <a href=\"{SITE_URL}\">{SITE_NAME}</a> user. If you have received this by mistake, please DO NOT click the confirmation link, and simply delete this email. After a short time, the request will be removed from the system.<br><br>Thank you,<br>The {SITE_NAME} Team</p>', 'change_email', NULL, NULL, NULL),
(3, 1, 'Forgot Password', 'Forgot Password', '<p>Forgot your password, huh? No big deal.<br>To create a new password, just follow this link:<br><br><big><strong><a href=\"{PASS_KEY_URL}\">Create a new password</a></strong></big><br>Link doesn\'t work? Copy the following link to your browser address bar:<br><a href=\"{PASS_KEY_URL}\">{PASS_KEY_URL}</a><br>You received this email, because it was requested by a <a href=\"{SITE_URL}\">{SITE_NAME}</a> user.</p><p>This is part of the procedure to create a new password on the system. If you DID NOT request a new password then please ignore this email and your password will remain the same.</p><p><br>Thank you,<br>The {SITE_NAME} Team</p>', 'forgot_password', NULL, NULL, NULL),
(4, 1, 'Register Email', 'Register Email', '<p>Welcome to {SITE_NAME}</p><p>Thanks for joining {SITE_NAME}. We listed your sign in details below, make sure you keep them safe.<br>To open your {SITE_NAME} homepage, please follow this link:<br><big><strong><a href=\"{SITE_URL}\">{SITE_NAME} Account!</a></strong></big><br>Link doesn\'t work? Copy the following link to your browser address bar:<br><a href=\"{SITE_URL}\">{SITE_URL}</a><br>Your username: {USERNAME}<br>Your email address: {EMAIL}<br>Your password: {PASSWORD}<br>Have fun!<br>The {SITE_NAME} Team.<br>&nbsp;</p>', 'register_email', NULL, NULL, NULL),
(5, 1, 'Reset Password', 'Reset Password', '<p>New password on {SITE_NAME}</p><p>You have changed your password.<br>Please, keep it in your records so you don\'t forget it.</p><p>Your username: {USERNAME}<br>Your email address: {EMAIL}<br>Your new password: {NEW_PASSWORD}<br><br>Thank you,<br>The {SITE_NAME} Team</p>', 'reset_password', NULL, NULL, NULL),
(6, 1, 'Welcome Email', 'Welcome Email', '<p>Hello <strong>{NAME}</strong>,</p><p>Welcome to <strong>{COMPANY_NAME}</strong> .Thanks for joining <strong>{COMPANY_NAME}</strong>.</p><p>We just wanted to say welcome.</p><p>Please contact us if you need any help.</p><p>To verify your email address, please click on below link:<br><div style=\"text-align: center;\"><big><strong><a href=\"{ACTIVATE_URL}\">Finish Your Registration...</a></strong></big></div></p><p><br>Have fun!<br>The <strong>{COMPANY_NAME}</strong> Team.</p>', 'welcome_email', NULL, NULL, NULL),
(7, 1, 'New User Added', 'New User Added #{USER_ID} {USER_NAME}', '<p>Hello <b>{NAME}</b>,</p><p>New user <b>{USER_NAME}</b> has registered as a <b>{ROLE}</b> role.</p><p>You can view his/her profile by logging in to the portal using the below link:</p><p style=\"text-align: center;\"><a href=\"{PROFILE_URL}\"><big><b>View Profile</b></big></a></p><p>Best Regards,<br>The {COMPANY_NAME} Team</p>', 'new_user_added', NULL, NULL, NULL),
(8, 1, 'New Client Added', 'NNew Client Added #{CLIENT_ID} {CLIENT_NAME}', '<p>Hello <b>{NAME}</b>,</p><p>New client <b>{CLIENT_NAME}</b> has registered.</p><p>You can view his/her profile by logging in to the portal using the below link:</p><p style=\"text-align: center;\"><a href=\"{PROFILE_URL}\"><big><b>View Profile</b></big></a></p><p>Best Regards,<br>The {COMPANY_NAME} Team</p>', 'new_client_added', NULL, NULL, NULL),
(9, 1, 'Announcement', 'Announcement', 'Hi <strong>{NAME}</strong>,<div><br></div><div><strong>{TITLE}</strong></div><div><br></div><div><strong>Start Date :</strong> {START_DATE}</div><div><strong>End Date :</strong> {END_DATE}</div><div><br></div><div>{DESCRIPTION}</div><div><br></div><div>Have fun!<br>The&nbsp;<strong>{COMPANY_NAME}</strong>&nbsp;Team.<br></div><div><br></div>', 'announcement', NULL, NULL, NULL),
(10, 2, 'Assigned Project', 'Assigned Project #{PROJECT_ID} {PROJECT_NAME}', '<p>Hi There,</p><p>A<strong> {PROJECT_NAME}</strong> has been assigned by <strong>{ASSIGNED_BY} </strong>to you.You can view this project by logging in to the portal using the link below:<br><br><div style=\"text-align: center;\"><big><a href=\"{PROJECT_URL}\"><strong>View Project</strong></a></big></div><br><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br>Best Regards,<br>The {SITE_NAME} Team</p><p>&nbsp;</p>', 'assigned_project', NULL, NULL, NULL),
(11, 2, 'Notification Client', 'New Project Created #{PROJECT_ID} {PROJECT_NAME}', '<p>Hello <strong>{CLIENT_NAME}</strong>,<br><br>We have created a new project with your account.<br>You can view this project by logging in to the portal using the link below:<br><div style=\"text-align: center;\"><big><a href=\"{PROJECT_URL}\"><strong>View Project</strong></a></big></div></p><p>Project Name: <b>{PROJECT_NAME}</b></p><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><p><br>Best Regards,<br>The {SITE_NAME} Team</p><p>&nbsp;</p>', 'notification_client', NULL, NULL, NULL),
(12, 2, 'Complete Projects', 'Project Completed #{PROJECT_ID} {PROJECT_NAME}', '<p>Hi <strong>{CLIENT_NAME}</strong>,</p><p>Project <strong>{PROJECT_NAME}</strong> &nbsp;has been completed.<br>You can view this project by logging in to the portal using the link below:<br><div style=\"text-align: center;\"><big><a href=\"{PROJECT_URL}\"><strong>View Project</strong></a></big></div><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br><br>Best Regards,<br>The {SITE_NAME} Team</p>', 'complete_projects', NULL, NULL, NULL),
(13, 2, 'Project Update', 'Project Updated #{PROJECT_ID} {PROJECT_NAME}', '<p>Hi There,</p><p>The Project <b> {PROJECT_NAME}</b> has been updated by <b>{UPDATED_BY} </b>.You can view this project by logging in to the portal using the below button:</p><div style=\"text-align: center;\"><big><a href=\"{PROJECT_URL}\"><b>View Project</b></a></big></div><br><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br>Best Regards,<br>The {SITE_NAME} Team</p><p>&nbsp;</p>', 'project_update', NULL, NULL, NULL),
(14, 2, 'Project Status Change', 'Status Changed #{PROJECT_ID} {PROJECT_NAME}', '<p>Hi there,</p><p>The Project <strong>{PROJECT_NAME}</strong> &nbsp;has been marked as <b>{STATUS}</b> by <b>{MARKED_BY}</b>.</p><p>You can view the project by logging into your portal Account:</p><p><div style=\"text-align: center;\"><b><a href=\"{PROJECT_URL}\">View Project</a></b></div></p><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br>Best Regards,<br />{COMPANY_NAME} Team', 'project_status_change', NULL, NULL, NULL),
(15, 2, 'Project Comments', 'New Project Comment Received', '<p>Hi There,</p><p>A new comment has been posted by <strong>{POSTED_BY}</strong> to project <strong>{PROJECT_NAME}</strong>.</p><p>You can view the comment using the link below:<br /><big><a href=\"{COMMENT_URL}\"><strong>View Project</strong></a></big><br /><br /><em>{COMMENT_MESSAGE}</em><br /><br />Best Regards,<br />The {SITE_NAME} Team</p>', 'project_comments', NULL, NULL, NULL),
(16, 2, 'Project Attachment', 'New Project Attachment', '<p>Hi There,</p><p>A new file has been uploaded by <strong>{UPLOADED_BY}</strong> to project <strong>{PROJECT_NAME}</strong>.<br />You can view the Project using the link below:<br><br><big><a href=\"{PROJECT_URL}\"><strong>View Project</strong></a></big><br /><br />Best Regards,<br />The {SITE_NAME} Team</p>', 'project_attachment', NULL, NULL, NULL),
(17, 2, 'Project Reminder', 'Reminder #{PROJECT_ID} {PROJECT_NAME}', '<p>Hello {NAME},</p><p>The Project <b>{PROJECT_NAME}</b> assigned to you will be start on <b>{START_DATE}</b>.</p><p>You can view this project by logging in to the portal using the below link:</p><p></p><div style=\"text-align: center;\"><b><a href=\"{PROJECT_URL}\">View Project</a></b></div><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><p></p><p>Best Regards,<br>{COMPANY_NAME} Team</p>', 'project_reminder', NULL, NULL, NULL),
(18, 2, 'Project Overdue Reminder', 'Project Overdue Reminder #{PROJECT_ID} {PROJECT_NAME}', '<p><b>Hello {NAME},</b></p><p>Hope You Are Well!<br></p><p>This is just to remind you that the project <b>{PROJECT_NAME}</b> is delayed by <b>{END_DATE}</b>. Its taking longer than expected,<br>Please review activities by login into portal using the link below:<br><big><p style=\"text-align: center;\"><a href=\"{PROJECT_URL}\"><strong>View Project</strong></a></p></big><br><p>Start Date: <b>{START_DATE}</b><br>Description: {DESCRIPTION}</p><br><p>Best Regards,<br>{COMPANY_NAME} Team</p>', 'project_overdue', NULL, NULL, NULL),
(19, 3, 'Task Assigned', 'Task Assigned #{TASK_ID} {TASK_NAME}', '<p>Hi there,</p><p>A new task <strong>{TASK_NAME}</strong> &nbsp;has been assigned to <strong>{ASSIGNED_TO}</strong> by <strong>{ASSIGNED_BY}</strong>.</p><p>You can view this task by logging in to the portal using the link below.</p><p><div style=\"text-align: center;\"><big><strong><a href=\"{TASK_URL}\">View Task</a></strong></big></div><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br>Best Regards,<br>The {COMPANY_NAME} Team</p>', 'task_assigned', NULL, NULL, NULL),
(20, 3, 'Task Updated', 'Task Updated #{TASK_ID} {TASK_NAME}', '<p>Hi there,</p><p><strong>{TASK_NAME}</strong> has been updated by <strong>{UPDATED_BY}</strong>.</p><p>You can view this Task by logging in to the portal using the link below.</p><p><br><div style=\"text-align: center;\"><big><strong><a href=\"{TASK_URL}\">View Task</a></strong></big></div><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br>Best Regards,<br>The {COMPANY_NAME} Team</p>', 'task_updated', NULL, NULL, NULL),
(21, 3, 'Task Status Change', 'Status Changed #{TASK_ID} {TASK_NAME}', '<p>Hi there,</p><p>The task <b>{TASK_NAME}</b> has been marked as <b>{STATUS}</b> by <b>{MARKED_BY}</b>.</p><p>You can view this task by logging in to the portal using the below button:</p><p><div style=\"text-align: center;\"><b><a href=\"{TASK_URL}\">View Task</a></b></div></p><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><p>Best Regards,<br>{COMPANY_NAME} Team</p>', 'task_status_change', NULL, NULL, NULL),
(22, 3, 'Task Reminder', 'Reminder #{TASK_ID} {TASK_NAME}', '<p>Hello {NAME},</p><p>The Task <b>{TASK_NAME}</b> assigned to you will be start on <b>{START_DATE}</b>.</p><p>You can view this task by logging in to the portal using the below button:</p><p></p><div style=\\\"text-align: center;\\\"><b><a href=\\\"{TASK_URL}\\\">View Task</a></b></div><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><p></p><p>Best Regards,<br>{COMPANY_NAME} Team</p>', 'task_reminder', NULL, NULL, NULL),
(23, 3, 'Task Overdue Reminder', 'Task Overdue Reminder #{TASK_ID} {TASK_NAME}', '<p><b>Hello {NAME},</b></p><p>Hope You Are Well!<br></p><p>This is just to remind you that the task <b>{TASK_NAME}</b> is delayed by <b>{END_DATE}</b>. Its taking longer than expected,<br>Please review activities by login into portal using the link below:<br><big><p style=\"text-align: center;\"><a href=\"{TASK_URL}\"><strong>View Task</strong></a></p></big><br><p>Start Date: <b>{START_DATE}</b><br>Description: {DESCRIPTION}</p><br><p>Best Regards,<br>{COMPANY_NAME} Team</p>', 'task_overdue', NULL, NULL, NULL),
(24, 3, 'Task Comments', 'New Task Comment Received', '<p>Hi There,</p><p>A new comment has been posted by <strong>{POSTED_BY}</strong> to <strong>{TASK_NAME}</strong>.</p><p>You can view the comment using the link below:<br /><big><strong><a href=\"{COMMENT_URL}\">View Comment</a></strong></big><br /><br /><em>{COMMENT_MESSAGE}</em><br /><br />Best Regards,<br />The {SITE_NAME} Team</p>', 'task_comments', NULL, NULL, NULL),
(25, 3, 'Tasks Attachment', 'New Tasks Attachment', '<p>Hi There,</p><p>A new file has been uploaded by <strong>{UPLOADED_BY} </strong>to Task <strong>{TASK_NAME}</strong>.<br>You can view the Task&nbsp;using the link below:</p><p><br><big><a href=\"{TASK_URL}\"><strong>View Task</strong></a></big><br><br>Best Regards,<br>The {SITE_NAME} Team</p>', 'task_attachment', NULL, NULL, NULL),
(26, 4, 'Defect Assigned', 'Defect Assigned #{DEFECT_ID} {DEFECT_NAME}', '<p>Hi there,</p><p>A new defect {DEFECT_NAME} has been assigned to <strong>{ASSIGNED_TO}</strong> by <strong>{ASSIGNED_BY}</strong>.</p><p>You can view this defect by logging in to the portal using the link below.</p><p><br><div style=\"text-align: center;\"><b><a href=\"{DEFECT_URL}\">View Defect</a></b></div><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br><br>Best Regards,<br>The {COMPANY_NAME} Team</p>', 'defect_assigned', NULL, NULL, NULL),
(27, 4, 'Defect Updated', 'Defect Updated #{DEFECT_ID} {DEFECT_NAME}', '<p>Hi there,</p><p><strong>{DEFECT_NAME}</strong> has been updated by <strong>{UPDATED_BY}</strong>.</p><p>You can view this Defect by logging in to the portal using the link below.</p><p><br><div style=\"text-align: center;\"><big><strong><a href=\"{DEFECT_URL}\">View Defect</a></strong></big></div><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br>Best Regards,<br>The {COMPANY_NAME} Team</p>', 'defect_updated', NULL, NULL, NULL),
(28, 4, 'Defect Status Changed', 'Status Changed #{DEFECT_ID} {DEFECT_NAME}', '<p>Hi there,</p><p>Defect <b>{DEFECT_NAME}</b> has been marked as <b>{STATUS}</b> by <b>{MARKED_BY}</b>.</p><p>You can view this defect by logging in to the portal using the link below.</p><p><div style=\"text-align: center;\"><b><a href=\"{DEFECT_URL}\">View Defect</a></b></div></p><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><p><br>Best Regards,<br>The {COMPANY_NAME} Team</p>', 'defect_status_changed', NULL, NULL, NULL),
(29, 4, 'Defect Reminder', 'Reminder #{DEFECT_ID} {DEFECT_NAME}', '<p>Hello {NAME},</p><p>The Defect <b>{DEFECT_NAME}</b> assigned to you will be start on <b>{START_DATE}</b>.</p><p>You can view this defect by logging in to the portal using the below button:</p><br><div style=\"text-align: center;\"><b><a href=\"{DEFECT_URL}\">View Defect</a></b></div><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br><p>Best Regards,<br>{COMPANY_NAME} Team</p>', 'defect_reminder', NULL, NULL, NULL),
(30, 4, 'Defect Overdue Reminder', 'Defect Overdue Reminder #{DEFECT_ID} {DEFECT_NAME}', '<p><b>Hello {NAME},</b></p><p>Hope You Are Well!<br></p><p>This is just to remind you that the defect <b>{DEFECT_NAME}</b> is delayed by <b>{END_DATE}</b>. Its taking longer than expected,<br>Please review activities by login into portal using the link below:<br><big><p style=\"text-align: center;\"><a href=\"{DEFECT_URL}\"><strong>View Defect</strong></a></p></big><br><p>Start Date: <b>{START_DATE}</b><br>Description: {DESCRIPTION}</p><br><p>Best Regards,<br>{COMPANY_NAME} Team</p>', 'defect_overdue', NULL, NULL, NULL),
(31, 4, 'Defect Comments', 'New Defect Comment Received', '<p>Hi there,</p><p>A new comment has been posted by {POSTED_BY} to defect {DEFECT_TITLE}.</p><p>You can view the comment using the link below.</p><p><em>{COMMENT_MESSAGE}</em></p><p><br /><big><strong><a href=\"{COMMENT_URL}\">View Comment</a></strong></big><br><br>Best Regards,<br />The {SITE_NAME} Team</p><p>&nbsp;</p>', 'defect_comments', NULL, NULL, NULL),
(32, 4, 'Defect Attachment', 'New Defect Attachment', '<p>Hi there,</p><p>A new attachment&nbsp;has been uploaded by {UPLOADED_BY} to issue {DEFECT_TITLE}.</p><p>You can view the defect using the link below.</p><p><br /><big><strong><a href=\"{DEFECT_URL}\">View Defect</a></strong></big></p><p><br />Best Regards,<br />The {SITE_NAME} Team</p>', 'defect_attachment', NULL, NULL, NULL),
(33, 5, 'Incident Assigned', 'Incident Assigned #{INCIDENT_ID} {INCIDENT_NAME}', '<p>Hi there,</p><p>A new incident <strong>{INCIDENT_NAME}</strong> has been assigned to <strong>{ASSIGNED_TO}</strong> by <strong>{ASSIGNED_BY}</strong>.</p><p>You can view this incident by logging in to the portal using the link below.</p><div style=\"text-align: center;\"><strong><a href=\"{INCIDENT_URL}\">View Incident</a></strong></div><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><p><br>Best Regards,<br>The {COMPANY_NAME} Team</p>', 'incident_assigned', NULL, NULL, NULL),
(34, 5, 'Incident Updated', 'Incident Updated #{INCIDENT_ID} {INCIDENT_NAME}', '<p>Hi there,</p><p><strong>{INCIDENT_NAME}</strong> has been updated by <strong>{UPDATED_BY}</strong>.</p><p>You can view this Incident by logging in to the portal using the link below.</p><p><br><div style=\"text-align: center;\"><big><strong><a href=\"{INCIDENT_URL}\">View Incident</a></strong></big></div><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br>Best Regards,<br>The {COMPANY_NAME} Team</p>', 'incident_updated', NULL, NULL, NULL),
(35, 5, 'Incident Status Changed', 'Status Changed #{INCIDENT_ID} {INCIDENT_NAME}', '<p>Hi there,</p><p>Incident <b>{INCIDENT_NAME}</b> has been marked as <b>{STATUS}</b> by <b>{MARKED_BY}</b>.</p><p>You can view this incident by logging in to the portal using the link below.</p><p><div style=\"text-align: center;\"><b><a href=\"{INCIDENT_URL}\">View Incident</a></b></div></p><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><p><br>Best Regards,<br>The {COMPANY_NAME} Team</p>', 'incident_status_changed', NULL, NULL, NULL),
(36, 5, 'Incident Reminder', 'Reminder #{INCIDENT_ID} {INCIDENT_NAME}', '<p>Hello {NAME},</p><p>The Incident <b>{INCIDENT_NAME}</b> assigned to you will be start on <b>{START_DATE}</b>.</p><p>You can view this incident by logging in to the portal using the below button:</p><br><div style=\"text-align: center;\"><b><a href=\"{INCIDENT_URL}\">View Incident</a></b></div><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br><p>Best Regards,<br>{COMPANY_NAME} Team</p>', 'incident_reminder', NULL, NULL, NULL),
(37, 5, 'Incident Overdue Reminder', 'Incident Overdue Reminder #{INCIDENT_ID} {INCIDENT_NAME}', '<p><b>Hello {NAME},</b></p><p>Hope You Are Well!<br></p><p>This is just to remind you that the incident <b>{INCIDENT_NAME}</b> is delayed by <b>{END_DATE}</b>. Its taking longer than expected,<br>Please review activities by login into portal using the link below:<br><big><p style=\"text-align: center;\"><a href=\"{INCIDENT_URL}\"><strong>View Incident</strong></a></p></big><br><p>Start Date: <b>{START_DATE}</b><br />Description: {DESCRIPTION}</p><br><p>Best Regards,<br>{COMPANY_NAME} Team</p>', 'incident_overdue', NULL, NULL, NULL),
(38, 5, 'Incident Comments', 'New Incident Comment Received', '<p>Hi there,</p><p>A new comment has been posted by {POSTED_BY} to incident {INCIDENT_TITLE}.</p><p>You can view the comment using the link below.</p><p><em>{COMMENT_MESSAGE}</em></p><p><br><big><strong><a href=\"{COMMENT_URL}\">View Comment</a></strong></big><br><br>Best Regards,<br>The {SITE_NAME} Team</p><p>&nbsp;</p>', 'incident_comments', NULL, NULL, NULL),
(39, 5, 'Incident Attachment', 'New Incident Attachment', '<p>Hi there,</p><p>A new attachment&nbsp;has been uploaded by {UPLOADED_BY} to issue {INCIDENT_TITLE}.</p><p>You can view the incident using the link below.</p><p><br><big><strong><a href=\"{INCIDENT_URL}\">View Incident</a></strong></big></p><p><br>Best Regards,<br>The {SITE_NAME} Team</p>', 'incident_attachment', NULL, NULL, NULL),
(40, 6, 'Appointment Mail', 'Appointment #{APPOINTMENT_ID} {APPOINTMENT_TITLE}', 'Hi <strong>{NAME}</strong>,<br><div>A appointment has been <b>{STATUS}</b> on <strong>{APPOINTEMENT_DATE}</strong>, in the <strong>{LOCATION}</strong> at <strong>{APPOINTEMENT_TIME}</strong>.</div><br><div>End Time: <b>{END_TIME}</b></div><div>Description: {DESCRIPTION}</div><br><div>Have fun!<br>The <strong>{COMPANY_NAME}</strong> Team.<br></div>', 'appointment_mail', NULL, NULL, NULL),
(41, 6, 'Appointment Reminder', 'Reminder #{APPOINTMENT_ID} {APPOINTMENT_TITLE}', '<<p>Hello <b>{NAME}</b>,</p><p>This is <b>{COMPANY_NAME}</b> reminding you for your appointment on <b>{DATE}</b> at <b>{TIME}</b>. Call/text us if you need to reschedule. See you then!</b></p><br><div>End Time: <b>{END_TIME}</b></div><div>Description: {DESCRIPTION}</div><br><p>Best Regards,<br>{COMPANY_NAME} Team</p>', 'appointment_reminder', NULL, NULL, NULL),
(42, 7, 'Leave Request', 'Leave Request', 'Hello Sir/Ma\'am,<br><br>I am writing to request for a {LEAVE_TYPE} Leave on {DATE}, due to {REASON}. Kindly grant me the leave.<br><br>Best Regards,<br>{NAME}', 'leave_request', NULL, NULL, NULL),
(43, 7, 'Approve Leave', 'Approval Letter for Leave Application', 'Hello {NAME},<br><br>We have gone through your request for your {LEAVE_TYPE} Leave. We are happy to grant you leave on {DATE}.<br><br>We request you to complete all your pending work so that the company does not face any loss or problem during your absence.<br><br>Have a good time.<br><br>Best Regards,<br>{APPROVED_BY}', 'approve_leave', NULL, NULL, NULL),
(44, 7, 'Reject Leave', 'Rejection Letter for Leave Application', 'Dear {NAME},<br><br>After reviewing your request, I am sorry to say that I am unable to grant you {LEAVE_TYPE} Leave on {DATE}. While {REJECT_REASON}.<br><br>Best Regards,<br>{REJECT_BY}', 'reject_leave', NULL, NULL, NULL),
(45, 7, 'Cancel Leave', 'Request for Cancelling Leave', 'Dear Sir/Ma\'am,<br><br>I am writing to request for the cancellation of my submitted leave for <b>{DATE}</b>. So there will be no effect on my attendance and salary. <br>Sorry for the inconvenience. <br>Thanking you in anticipation.<br><br>Best Regards,<br>{NAME}', 'cancel_leave', NULL, NULL, NULL),
(46, 8, 'Send Estimate to Customer', 'Estimate Created # {ESTIMATE_NUMBER}', 'Dear {CUSTOMER_NAME},<br><br>We have created an estimate for you in the amount of {AMOUNT}.<br><br>Please find the attached estimate <b># {ESTIMATE_NUMBER}</b><br><br>You can view the estimate on the following link: <a href=\"{ESTIMATE_URL}\">{ESTIMATE_NUMBER}</a><br><br>We are looking forward to your reply.<br><br>Best Regards,<br>The {COMPANY_NAME} Team', 'send_estimate', NULL, NULL, NULL),
(47, 8, 'Estimate Declined', 'Customer Declined Estimate # {ESTIMATE_NUMBER}', 'Hi,<br><br>{CUSTOMER_NAME} declined the estimate <b># {ESTIMATE_NUMBER}</b><br><br>You can view the estimate on the following link: <a href=\"{ESTIMATE_URL}\">{ESTIMATE_NUMBER}</a><br><br>{CUSTOMER_NAME}', 'estimate_declined', NULL, NULL, NULL),
(48, 8, 'Estimate Accepted', 'Customer Accepted Estimate # {ESTIMATE_NUMBER}', 'Hi,<br><br>{CUSTOMER_NAME} accepted the estimate <b># {ESTIMATE_NUMBER}</b><br><br>You can view the estimate on the following link: <a href=\"{ESTIMATE_URL}\">{ESTIMATE_NUMBER}</a><br><br>{CUSTOMER_NAME}', 'estimate_accepted', NULL, NULL, NULL),
(49, 8, 'Thank You Email', 'Thank you for accepting estimate # {ESTIMATE_NUMBER}', 'Dear {CUSTOMER_NAME},<br><br>Thank you for accepting the estimate <b># {ESTIMATE_NUMBER}</b><br><br>We are looking forward to doing business with you.<br><br>We will contact you as soon as possible.<br><br>Best Regards,<br>The {COMPANY_NAME} Team', 'thank_you_for_accepting_estimate', NULL, NULL, NULL),
(50, 8, 'Estimate Expiration Reminder', 'Estimate Expiration Reminder # {ESTIMATE_NUMBER}', 'Hello {CUSTOMER_NAME},<br><br>The estimate <b># {ESTIMATE_NUMBER}</b> will expire on <b>{ESTIMATE_VALID_TILL}</b><br><br>You can view the estimate on the following link: <a href=\"{ESTIMATE_URL}\">{ESTIMATE_NUMBER}</a><br><br>Best Regards,<br>The {COMPANY_NAME} Team', 'estimate_expiration_reminder', NULL, NULL, NULL),
(51, 9, 'Send Invoice to Customer', 'Invoice Created # {INVOICE_NUMBER}', 'Dear {CUSTOMER_NAME},<br><br>We have created an invoice for you in the amount of {AMOUNT}.<br><br>Please find the attached invoice <b># {INVOICE_NUMBER}</b><br><br>You can view the invoice on the following link: <a href=\"{INVOICE_URL}\">{INVOICE_NUMBER}</a><br><br>Don’t hesitate to reach out if you have any questions.<br><br>Best Regards,<br>The {COMPANY_NAME} Team', 'send_invoice', NULL, NULL, NULL),
(52, 9, 'Invoice Overdue Notice', 'Invoice Overdue Notice # {INVOICE_NUMBER}', 'Dear {CUSTOMER_NAME},<br><br>I hope you are doing well.<br><br>This is an overdue notice for invoice <b># {INVOICE_NUMBER}</b><br><br>This invoice was due on <b>{DUE_DATE}</b><br><br>You can view the invoice on the following link: <a href=\"{INVOICE_URL}\">{INVOICE_NUMBER}</a><br><br>Please let me know in case of any questions.<br><br>Best Regards,<br>The {COMPANY_NAME} Team', 'invoice_overdue_notice', NULL, NULL, NULL),
(53, 10, 'Payment Added (Sent to Customer)', 'Payment Added Against # {INVOICE_NUMBER}', 'Hello {CUSTOMER_NAME},<br><br>Thank you for the payment.<br><br>Please find the attached payment receipt # <b>{RECEIPT_NUMBER}</b><br><br>You can always view the invoice for this payment at the following link: <a href=\"{INVOICE_URL}\"><b>{INVOICE_NUMBER}</b></a><br><br>We are looking forward working with you.<br><br>Best  Regards,<br>The {COMPANY_NAME} Team', 'payment_added_send_to_customer', NULL, NULL, NULL),
(54, 10, 'Payment Added (Sent to Staff)', 'Payment Added Against # {INVOICE_NUMBER}', 'Hello,<br><br>Customer added payment for invoice # {INVOICE_NUMBER}<br><br>You can view the invoice for this payment at the following link: <a href=\"{INVOICE_URL}\"><b>{INVOICE_NUMBER}</b></a><br><br>{CUSTOMER_NAME}', 'payment_added_send_to_staff', NULL, NULL, NULL),
(55, 11, 'Meeting', 'Meeting #{MEETING_ID} {MEETING_TITLE}', 'Hi <strong>{NAME}</strong>,<div><br></div><div>A meeting has been scheduled on <strong>{MEETING_DATE}</strong>, in the <strong>{LOCATION}</strong> at <strong>{MEETING_TIME}</strong>.</div><div><br></div><div>End Time: <b>{END_TIME}</b></div><div>Description: {DESCRIPTION}</div><div><br></div><div>Have fun!<br>The <strong>{COMPANY_NAME}</strong> Team.<br></div><div><br></div>', 'meeting', NULL, NULL, NULL),
(56, 11, 'Cancel Meeting', 'Meeting Canceled #{MEETING_ID} {MEETING_TITLE}', 'Hi <strong>{NAME}</strong>,<div><br></div><div>The meeting scheduled on <strong>{MEETING_DATE}</strong>, in the <strong>{LOCATION}</strong> at <strong>{MEETING_TIME}</strong> has been canceled.</div><br><div>End Time: <b>{END_TIME}</b></div><div>Description: {DESCRIPTION}</div><br><div>Have fun!<br>The <strong>{COMPANY_NAME}</strong> Team.<br></div>', 'cancel_meeting', NULL, NULL, NULL),
(57, 11, 'Meeting Reminder', 'Reminder #{MEETING_ID} {MEETING_TITLE}', '<p>Hi <b>{NAME}</b>,</p><p>Friendly reminder that we have a meeting booked for <b>{DATE}</b> at <b>{TIME}</b></b>.</p><p>You can view this meeting by logging in to the portal using the below button:</p><p></p><div style=\"text-align: center;\"><b><a href=\"{MEETING_URL}\">View Meeting</a></b></div><div>End Time: <b>{END_TIME}</b><div>Description: {DESCRIPTION}</div><p></p><p>Best Regards,<br>{COMPANY_NAME} Team</p>', 'meeting_reminder', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_email_template_groups`
--

CREATE TABLE `vipspm_email_template_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `email_group_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_email_template_groups`
--

INSERT INTO `vipspm_email_template_groups` (`id`, `email_group_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Account Emails', NULL, NULL, NULL),
(2, 'Project Emails', NULL, NULL, NULL),
(3, 'Task Emails', NULL, NULL, NULL),
(4, 'Defects Email', NULL, NULL, NULL),
(5, 'Incidents Email', NULL, NULL, NULL),
(6, 'Appointment Emails', NULL, NULL, NULL),
(7, 'Leave Emails', NULL, NULL, NULL),
(8, 'Estimate Emails', NULL, NULL, NULL),
(9, 'Invoice Emails', NULL, NULL, NULL),
(10, 'Payment Emails', NULL, NULL, NULL),
(11, 'Meeting Emails', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_estimates`
--

CREATE TABLE `vipspm_estimates` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `estimate_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `estimate_date` date NOT NULL,
  `valid_till` date NOT NULL,
  `sub_total` double(13,2) NOT NULL DEFAULT '0.00',
  `total_tax` double(13,2) NOT NULL DEFAULT '0.00',
  `discount_type` enum('percent','fixed') COLLATE utf8_unicode_ci DEFAULT NULL,
  `discount` double(13,2) NOT NULL DEFAULT '0.00',
  `total_discount` double(13,2) NOT NULL DEFAULT '0.00',
  `adjustment` double(13,2) NOT NULL DEFAULT '0.00',
  `total_amount` double(13,2) NOT NULL DEFAULT '0.00',
  `reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` mediumtext COLLATE utf8_unicode_ci,
  `status` enum('draft','sent','expired','declined','accepted') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_estimate_items`
--

CREATE TABLE `vipspm_estimate_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `estimate_id` int(10) UNSIGNED NOT NULL,
  `item_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_description` mediumtext COLLATE utf8_unicode_ci,
  `item_unit` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit_price` double(13,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_item_amount` double(13,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_estimate_item_taxes`
--

CREATE TABLE `vipspm_estimate_item_taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `estimate_item_id` int(10) UNSIGNED NOT NULL,
  `tax_id` int(10) UNSIGNED NOT NULL,
  `item_tax` double(13,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_estimate_settings`
--

CREATE TABLE `vipspm_estimate_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `estimate_prefix` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'EST-',
  `due_after` int(11) NOT NULL DEFAULT '7',
  `estimate_logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'estimate-1',
  `client_note` mediumtext COLLATE utf8_unicode_ci,
  `terms_conditions` mediumtext COLLATE utf8_unicode_ci,
  `auto_remind` tinyint(1) NOT NULL DEFAULT '0',
  `auto_remind_before` int(11) DEFAULT '2',
  `auto_convert_to_invoice_on_client_accept` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_estimate_settings`
--

INSERT INTO `vipspm_estimate_settings` (`id`, `estimate_prefix`, `due_after`, `estimate_logo`, `template`, `client_note`, `terms_conditions`, `auto_remind`, `auto_remind_before`, `auto_convert_to_invoice_on_client_accept`, `created_at`, `updated_at`) VALUES
(1, 'EST-', 7, NULL, 'estimate-1', NULL, 'Looking forward to doing business with you.', 0, 2, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_files`
--

CREATE TABLE `vipspm_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `folder_id` int(11) NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_extension` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `file_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_folders`
--

CREATE TABLE `vipspm_folders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `folder_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_folder` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_form`
--

CREATE TABLE `vipspm_form` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `table_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_form`
--

INSERT INTO `vipspm_form` (`id`, `name`, `table_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Project', 'vipspm_projects', 1, NULL, NULL),
(2, 'Task', 'vipspm_tasks', 1, NULL, NULL),
(3, 'Defect', 'vipspm_defects', 1, NULL, NULL),
(4, 'Incident', 'vipspm_incidents', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_holidays`
--

CREATE TABLE `vipspm_holidays` (
  `id` int(10) UNSIGNED NOT NULL,
  `event_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci,
  `date` date NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#1ab394',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_incidents`
--

CREATE TABLE `vipspm_incidents` (
  `id` int(10) UNSIGNED NOT NULL,
  `create_user_id` int(11) NOT NULL,
  `generated_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `project_version` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `incident_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=Service Request,2=Info Request',
  `incident_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `estimated_hours` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `actual_hours` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=Open,2=Assigned,3=In Progress,4=Solved,5=Deferred,6=Re-open,7=Closed',
  `assigned_group_id` int(11) DEFAULT NULL,
  `assign_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `priority` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=Low,2=Medium,3=High,4=Very High,5=Urgent',
  `notes` text COLLATE utf8_unicode_ci,
  `orders` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_incident_attachments`
--

CREATE TABLE `vipspm_incident_attachments` (
  `id` int(10) UNSIGNED NOT NULL,
  `incident_id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_extension` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_incident_comments`
--

CREATE TABLE `vipspm_incident_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `incident_id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `attachments` text COLLATE utf8_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_incident_history`
--

CREATE TABLE `vipspm_incident_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `incident_id` int(10) UNSIGNED NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `commented_by_id` int(11) DEFAULT NULL,
  `solved_by_id` int(11) DEFAULT NULL,
  `closed_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_incident_user`
--

CREATE TABLE `vipspm_incident_user` (
  `incident_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_invoices`
--

CREATE TABLE `vipspm_invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `estimate_id` int(11) DEFAULT NULL,
  `invoice_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `sub_total` double(13,2) NOT NULL DEFAULT '0.00',
  `total_tax` double(13,2) NOT NULL DEFAULT '0.00',
  `discount_type` enum('percent','fixed') COLLATE utf8_unicode_ci DEFAULT NULL,
  `discount` double(13,2) NOT NULL DEFAULT '0.00',
  `total_discount` double(13,2) NOT NULL DEFAULT '0.00',
  `adjustment` double(13,2) NOT NULL DEFAULT '0.00',
  `total_amount` double(13,2) NOT NULL DEFAULT '0.00',
  `total_due_amount` double(13,2) NOT NULL DEFAULT '0.00',
  `reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` mediumtext COLLATE utf8_unicode_ci,
  `recurrence` tinyint(1) NOT NULL DEFAULT '0',
  `recurrence_occurrences` int(11) DEFAULT NULL,
  `recurrence_pattern` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_recurrence_generated` tinyint(1) NOT NULL DEFAULT '0',
  `last_overdue_reminder_date` datetime DEFAULT NULL,
  `status` enum('paid','partially_paid','unpaid') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_invoice_items`
--

CREATE TABLE `vipspm_invoice_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `item_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_description` mediumtext COLLATE utf8_unicode_ci,
  `item_unit` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit_price` double(13,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_item_amount` double(13,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_invoice_item_taxes`
--

CREATE TABLE `vipspm_invoice_item_taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `invoice_item_id` int(10) UNSIGNED NOT NULL,
  `tax_id` int(10) UNSIGNED NOT NULL,
  `item_tax` double(13,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_invoice_settings`
--

CREATE TABLE `vipspm_invoice_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `invoice_prefix` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'INV-',
  `due_after` int(11) NOT NULL DEFAULT '30',
  `invoice_logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'invoice-1',
  `gst_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_gst_number` tinyint(1) NOT NULL DEFAULT '0',
  `client_note` mediumtext COLLATE utf8_unicode_ci,
  `terms_conditions` mediumtext COLLATE utf8_unicode_ci,
  `auto_remind` tinyint(1) NOT NULL DEFAULT '0',
  `auto_remind_after` varchar(255) COLLATE utf8_unicode_ci DEFAULT '1,3',
  `payment_prefix` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'PAY-',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_invoice_settings`
--

INSERT INTO `vipspm_invoice_settings` (`id`, `invoice_prefix`, `due_after`, `invoice_logo`, `template`, `gst_number`, `show_gst_number`, `client_note`, `terms_conditions`, `auto_remind`, `auto_remind_after`, `payment_prefix`, `created_at`, `updated_at`) VALUES
(1, 'INV-', 30, NULL, 'invoice-1', NULL, 0, NULL, 'Thank you for your business. Please process this invoice within the due date.', 0, '1,3', 'PAY-', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_items`
--

CREATE TABLE `vipspm_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double(13,2) NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci,
  `unit` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_item_tax`
--

CREATE TABLE `vipspm_item_tax` (
  `item_id` int(10) UNSIGNED NOT NULL,
  `tax_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_knowledge_base_article`
--

CREATE TABLE `vipspm_knowledge_base_article` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `article_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_knowledge_base_category`
--

CREATE TABLE `vipspm_knowledge_base_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category_logo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_languages`
--

CREATE TABLE `vipspm_languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_languages`
--

INSERT INTO `vipspm_languages` (`id`, `code`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ar', 'Arabic', 1, NULL, NULL),
(2, 'cs', 'Czech', 1, NULL, NULL),
(3, 'da', 'Danish', 1, NULL, NULL),
(4, 'de', 'German', 1, NULL, NULL),
(5, 'el', 'Greek', 1, NULL, NULL),
(6, 'en', 'English', 1, NULL, NULL),
(7, 'es', 'Spanish', 1, NULL, NULL),
(8, 'fr', 'French', 1, NULL, NULL),
(9, 'gu', 'Gujarati', 1, NULL, NULL),
(10, 'hi', 'Hindi', 1, NULL, NULL),
(11, 'id', 'Indonesian', 1, NULL, NULL),
(12, 'it', 'Italian', 1, NULL, NULL),
(13, 'ja', 'Japanese', 1, NULL, NULL),
(14, 'nl', 'Dutch', 1, NULL, NULL),
(15, 'no', 'Norwegian', 1, NULL, NULL),
(16, 'pl', 'Polish', 1, NULL, NULL),
(17, 'pt', 'Portuguese', 1, NULL, NULL),
(18, 'ro', 'Romanian', 1, NULL, NULL),
(19, 'ru', 'Russian', 1, NULL, NULL),
(20, 'tr', 'Turkish', 1, NULL, NULL),
(21, 'zh', 'Chinese', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_leaves`
--

CREATE TABLE `vipspm_leaves` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `leave_type_id` int(10) UNSIGNED NOT NULL,
  `duration` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'full, half',
  `duration_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'first_half, second_half',
  `leave_day` double(5,2) NOT NULL DEFAULT '1.00' COMMENT '1 or 0.5',
  `leave_date` date NOT NULL,
  `reason` text COLLATE utf8_unicode_ci,
  `reject_reason` text COLLATE utf8_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=Submitted, 2=Approved, 3=Rejected, 4=Cancel, 5=Taken',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_leave_attachments`
--

CREATE TABLE `vipspm_leave_attachments` (
  `id` int(10) UNSIGNED NOT NULL,
  `leave_id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_leave_types`
--

CREATE TABLE `vipspm_leave_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `leave_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `no_of_leaves` int(11) NOT NULL DEFAULT '5',
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#1ab394',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_leave_types`
--

INSERT INTO `vipspm_leave_types` (`id`, `leave_type`, `no_of_leaves`, `color`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Personal', 5, '#1ab394', NULL, NULL, NULL),
(2, 'Casual', 5, '#f50606', NULL, NULL, NULL),
(3, 'Sick', 5, '#06f509', NULL, NULL, NULL),
(4, 'Maternity', 5, '#0614f5', NULL, NULL, NULL),
(5, 'Earned', 5, '#ee06f5', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_locales`
--

CREATE TABLE `vipspm_locales` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `locale` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_locales`
--

INSERT INTO `vipspm_locales` (`id`, `name`, `locale`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Afghanistan', 'afghanistan', 1, NULL, NULL),
(2, 'Aringland Islands', 'aringland_islands', 1, NULL, NULL),
(3, 'Albania', 'albania', 1, NULL, NULL),
(4, 'Algeria', 'algeria', 1, NULL, NULL),
(5, 'American Samoa', 'american_samoa', 1, NULL, NULL),
(6, 'Andorra', 'andorra', 1, NULL, NULL),
(7, 'Angola', 'angola', 1, NULL, NULL),
(8, 'Anguilla', 'anguilla', 1, NULL, NULL),
(9, 'Antarctica', 'antarctica', 1, NULL, NULL),
(10, 'Antigua and Barbuda', 'antigua_and_barbuda', 1, NULL, NULL),
(11, 'Argentina', 'argentina', 1, NULL, NULL),
(12, 'Armenia', 'armenia', 1, NULL, NULL),
(13, 'Aruba', 'aruba', 1, NULL, NULL),
(14, 'Australia', 'australia', 1, NULL, NULL),
(15, 'Austria', 'austria', 1, NULL, NULL),
(16, 'Azerbaijan', 'azerbaijan', 1, NULL, NULL),
(17, 'Bahamas', 'bahamas', 1, NULL, NULL),
(18, 'Bahrain', 'bahrain', 1, NULL, NULL),
(19, 'Bangladesh', 'bangladesh', 1, NULL, NULL),
(20, 'Barbados', 'barbados', 1, NULL, NULL),
(21, 'Belarus', 'belarus', 1, NULL, NULL),
(22, 'Belgium', 'belgium', 1, NULL, NULL),
(23, 'Belize', 'belize', 1, NULL, NULL),
(24, 'Benin', 'benin', 1, NULL, NULL),
(25, 'Bermuda', 'bermuda', 1, NULL, NULL),
(26, 'Bhutan', 'bhutan', 1, NULL, NULL),
(27, 'Bolivia', 'bolivia', 1, NULL, NULL),
(28, 'Bosnia and Herzegovina', 'bosnia_and_herzegovina', 1, NULL, NULL),
(29, 'Botswana', 'botswana', 1, NULL, NULL),
(30, 'Bouvet Island', 'bouvet_island', 1, NULL, NULL),
(31, 'Brazil', 'brazil', 1, NULL, NULL),
(32, 'British Indian Ocean territory', 'british_indian_ocean_territory', 1, NULL, NULL),
(33, 'Brunei Darussalam', 'brunei_darussalam', 1, NULL, NULL),
(34, 'Bulgaria', 'bulgaria', 1, NULL, NULL),
(35, 'Burkina Faso', 'burkina_faso', 1, NULL, NULL),
(36, 'Burundi', 'burundi', 1, NULL, NULL),
(37, 'Cambodia', 'cambodia', 1, NULL, NULL),
(38, 'Cameroon', 'cameroon', 1, NULL, NULL),
(39, 'Canada', 'canada', 1, NULL, NULL),
(40, 'Cape Verde', 'cape_verde', 1, NULL, NULL),
(41, 'Cayman Islands', 'cayman_islands', 1, NULL, NULL),
(42, 'Central African Republic', 'central_african_republic', 1, NULL, NULL),
(43, 'Chad', 'chad', 1, NULL, NULL),
(44, 'Chile', 'chile', 1, NULL, NULL),
(45, 'China', 'china', 1, NULL, NULL),
(46, 'Christmas Island', 'christmas_island', 1, NULL, NULL),
(47, 'Cocos(Keeling) Islands', 'cocos_islands', 1, NULL, NULL),
(48, 'Colombia', 'colombia', 1, NULL, NULL),
(49, 'Comoros', 'comoros', 1, NULL, NULL),
(50, 'Congo', 'congo', 1, NULL, NULL),
(51, 'Democratic Republic', 'democratic_republic', 1, NULL, NULL),
(52, 'Cook Islands', 'cook_islands', 1, NULL, NULL),
(53, 'Costa Rica', 'costa_rica', 1, NULL, NULL),
(54, 'Ivory Coast(Ivory Coast)', 'ivory_coast', 1, NULL, NULL),
(55, 'Croatia(Hrvatska)', 'croatia', 1, NULL, NULL),
(56, 'Cuba', 'cuba', 1, NULL, NULL),
(57, 'Cyprus', 'cyprus', 1, NULL, NULL),
(58, 'Czech Republic', 'czech_republic', 1, NULL, NULL),
(59, 'Denmark', 'denmark', 1, NULL, NULL),
(60, 'Djibouti', 'djibouti', 1, NULL, NULL),
(61, 'Dominica', 'dominica', 1, NULL, NULL),
(62, 'Dominican Republic', 'dominican_republic', 1, NULL, NULL),
(63, 'East Timor', 'east_timor', 1, NULL, NULL),
(64, 'Ecuador', 'ecuador', 1, NULL, NULL),
(65, 'Egypt', 'egypt', 1, NULL, NULL),
(66, 'El Salvador', 'el_salvador', 1, NULL, NULL),
(67, 'Equatorial Guinea', 'equatorial_guinea', 1, NULL, NULL),
(68, 'Eritrea', 'eritrea', 1, NULL, NULL),
(69, 'Estonia', 'estonia', 1, NULL, NULL),
(70, 'Ethiopia', 'ethiopia', 1, NULL, NULL),
(71, 'Falkland Islands', 'falkland_islands', 1, NULL, NULL),
(72, 'Faroe Islands', 'faroe_islands', 1, NULL, NULL),
(73, 'Fiji', 'fiji', 1, NULL, NULL),
(74, 'Finland', 'finland', 1, NULL, NULL),
(75, 'France', 'france', 1, NULL, NULL),
(76, 'French Guiana', 'french_guiana', 1, NULL, NULL),
(77, 'French Polynesia', 'french_polynesia', 1, NULL, NULL),
(78, 'French Southern Territories', 'french_southern_territories', 1, NULL, NULL),
(79, 'Gabon', 'gabon', 1, NULL, NULL),
(80, 'Gambia', 'gambia', 1, NULL, NULL),
(81, 'Georgia', 'georgia', 1, NULL, NULL),
(82, 'Germany', 'germany', 1, NULL, NULL),
(83, 'Ghana', 'ghana', 1, NULL, NULL),
(84, 'Gibraltar', 'gibraltar', 1, NULL, NULL),
(85, 'Greece', 'greece', 1, NULL, NULL),
(86, 'Greenland', 'greenland', 1, NULL, NULL),
(87, 'Grenada', 'grenada', 1, NULL, NULL),
(88, 'Guadeloupe', 'guadeloupe', 1, NULL, NULL),
(89, 'Guam', 'guam', 1, NULL, NULL),
(90, 'Guatemala', 'guatemala', 1, NULL, NULL),
(91, 'Guinea', 'guinea', 1, NULL, NULL),
(92, 'Guinea-Bissau', 'guinea_bissau', 1, NULL, NULL),
(93, 'Guyana', 'guyana', 1, NULL, NULL),
(94, 'Haiti', 'haiti', 1, NULL, NULL),
(95, 'Heard and McDonald Islands', 'heard_and_mcdonald_islands', 1, NULL, NULL),
(96, 'Honduras', 'honduras', 1, NULL, NULL),
(97, 'Hong Kong', 'hong_kong', 1, NULL, NULL),
(98, 'Hungary', 'hungary', 1, NULL, NULL),
(99, 'Iceland', 'iceland', 1, NULL, NULL),
(100, 'India', 'india', 1, NULL, NULL),
(101, 'Indonesia', 'indonesia', 1, NULL, NULL),
(102, 'Iran', 'iran', 1, NULL, NULL),
(103, 'Iraq', 'iraq', 1, NULL, NULL),
(104, 'Ireland', 'ireland', 1, NULL, NULL),
(105, 'Israel', 'israel', 1, NULL, NULL),
(106, 'Italy', 'italy', 1, NULL, NULL),
(107, 'Jamaica', 'jamaica', 1, NULL, NULL),
(108, 'Japan', 'japan', 1, NULL, NULL),
(109, 'Jordan', 'jordan', 1, NULL, NULL),
(110, 'Kazakhstan', 'kazakhstan', 1, NULL, NULL),
(111, 'Kenya', 'kenya', 1, NULL, NULL),
(112, 'Kiribati', 'kiribati', 1, NULL, NULL),
(113, 'Korea (north)', 'korea_north', 1, NULL, NULL),
(114, 'Korea (south)', 'korea_south', 1, NULL, NULL),
(115, 'Kuwait', 'kuwait', 1, NULL, NULL),
(116, 'Kyrgyzstan', 'kyrgyzstan', 1, NULL, NULL),
(117, 'Lao People\'s Democratic Republic', 'Lao_peoples_emocratic_republic', 1, NULL, NULL),
(118, 'Latvia', 'latvia', 1, NULL, NULL),
(119, 'Lebanon', 'lebanon', 1, NULL, NULL),
(120, 'Lesotho', 'lesotho', 1, NULL, NULL),
(121, 'Liberia', 'liberia', 1, NULL, NULL),
(122, 'Libyan Arab Jamahiriya', 'libyan_arab_jamahiriya', 1, NULL, NULL),
(123, 'Liechtenstein', 'liechtenstein', 1, NULL, NULL),
(124, 'Lithuania', 'lithuania', 1, NULL, NULL),
(125, 'Luxembourg', 'luxembourg', 1, NULL, NULL),
(126, 'Macao', 'macao', 1, NULL, NULL),
(127, 'Macedonia', 'macedonia', 1, NULL, NULL),
(128, 'Madagascar', 'madagascar', 1, NULL, NULL),
(129, 'Malawi', 'malawi', 1, NULL, NULL),
(130, 'Malaysia', 'malaysia', 1, NULL, NULL),
(131, 'Maldives', 'maldives', 1, NULL, NULL),
(132, 'Mali', 'mali', 1, NULL, NULL),
(133, 'Malta', 'malta', 1, NULL, NULL),
(134, 'Marshall Islands', 'marshall_islands', 1, NULL, NULL),
(135, 'Martinique', 'martinique', 1, NULL, NULL),
(136, 'Mauritania', 'mauritania', 1, NULL, NULL),
(137, 'Mauritius', 'mauritius', 1, NULL, NULL),
(138, 'Mayotte', 'mayotte', 1, NULL, NULL),
(139, 'Mexico', 'mexico', 1, NULL, NULL),
(140, 'Micronesia', 'micronesia', 1, NULL, NULL),
(141, 'Moldova', 'moldova', 1, NULL, NULL),
(142, 'Monaco', 'monaco', 1, NULL, NULL),
(143, 'Mongolia', 'mongolia', 1, NULL, NULL),
(144, 'Montserrat', 'Montserrat', 1, NULL, NULL),
(145, 'Morocco', 'morocco', 1, NULL, NULL),
(146, 'Mozambique', 'mozambique', 1, NULL, NULL),
(147, 'Myanmar', 'myanmar', 1, NULL, NULL),
(148, 'Namibia', 'namibia', 1, NULL, NULL),
(149, 'Nauru', 'nauru', 1, NULL, NULL),
(150, 'Nepal', 'nepal', 1, NULL, NULL),
(151, 'Netherlands', 'netherlands', 1, NULL, NULL),
(152, 'Netherlands Antilles', 'netherlands_antilles', 1, NULL, NULL),
(153, 'New Caledonia', 'new_caledonia', 1, NULL, NULL),
(154, 'New Zealand', 'new_zealand', 1, NULL, NULL),
(155, 'Nicaragua', 'nicaragua', 1, NULL, NULL),
(156, 'Niger', 'niger', 1, NULL, NULL),
(157, 'Nigeria', 'nigeria', 1, NULL, NULL),
(158, 'Niue', 'niue', 1, NULL, NULL),
(159, 'Norfolk Island', 'norfolk_island', 1, NULL, NULL),
(160, 'Northern Mariana Islands', 'northern_mariana_islands', 1, NULL, NULL),
(161, 'Norway', 'norway', 1, NULL, NULL),
(162, 'Oman', 'oman', 1, NULL, NULL),
(163, 'Pakistan', 'pakistan', 1, NULL, NULL),
(164, 'Palau', 'palau', 1, NULL, NULL),
(165, 'Palestinian Territories', 'palestinian_territories', 1, NULL, NULL),
(166, 'Panama', 'panama', 1, NULL, NULL),
(167, 'Papua New Guinea', 'papua_new_guinea', 1, NULL, NULL),
(168, 'Paraguay', 'paraguay', 1, NULL, NULL),
(169, 'Peru', 'peru', 1, NULL, NULL),
(170, 'Philippines', 'philippines', 1, NULL, NULL),
(171, 'Pitcairn', 'pitcairn', 1, NULL, NULL),
(172, 'Poland', 'poland', 1, NULL, NULL),
(173, 'Portugal', 'portugal', 1, NULL, NULL),
(174, 'Puerto Rico', 'puerto_rico', 1, NULL, NULL),
(175, 'Qatar', 'qatar', 1, NULL, NULL),
(176, 'Runion', 'runion', 1, NULL, NULL),
(177, 'Romania', 'romania', 1, NULL, NULL),
(178, 'Russian Federation', 'russian_federation', 1, NULL, NULL),
(179, 'Rwanda', 'rwanda', 1, NULL, NULL),
(180, 'Saint Helena', 'saint_helena', 1, NULL, NULL),
(181, 'Saint Kitts and Nevis', 'saint_kitts_and_nevis', 1, NULL, NULL),
(182, 'Saint Lucia', 'saint_lucia', 1, NULL, NULL),
(183, 'Saint Pierre and Miquelon', 'saint_pierre_and_miquelon', 1, NULL, NULL),
(184, 'Saint Vincent and the Grenadines', 'saint_vincent_and_the_grenadines', 1, NULL, NULL),
(185, 'Samoa', 'samoa', 1, NULL, NULL),
(186, 'San Marino', 'san_marino', 1, NULL, NULL),
(187, 'Sao Tome and Principe', 'sao_tome_and_principe', 1, NULL, NULL),
(188, 'Saudi Arabia', 'saudi_arabia', 1, NULL, NULL),
(189, 'Senegal', 'senegal', 1, NULL, NULL),
(190, 'Serbia and Montenegro', 'serbia_and_montenegro', 1, NULL, NULL),
(191, 'Seychelles', 'seychelles', 1, NULL, NULL),
(192, 'Sierra Leone', 'sierra_leone', 1, NULL, NULL),
(193, 'Singapore', 'singapore', 1, NULL, NULL),
(194, 'Slovakia', 'slovakia', 1, NULL, NULL),
(195, 'Slovenia', 'slovenia', 1, NULL, NULL),
(196, 'Solomon Islands', 'solomon_islands', 1, NULL, NULL),
(197, 'Somalia', 'somalia', 1, NULL, NULL),
(198, 'South Africa', 'south_africa', 1, NULL, NULL),
(199, 'South Georgia and the South Sandwich Islands', 'south_georgia_and_the_south_sandwich_islands', 1, NULL, NULL),
(200, 'Spain', 'spain', 1, NULL, NULL),
(201, 'Sri Lanka', 'sri_lanka', 1, NULL, NULL),
(202, 'Sudan', 'sudan', 1, NULL, NULL),
(203, 'Suriname', 'suriname', 1, NULL, NULL),
(204, 'Svalbard and Jan Mayen Islands', 'svalbard_and_jan_mayen_islands', 1, NULL, NULL),
(205, 'Swaziland', 'swaziland', 1, NULL, NULL),
(206, 'Sweden', 'sweden', 1, NULL, NULL),
(207, 'Switzerland', 'switzerland', 1, NULL, NULL),
(208, 'Syria', 'syria', 1, NULL, NULL),
(209, 'Taiwan', 'taiwan', 1, NULL, NULL),
(210, 'Tajikistan', 'tajikistan', 1, NULL, NULL),
(211, 'Tanzania', 'tanzania', 1, NULL, NULL),
(212, 'Thailand', 'thailand', 1, NULL, NULL),
(213, 'Togo', 'togo', 1, NULL, NULL),
(214, 'Tokelau', 'tokelau', 1, NULL, NULL),
(215, 'Tonga', 'tonga', 1, NULL, NULL),
(216, 'Trinidad and Tobago', 'trinidad_and_tobago', 1, NULL, NULL),
(217, 'Tunisia', 'tunisia', 1, NULL, NULL),
(218, 'Turkey', 'turkey', 1, NULL, NULL),
(219, 'Turkmenistan', 'turkmenistan', 1, NULL, NULL),
(220, 'Turks and Caicos Islands', 'turks_and_caicos_islands', 1, NULL, NULL),
(221, 'Tuvalu', 'tuvalu', 1, NULL, NULL),
(222, 'Uganda', 'uganda', 1, NULL, NULL),
(223, 'Ukraine', 'ukraine', 1, NULL, NULL),
(224, 'United Arab Emirates', 'united_arab_emirates', 1, NULL, NULL),
(225, 'United Kingdom', 'united_kingdom', 1, NULL, NULL),
(226, 'United States of America', 'united_states_of_america', 1, NULL, NULL),
(227, 'Uruguay', 'uruguay', 1, NULL, NULL),
(228, 'Uzbekistan', 'uzbekistan', 1, NULL, NULL),
(229, 'Vanuatu', 'vanuatu', 1, NULL, NULL),
(230, 'Vatican City', 'vatican_city', 1, NULL, NULL),
(231, 'Venezuela', 'venezuela', 1, NULL, NULL),
(232, 'Vietnam', 'vietnam', 1, NULL, NULL),
(233, 'Virgin Islands (British)', 'virgin_islands_british', 1, NULL, NULL),
(234, 'Virgin Islands (US)', 'virgin_islands_us', 1, NULL, NULL),
(235, 'Wallis and Futuna Islands', 'wallis_and_futuna_islands', 1, NULL, NULL),
(236, 'Western Sahara', 'western_sahara', 1, NULL, NULL),
(237, 'Yemen', 'yemen', 1, NULL, NULL),
(238, 'Zaire', 'zaire', 1, NULL, NULL),
(239, 'Zambia', 'zambia', 1, NULL, NULL),
(240, 'Zimbabwe', 'zimbabwe', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_meetings`
--

CREATE TABLE `vipspm_meetings` (
  `id` int(10) UNSIGNED NOT NULL,
  `generated_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `organizer_id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `hour_time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=Open,2=In Progress,3=Cancel,4=Completed',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_meeting_members`
--

CREATE TABLE `vipspm_meeting_members` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `meeting_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_menus`
--

CREATE TABLE `vipspm_menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_menu_id` int(11) NOT NULL,
  `module` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order` int(11) NOT NULL,
  `subscription` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=Subscription, 0=Unsubscription',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_menus`
--

INSERT INTO `vipspm_menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES
(1, 0, 'dashboard', 'dashboard', 'Dashboard', '/dashboard', 'fa fa-dashboard', 1, 1, 1),
(2, 0, 'utilities', 'calendar', 'Calendar', '/calendar', 'fa fa-calendar', 0, 1, 1),
(3, 0, 'admin', 'administration', 'Administration', '#', 'fa fa-user-circle', 2, 1, 1),
(4, 3, 'admin', 'roles', 'Roles', '/roles', 'fa fa-user-circle', 1, 1, 1),
(5, 3, 'admin', 'departments', 'Departments', '/departments', 'fa fa-building-o', 2, 1, 1),
(6, 101, 'hrm', 'users', 'Users', '/users', 'fa fa-user-circle', 1, 1, 1),
(7, 3, 'admin', 'teams', 'Teams', '/teams', 'fa fa-universal-access', 3, 1, 1),
(9, 101, 'hrm', 'holidays', 'Holidays', '/holidays', 'fa fa-calendar-plus-o', 3, 1, 1),
(10, 0, 'meetings', 'meetings', 'Meetings', '/meetings', 'fa fa-group', 11, 1, 1),
(11, 0, 'clients', 'clients', 'Clients', '/clients', 'fa fa-user-circle', 9, 1, 1),
(12, 0, 'utilities', 'todos', 'Todos', '/todos', 'fa fa-list-ul', 0, 1, 1),
(13, 0, 'utilities', 'announcements', 'Announcements', '/announcements', 'fa fa-announcements', 0, 1, 1),
(14, 0, 'utilities', 'mailbox', 'Mailbox', '/mailbox', 'fa fa-envelope-o', 0, 1, 1),
(15, 0, 'filemanager', 'file manager', 'File Manager', '/file-browser', 'fa fa-folder', 13, 1, 1),
(16, 0, 'setting', 'settings', 'Settings', '/settings', 'fa fa-cogs', 16, 1, 1),
(17, 16, 'settings', 'company detail', 'Company Detail', '#', 'fa fa-info-circle', 1, 1, 1),
(18, 16, 'settings', 'email settings', 'Email Settings', '#', 'fa fa-envelope', 2, 1, 1),
(19, 16, 'settings', 'email Templates', 'Email Templates', '#', 'fa fa-pencil-square', 3, 1, 1),
(20, 16, 'settings', 'email notifications', 'Email Notifications', '#', 'fa fa-bell-o', 4, 1, 1),
(21, 16, 'settings', 'cronjob', 'Cronjob', '#', 'fa fa-contao', 5, 1, 1),
(22, 16, 'settings', 'menu allocation', 'Menu Allocation', '#', 'fa fa-language', 6, 1, 1),
(23, 16, 'settings', 'theme settings', 'Theme Settings', '#', 'fa fa-compass', 7, 1, 1),
(24, 16, 'settings', 'dashboard settings', 'Dashboard Settings', '#', 'fa fa-cog', 8, 1, 1),
(25, 16, 'settings', 'system settings', 'System Settings', '#', 'fa fa-desktop', 9, 1, 1),
(27, 16, 'settings', 'backup database', 'Backup Database', '#', 'fa fa-database', 11, 1, 1),
(28, 16, 'settings', 'custom fields', 'Custom Fields', '#', 'fa fa-list-alt', 12, 1, 1),
(29, 101, 'pm', 'timesheet', 'Timesheet', '/timesheet', 'fa fa-clock-o', 4, 1, 1),
(30, 16, 'settings', 'translations', 'Translations', '#', 'fa fa-language', 13, 1, 1),
(31, 16, 'settings', 'leave types', 'Leave Types', '#', 'fa fa-calendar-times-o', 14, 1, 1),
(32, 16, 'settings', 'estimate settings', 'Estimate Settings', '#', 'fa fa-external-link', 16, 1, 1),
(33, 16, 'settings', 'invoice settings', 'Invoice Settings', '#', 'fa fa-file-pdf-o', 17, 1, 1),
(34, 16, 'settings', 'payment method', 'Payment Method', '#', 'fa fa-th', 18, 1, 1),
(35, 16, 'settings', 'payment gateway settings', 'Payment Gateway Settings', '#', 'fa fa-credit-card', 19, 1, 1),
(36, 16, 'settings', 'slack settings', 'Slack Settings', '#', 'fa fa-slack', 20, 1, 1),
(41, 0, 'pm', 'project management', 'PM', '#', 'fa fa-product-hunt', 0, 1, 0),
(42, 0, 'pm', 'project planner', 'Project Planner', '/projects-planner', 'fa fa-american-sign-language-interpreting', 5, 1, 1),
(43, 0, 'pm', 'projects', 'Projects', '/projects', 'fa fa-product-hunt', 6, 1, 1),
(44, 0, 'pm', 'tasks', 'Tasks', '/tasks', 'fa fa-tasks', 7, 1, 1),
(46, 0, 'pm', 'defects', 'Defects', '/defects', 'fa fa-bug', 8, 1, 1),
(47, 0, 'pm', 'incidents', 'Incidents', '/incidents', 'fa fa-ticket', 10, 1, 1),
(49, 0, 'pm', 'knowledge base', 'Knowledge Base', '/knowledgebase', 'fa fa-graduation-cap', 14, 1, 1),
(51, 0, 'pm', 'reports', 'Reports', '/reports', 'fa fa-area-chart', 15, 1, 1),
(71, 0, 'crm', 'appointment', 'Appointments', '/appointments', 'fa fa-calendar-plus-o', 12, 1, 1),
(101, 0, 'hrm', 'hrm', 'HRM', '#', 'fa fa-users', 3, 1, 1),
(102, 101, 'hrm', 'leaves', 'Leaves', '/leaves', 'fa fa-calendar-times-o', 2, 1, 1),
(111, 0, 'sales', 'sales', 'Sales', '#', 'fa fa-balance-scale', 4, 1, 1),
(112, 111, 'sales', 'estimates', 'Estimates', '/estimates', 'fa fa-external-link', 1, 1, 1),
(113, 111, 'sales', 'invoices', 'Invoices', '/invoices', 'fa fa-file-pdf-o', 2, 1, 1),
(114, 111, 'sales', 'payments', 'Payments', '/payments', 'fa fa-money', 3, 1, 1),
(115, 111, 'sales', 'items', 'Items', '/items', 'fa fa-shopping-basket', 4, 1, 1),
(116, 111, 'sales', 'taxes', 'Taxes', '/taxes', 'fa fa-percent', 5, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_modules`
--

CREATE TABLE `vipspm_modules` (
  `id` int(10) UNSIGNED NOT NULL,
  `module_id` int(11) NOT NULL,
  `module_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `module_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=Active,0=Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_modules`
--

INSERT INTO `vipspm_modules` (`id`, `module_id`, `module_name`, `module_slug`, `status`) VALUES
(1, 1, 'Project', 'projects', 1),
(2, 2, 'Task', 'task', 1),
(3, 3, 'Defect', 'defect', 1),
(4, 4, 'Incident', 'incident', 1),
(5, 5, 'Meeting', 'meeting', 1),
(6, 6, 'Other', 'other', 1),
(7, 7, 'Leave', 'leave', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_notifications`
--

CREATE TABLE `vipspm_notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `route` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `route_related_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_password_resets`
--

CREATE TABLE `vipspm_password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_payments`
--

CREATE TABLE `vipspm_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `receipt_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL,
  `amount` double(8,2) NOT NULL DEFAULT '0.00',
  `notes` mediumtext COLLATE utf8_unicode_ci,
  `status` enum('in_progress','successful','failed') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'successful',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_payment_gateway_settings`
--

CREATE TABLE `vipspm_payment_gateway_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `paypal_checkout_label` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'paypal',
  `paypal_checkout_client_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paypal_checkout_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=active, 0=inactive',
  `stripe_label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stripe_api_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stripe_secret_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stripe_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=active, 0=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_payment_gateway_settings`
--

INSERT INTO `vipspm_payment_gateway_settings` (`id`, `paypal_checkout_label`, `paypal_checkout_client_id`, `paypal_checkout_status`, `stripe_label`, `stripe_api_key`, `stripe_secret_key`, `stripe_status`, `created_at`, `updated_at`) VALUES
(1, 'paypal', NULL, 0, NULL, NULL, NULL, 0, '2022-06-06 13:26:30', '2022-06-06 13:26:30');

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_payment_methods`
--

CREATE TABLE `vipspm_payment_methods` (
  `id` int(10) UNSIGNED NOT NULL,
  `method_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci,
  `show_on_pdf` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_projects`
--

CREATE TABLE `vipspm_projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `generated_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `project_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `project_version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `progress` int(11) NOT NULL DEFAULT '0',
  `project_hours` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=Progress Bar,1=Task Hours',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `billing_type` tinyint(4) DEFAULT NULL COMMENT '1=Fixed Rate,2=Hourly Rate',
  `price_rate` double(15,8) DEFAULT NULL,
  `estimated_hours` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `actual_hours` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '1=Open,2=InProgress,3=OnHold,4=Cancel,5=Completed',
  `demo_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci,
  `project_logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `assign_to` tinyint(4) DEFAULT NULL,
  `assign_members` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `is_overdue` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_project_attachments`
--

CREATE TABLE `vipspm_project_attachments` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_extension` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_project_comments`
--

CREATE TABLE `vipspm_project_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `attachments` text COLLATE utf8_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_project_sprints`
--

CREATE TABLE `vipspm_project_sprints` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `sprint_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `hours` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=Open,2=InProgress,3=Closed',
  `description` mediumtext COLLATE utf8_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_project_sprint_members`
--

CREATE TABLE `vipspm_project_sprint_members` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_sprint_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_project_sprint_tasks`
--

CREATE TABLE `vipspm_project_sprint_tasks` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_sprint_id` int(10) UNSIGNED DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Task:1=Open, 2=OnHold, 3=Closed, 4=Released|Story:1=Open, 2=InProgress, 3=Closed',
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `estimated_hours` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `assign_to` tinyint(4) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_project_sprint_task_members`
--

CREATE TABLE `vipspm_project_sprint_task_members` (
  `id` int(10) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_project_user`
--

CREATE TABLE `vipspm_project_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `view` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=No,1=Yes',
  `edit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=No,1=Yes',
  `delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=No,1=Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_providers`
--

CREATE TABLE `vipspm_providers` (
  `id` int(10) UNSIGNED NOT NULL,
  `firstname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '1=Active,0=Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_roles`
--

CREATE TABLE `vipspm_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `status` enum('1','0') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_roles`
--

INSERT INTO `vipspm_roles` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', 'User has access to all system functionality.', '1', NULL, NULL),
(2, 'Staff', 'staff', 'Staff role.', '1', NULL, NULL),
(3, 'Client', 'client', 'Client role.', '1', NULL, NULL),
(4, 'Project Manager', 'project_manager', 'Project Manager role.', '1', NULL, NULL),
(5, 'HR Manager', 'hr_manager', 'HR Manager role.', '1', NULL, NULL),
(6, 'Sales Manager', 'sales_manager', 'Sales Manager role.', '1', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_slack_integration`
--

CREATE TABLE `vipspm_slack_integration` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `access_token` text COLLATE utf8_unicode_ci NOT NULL,
  `team_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `channel_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_slack_settings`
--

CREATE TABLE `vipspm_slack_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `slack_client_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slack_client_secret` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slack_redirect_URL` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_authenticate` tinyint(1) NOT NULL DEFAULT '0',
  `slack_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=active, 0=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_slack_settings`
--

INSERT INTO `vipspm_slack_settings` (`id`, `slack_client_id`, `slack_client_secret`, `slack_redirect_URL`, `is_authenticate`, `slack_status`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, NULL, 0, 0, '2022-06-06 13:26:30', '2022-06-06 13:26:30');

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_tasks`
--

CREATE TABLE `vipspm_tasks` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_task_id` int(11) NOT NULL DEFAULT '0',
  `generated_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `project_version` text COLLATE utf8_unicode_ci,
  `planned_start_date` date DEFAULT NULL,
  `planned_end_date` date DEFAULT NULL,
  `task_start_date` datetime DEFAULT NULL,
  `task_end_date` datetime DEFAULT NULL,
  `estimated_hours` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `actual_hours` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=Open,2=In Progress,3=On Hold,4=Waiting For Some one,5=Cancel,6=Completed',
  `priority` int(11) NOT NULL COMMENT '1=Urgent,2=Very High,3=High,4=Medium,5=Low',
  `progress` int(11) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `assign_to` tinyint(4) DEFAULT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci,
  `notes` text COLLATE utf8_unicode_ci,
  `order` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_task_attachments`
--

CREATE TABLE `vipspm_task_attachments` (
  `id` int(10) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_extension` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_task_comments`
--

CREATE TABLE `vipspm_task_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `attachments` text COLLATE utf8_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_task_user`
--

CREATE TABLE `vipspm_task_user` (
  `task_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `view` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=No,1=Yes',
  `edit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=No,1=Yes',
  `delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=No,1=Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_taxes`
--

CREATE TABLE `vipspm_taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `tax_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tax_rate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_teams`
--

CREATE TABLE `vipspm_teams` (
  `id` int(10) UNSIGNED NOT NULL,
  `team_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `team_leader` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_teams_members`
--

CREATE TABLE `vipspm_teams_members` (
  `id` int(10) UNSIGNED NOT NULL,
  `team_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_timer_logs`
--

CREATE TABLE `vipspm_timer_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `reference_id` int(11) NOT NULL,
  `is_task_timer` tinyint(1) NOT NULL DEFAULT '0',
  `is_defect_timer` tinyint(1) NOT NULL DEFAULT '0',
  `is_incident_timer` tinyint(1) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_timesheets`
--

CREATE TABLE `vipspm_timesheets` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `module_related_id` int(11) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `decimal_time` decimal(8,2) NOT NULL,
  `hour_time` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci,
  `created_user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_todos`
--

CREATE TABLE `vipspm_todos` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `module_id` int(11) NOT NULL,
  `module_related_id` int(11) DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `due_date` date DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=Open, 2=Completed',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_translations`
--

CREATE TABLE `vipspm_translations` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vipspm_translations`
--

INSERT INTO `vipspm_translations` (`id`, `name`, `language`, `icon`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', 'United-States.png', 1, NULL, NULL, NULL),
(2, 'Chinese', 'zh', 'China.png', 1, NULL, NULL, NULL),
(3, 'Czech', 'cs', 'Czech-Republic.png', 1, NULL, NULL, NULL),
(4, 'Dutch', 'nl', 'Netherlands.png', 1, NULL, NULL, NULL),
(5, 'French', 'fr', 'France.png', 1, NULL, NULL, NULL),
(6, 'German', 'de', 'Germany.png', 1, NULL, NULL, NULL),
(7, 'Italian', 'it', 'Italy.png', 1, NULL, NULL, NULL),
(8, 'Japanese', 'ja', 'Japan.png', 1, NULL, NULL, NULL),
(9, 'Portuguese', 'pt', 'Portugal.png', 1, NULL, NULL, NULL),
(10, 'Russian', 'ru', 'Russia.png', 1, NULL, NULL, NULL),
(11, 'Spanish', 'es', 'Spain.png', 1, NULL, NULL, NULL),
(12, 'Turkish', 'tr', 'Turkey.png', 1, NULL, NULL, NULL),
(13, 'Vietnamese', 'vi', 'Vietnam.png', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_users`
--

CREATE TABLE `vipspm_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_generated_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_super_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_client` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=Active, 0=Inactive',
  `email_verified` enum('1','0') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '1=Verified, 0=Unverified',
  `email_verification_code` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `online_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=Online, 0=Offline',
  `avatar` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_ip` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `emp_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postal_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en' COMMENT 'Used for app locale',
  `address` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `skype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `gender` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `maritial_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `father_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mother_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `permission` text COLLATE utf8_unicode_ci,
  `can_view_all_users` tinyint(1) NOT NULL DEFAULT '0',
  `primary_manager` int(11) DEFAULT NULL,
  `secondary_manager` int(11) DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_user_activities`
--

CREATE TABLE `vipspm_user_activities` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `module` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `self_parent_id` int(11) DEFAULT NULL,
  `module_field_id` int(11) NOT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `diff_for_humans` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_user_role_department`
--

CREATE TABLE `vipspm_user_role_department` (
  `id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vipspm_user_settings`
--

CREATE TABLE `vipspm_user_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_legal_name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_short_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'VipsPM',
  `contact_person` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_address` text COLLATE utf8_unicode_ci,
  `company_country` bigint(20) DEFAULT NULL,
  `company_city` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_zipcode` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_from_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `post_mark` tinyint(1) NOT NULL DEFAULT '0',
  `smtp_protocol` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `smtp_host` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `smtp_user` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `smtp_password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `smtp_port` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `smtp_encryption` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mail_from_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sparkpost_secret` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mailgun_domain` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mailgun_secret` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mandrill_secret` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_announcement_notification` tinyint(1) NOT NULL DEFAULT '1',
  `active_cronjob` tinyint(1) NOT NULL DEFAULT '0',
  `automatic_backup` tinyint(1) NOT NULL DEFAULT '0',
  `daily_report_emails` text COLLATE utf8_unicode_ci,
  `last_autobackup` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `cronjob_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_cronjob_run` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reminder_alert_before_hours` varchar(255) COLLATE utf8_unicode_ci DEFAULT '6,12,24' COMMENT 'For appointment and meeting reminder.',
  `reminder_alert_before_days` varchar(255) COLLATE utf8_unicode_ci DEFAULT '1,2,3' COMMENT 'For project, task, defect and incident reminder.',
  `login_background` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_sidebar_logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sidebar_background_images` text COLLATE utf8_unicode_ci,
  `default_language` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `default_locale` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tables_pagination_limit` int(11) NOT NULL DEFAULT '10',
  `date_format` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'YYYY-MM-DD',
  `time_format` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'hh:mm A',
  `date_time_format` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'YYYY-MM-DD hh:mm A',
  `php_date_format` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y-m-d',
  `php_time_format` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'H:i',
  `php_date_time_format` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y-m-d H:i',
  `currency_id` int(10) UNSIGNED DEFAULT NULL,
  `purchase_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unique_coder` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_collapsed_menu` tinyint(1) NOT NULL DEFAULT '1',
  `is_sidebar_background` tinyint(1) NOT NULL DEFAULT '1',
  `theme_layout` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'light',
  `sidebar_bg_color` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pomegranate-gr',
  `sidebar_transparent_bg_image` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'bg_glass_1',
  `sidebar_bg_custom_color` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#000000',
  `sidebar_font_color` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'black',
  `sidebar_width` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'small',
  `sidebar_bg_image` int(11) NOT NULL DEFAULT '0',
  `product_version` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_demo` tinyint(1) NOT NULL DEFAULT '0',
  `allowed_for_registration` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_announcements`
--
ALTER TABLE `vipspm_announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_announcements_user_id_foreign` (`user_id`);

--
-- Indexes for table `vipspm_appointment`
--
ALTER TABLE `vipspm_appointment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_appointment_user_id_index` (`user_id`),
  ADD KEY `vipspm_appointment_provider_id_index` (`provider_id`);

--
-- Indexes for table `vipspm_appointment_recurrence`
--
ALTER TABLE `vipspm_appointment_recurrence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_appointment_recurrence_appointment_id_foreign` (`appointment_id`);

--
-- Indexes for table `vipspm_clients`
--
ALTER TABLE `vipspm_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_clients_user_id_foreign` (`user_id`);

--
-- Indexes for table `vipspm_countries`
--
ALTER TABLE `vipspm_countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_currency`
--
ALTER TABLE `vipspm_currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_custom_fields`
--
ALTER TABLE `vipspm_custom_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_custom_fields_form_id_foreign` (`form_id`);

--
-- Indexes for table `vipspm_dashboard_settings`
--
ALTER TABLE `vipspm_dashboard_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_database_backups`
--
ALTER TABLE `vipspm_database_backups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_database_backups_user_id_foreign` (`user_id`);

--
-- Indexes for table `vipspm_defects`
--
ALTER TABLE `vipspm_defects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_defects_history`
--
ALTER TABLE `vipspm_defects_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_defects_history_defect_id_foreign` (`defect_id`);

--
-- Indexes for table `vipspm_defect_attachments`
--
ALTER TABLE `vipspm_defect_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_defect_attachments_defect_id_index` (`defect_id`);

--
-- Indexes for table `vipspm_defect_comments`
--
ALTER TABLE `vipspm_defect_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_defect_comments_user_id_foreign` (`user_id`),
  ADD KEY `vipspm_defect_comments_defect_id_index` (`defect_id`);

--
-- Indexes for table `vipspm_defect_user`
--
ALTER TABLE `vipspm_defect_user`
  ADD PRIMARY KEY (`defect_id`,`user_id`),
  ADD KEY `vipspm_defect_user_defect_id_index` (`defect_id`),
  ADD KEY `vipspm_defect_user_user_id_index` (`user_id`);

--
-- Indexes for table `vipspm_departments`
--
ALTER TABLE `vipspm_departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vipspm_departments_name_deleted_at_unique` (`name`,`deleted_at`);

--
-- Indexes for table `vipspm_departments_roles`
--
ALTER TABLE `vipspm_departments_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_departments_roles_role_id_index` (`role_id`),
  ADD KEY `vipspm_departments_roles_department_id_index` (`department_id`);

--
-- Indexes for table `vipspm_department_role_menu`
--
ALTER TABLE `vipspm_department_role_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_department_role_menu_department_id_index` (`department_id`),
  ADD KEY `vipspm_department_role_menu_role_id_index` (`role_id`),
  ADD KEY `vipspm_department_role_menu_menu_id_index` (`menu_id`);

--
-- Indexes for table `vipspm_email_inbox`
--
ALTER TABLE `vipspm_email_inbox`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_email_inbox_user_id_foreign` (`user_id`);

--
-- Indexes for table `vipspm_email_inbox_attachment`
--
ALTER TABLE `vipspm_email_inbox_attachment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_email_inbox_attachment_mailbox_id_foreign` (`mailbox_id`);

--
-- Indexes for table `vipspm_email_template`
--
ALTER TABLE `vipspm_email_template`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vipspm_email_template_template_name_deleted_at_unique` (`template_name`,`deleted_at`),
  ADD KEY `vipspm_email_template_email_group_id_foreign` (`email_group_id`);

--
-- Indexes for table `vipspm_email_template_groups`
--
ALTER TABLE `vipspm_email_template_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_estimates`
--
ALTER TABLE `vipspm_estimates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vipspm_estimates_estimate_number_unique` (`estimate_number`),
  ADD KEY `vipspm_estimates_user_id_foreign` (`user_id`),
  ADD KEY `vipspm_estimates_client_id_foreign` (`client_id`);

--
-- Indexes for table `vipspm_estimate_items`
--
ALTER TABLE `vipspm_estimate_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_estimate_items_estimate_id_foreign` (`estimate_id`);

--
-- Indexes for table `vipspm_estimate_item_taxes`
--
ALTER TABLE `vipspm_estimate_item_taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_estimate_item_taxes_estimate_item_id_foreign` (`estimate_item_id`),
  ADD KEY `vipspm_estimate_item_taxes_tax_id_foreign` (`tax_id`);

--
-- Indexes for table `vipspm_estimate_settings`
--
ALTER TABLE `vipspm_estimate_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_files`
--
ALTER TABLE `vipspm_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_files_user_id_foreign` (`user_id`);

--
-- Indexes for table `vipspm_folders`
--
ALTER TABLE `vipspm_folders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_folders_user_id_foreign` (`user_id`);

--
-- Indexes for table `vipspm_form`
--
ALTER TABLE `vipspm_form`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_holidays`
--
ALTER TABLE `vipspm_holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_incidents`
--
ALTER TABLE `vipspm_incidents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_incident_attachments`
--
ALTER TABLE `vipspm_incident_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_incident_attachments_incident_id_index` (`incident_id`);

--
-- Indexes for table `vipspm_incident_comments`
--
ALTER TABLE `vipspm_incident_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_incident_comments_user_id_foreign` (`user_id`),
  ADD KEY `vipspm_incident_comments_incident_id_index` (`incident_id`);

--
-- Indexes for table `vipspm_incident_history`
--
ALTER TABLE `vipspm_incident_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_incident_history_incident_id_foreign` (`incident_id`);

--
-- Indexes for table `vipspm_incident_user`
--
ALTER TABLE `vipspm_incident_user`
  ADD PRIMARY KEY (`incident_id`,`user_id`),
  ADD KEY `vipspm_incident_user_incident_id_index` (`incident_id`),
  ADD KEY `vipspm_incident_user_user_id_index` (`user_id`);

--
-- Indexes for table `vipspm_invoices`
--
ALTER TABLE `vipspm_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vipspm_invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `vipspm_invoices_user_id_foreign` (`user_id`),
  ADD KEY `vipspm_invoices_client_id_foreign` (`client_id`);

--
-- Indexes for table `vipspm_invoice_items`
--
ALTER TABLE `vipspm_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_invoice_items_invoice_id_foreign` (`invoice_id`);

--
-- Indexes for table `vipspm_invoice_item_taxes`
--
ALTER TABLE `vipspm_invoice_item_taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_invoice_item_taxes_invoice_item_id_foreign` (`invoice_item_id`),
  ADD KEY `vipspm_invoice_item_taxes_tax_id_foreign` (`tax_id`);

--
-- Indexes for table `vipspm_invoice_settings`
--
ALTER TABLE `vipspm_invoice_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_items`
--
ALTER TABLE `vipspm_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_item_tax`
--
ALTER TABLE `vipspm_item_tax`
  ADD PRIMARY KEY (`tax_id`,`item_id`),
  ADD KEY `vipspm_item_tax_item_id_index` (`item_id`),
  ADD KEY `vipspm_item_tax_tax_id_index` (`tax_id`);

--
-- Indexes for table `vipspm_knowledge_base_article`
--
ALTER TABLE `vipspm_knowledge_base_article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_knowledge_base_article_user_id_foreign` (`user_id`),
  ADD KEY `vipspm_knowledge_base_article_category_id_index` (`category_id`);

--
-- Indexes for table `vipspm_knowledge_base_category`
--
ALTER TABLE `vipspm_knowledge_base_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_knowledge_base_category_user_id_foreign` (`user_id`);

--
-- Indexes for table `vipspm_languages`
--
ALTER TABLE `vipspm_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_leaves`
--
ALTER TABLE `vipspm_leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_leaves_user_id_foreign` (`user_id`),
  ADD KEY `vipspm_leaves_leave_type_id_foreign` (`leave_type_id`);

--
-- Indexes for table `vipspm_leave_attachments`
--
ALTER TABLE `vipspm_leave_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_leave_attachments_leave_id_index` (`leave_id`);

--
-- Indexes for table `vipspm_leave_types`
--
ALTER TABLE `vipspm_leave_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_locales`
--
ALTER TABLE `vipspm_locales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_meetings`
--
ALTER TABLE `vipspm_meetings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_meetings_organizer_id_foreign` (`organizer_id`);

--
-- Indexes for table `vipspm_meeting_members`
--
ALTER TABLE `vipspm_meeting_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_meeting_members_user_id_foreign` (`user_id`),
  ADD KEY `vipspm_meeting_members_meeting_id_foreign` (`meeting_id`);

--
-- Indexes for table `vipspm_menus`
--
ALTER TABLE `vipspm_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_modules`
--
ALTER TABLE `vipspm_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_notifications`
--
ALTER TABLE `vipspm_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_password_resets`
--
ALTER TABLE `vipspm_password_resets`
  ADD KEY `vipspm_password_resets_email_index` (`email`);

--
-- Indexes for table `vipspm_payments`
--
ALTER TABLE `vipspm_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vipspm_payments_receipt_number_unique` (`receipt_number`),
  ADD KEY `vipspm_payments_user_id_foreign` (`user_id`),
  ADD KEY `vipspm_payments_client_id_foreign` (`client_id`);

--
-- Indexes for table `vipspm_payment_gateway_settings`
--
ALTER TABLE `vipspm_payment_gateway_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_payment_methods`
--
ALTER TABLE `vipspm_payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vipspm_payment_methods_method_name_unique` (`method_name`);

--
-- Indexes for table `vipspm_projects`
--
ALTER TABLE `vipspm_projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vipspm_projects_project_name_deleted_at_unique` (`project_name`,`deleted_at`);

--
-- Indexes for table `vipspm_project_attachments`
--
ALTER TABLE `vipspm_project_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_project_attachments_project_id_foreign` (`project_id`);

--
-- Indexes for table `vipspm_project_comments`
--
ALTER TABLE `vipspm_project_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_project_comments_user_id_foreign` (`user_id`),
  ADD KEY `vipspm_project_comments_project_id_foreign` (`project_id`);

--
-- Indexes for table `vipspm_project_sprints`
--
ALTER TABLE `vipspm_project_sprints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_project_sprints_project_id_foreign` (`project_id`);

--
-- Indexes for table `vipspm_project_sprint_members`
--
ALTER TABLE `vipspm_project_sprint_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_project_sprint_members_project_sprint_id_foreign` (`project_sprint_id`),
  ADD KEY `vipspm_project_sprint_members_user_id_foreign` (`user_id`);

--
-- Indexes for table `vipspm_project_sprint_tasks`
--
ALTER TABLE `vipspm_project_sprint_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_project_sprint_tasks_project_sprint_id_foreign` (`project_sprint_id`);

--
-- Indexes for table `vipspm_project_sprint_task_members`
--
ALTER TABLE `vipspm_project_sprint_task_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_project_sprint_task_members_task_id_foreign` (`task_id`),
  ADD KEY `vipspm_project_sprint_task_members_user_id_foreign` (`user_id`);

--
-- Indexes for table `vipspm_project_user`
--
ALTER TABLE `vipspm_project_user`
  ADD PRIMARY KEY (`project_id`,`user_id`),
  ADD KEY `vipspm_project_user_user_id_index` (`user_id`),
  ADD KEY `vipspm_project_user_project_id_index` (`project_id`);

--
-- Indexes for table `vipspm_providers`
--
ALTER TABLE `vipspm_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_roles`
--
ALTER TABLE `vipspm_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vipspm_roles_slug_unique` (`slug`);

--
-- Indexes for table `vipspm_slack_integration`
--
ALTER TABLE `vipspm_slack_integration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_slack_integration_user_id_foreign` (`user_id`);

--
-- Indexes for table `vipspm_slack_settings`
--
ALTER TABLE `vipspm_slack_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_tasks`
--
ALTER TABLE `vipspm_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_tasks_project_id_index` (`project_id`);

--
-- Indexes for table `vipspm_task_attachments`
--
ALTER TABLE `vipspm_task_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_task_attachments_task_id_index` (`task_id`);

--
-- Indexes for table `vipspm_task_comments`
--
ALTER TABLE `vipspm_task_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_task_comments_user_id_foreign` (`user_id`),
  ADD KEY `vipspm_task_comments_task_id_index` (`task_id`);

--
-- Indexes for table `vipspm_task_user`
--
ALTER TABLE `vipspm_task_user`
  ADD PRIMARY KEY (`task_id`,`user_id`),
  ADD KEY `vipspm_task_user_task_id_index` (`task_id`),
  ADD KEY `vipspm_task_user_user_id_index` (`user_id`);

--
-- Indexes for table `vipspm_taxes`
--
ALTER TABLE `vipspm_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_teams`
--
ALTER TABLE `vipspm_teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_teams_members`
--
ALTER TABLE `vipspm_teams_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_teams_members_team_id_foreign` (`team_id`),
  ADD KEY `vipspm_teams_members_user_id_foreign` (`user_id`);

--
-- Indexes for table `vipspm_timer_logs`
--
ALTER TABLE `vipspm_timer_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_timesheets`
--
ALTER TABLE `vipspm_timesheets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_timesheets_created_user_id_index` (`created_user_id`);

--
-- Indexes for table `vipspm_todos`
--
ALTER TABLE `vipspm_todos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_todos_user_id_foreign` (`user_id`);

--
-- Indexes for table `vipspm_translations`
--
ALTER TABLE `vipspm_translations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vipspm_users`
--
ALTER TABLE `vipspm_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vipspm_users_username_deleted_at_unique` (`username`,`deleted_at`),
  ADD UNIQUE KEY `vipspm_users_email_deleted_at_unique` (`email`,`deleted_at`);

--
-- Indexes for table `vipspm_user_activities`
--
ALTER TABLE `vipspm_user_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_user_activities_user_id_foreign` (`user_id`);

--
-- Indexes for table `vipspm_user_role_department`
--
ALTER TABLE `vipspm_user_role_department`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vipspm_user_role_department_department_id_index` (`department_id`),
  ADD KEY `vipspm_user_role_department_role_id_index` (`role_id`),
  ADD KEY `vipspm_user_role_department_user_id_index` (`user_id`);

--
-- Indexes for table `vipspm_user_settings`
--
ALTER TABLE `vipspm_user_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `vipspm_announcements`
--
ALTER TABLE `vipspm_announcements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_appointment`
--
ALTER TABLE `vipspm_appointment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_appointment_recurrence`
--
ALTER TABLE `vipspm_appointment_recurrence`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_clients`
--
ALTER TABLE `vipspm_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_countries`
--
ALTER TABLE `vipspm_countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=265;

--
-- AUTO_INCREMENT for table `vipspm_currency`
--
ALTER TABLE `vipspm_currency`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `vipspm_custom_fields`
--
ALTER TABLE `vipspm_custom_fields`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_dashboard_settings`
--
ALTER TABLE `vipspm_dashboard_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vipspm_database_backups`
--
ALTER TABLE `vipspm_database_backups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_defects`
--
ALTER TABLE `vipspm_defects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_defects_history`
--
ALTER TABLE `vipspm_defects_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_defect_attachments`
--
ALTER TABLE `vipspm_defect_attachments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_defect_comments`
--
ALTER TABLE `vipspm_defect_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_departments`
--
ALTER TABLE `vipspm_departments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vipspm_departments_roles`
--
ALTER TABLE `vipspm_departments_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vipspm_department_role_menu`
--
ALTER TABLE `vipspm_department_role_menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=307;

--
-- AUTO_INCREMENT for table `vipspm_email_inbox`
--
ALTER TABLE `vipspm_email_inbox`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_email_inbox_attachment`
--
ALTER TABLE `vipspm_email_inbox_attachment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_email_template`
--
ALTER TABLE `vipspm_email_template`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `vipspm_email_template_groups`
--
ALTER TABLE `vipspm_email_template_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `vipspm_estimates`
--
ALTER TABLE `vipspm_estimates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_estimate_items`
--
ALTER TABLE `vipspm_estimate_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_estimate_item_taxes`
--
ALTER TABLE `vipspm_estimate_item_taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_estimate_settings`
--
ALTER TABLE `vipspm_estimate_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vipspm_files`
--
ALTER TABLE `vipspm_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_folders`
--
ALTER TABLE `vipspm_folders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_form`
--
ALTER TABLE `vipspm_form`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vipspm_holidays`
--
ALTER TABLE `vipspm_holidays`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_incidents`
--
ALTER TABLE `vipspm_incidents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_incident_attachments`
--
ALTER TABLE `vipspm_incident_attachments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_incident_comments`
--
ALTER TABLE `vipspm_incident_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_incident_history`
--
ALTER TABLE `vipspm_incident_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_invoices`
--
ALTER TABLE `vipspm_invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_invoice_items`
--
ALTER TABLE `vipspm_invoice_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_invoice_item_taxes`
--
ALTER TABLE `vipspm_invoice_item_taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_invoice_settings`
--
ALTER TABLE `vipspm_invoice_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vipspm_items`
--
ALTER TABLE `vipspm_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_knowledge_base_article`
--
ALTER TABLE `vipspm_knowledge_base_article`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_knowledge_base_category`
--
ALTER TABLE `vipspm_knowledge_base_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_languages`
--
ALTER TABLE `vipspm_languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `vipspm_leaves`
--
ALTER TABLE `vipspm_leaves`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_leave_attachments`
--
ALTER TABLE `vipspm_leave_attachments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_leave_types`
--
ALTER TABLE `vipspm_leave_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vipspm_locales`
--
ALTER TABLE `vipspm_locales`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT for table `vipspm_meetings`
--
ALTER TABLE `vipspm_meetings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_meeting_members`
--
ALTER TABLE `vipspm_meeting_members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_menus`
--
ALTER TABLE `vipspm_menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `vipspm_modules`
--
ALTER TABLE `vipspm_modules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `vipspm_notifications`
--
ALTER TABLE `vipspm_notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_payments`
--
ALTER TABLE `vipspm_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_payment_gateway_settings`
--
ALTER TABLE `vipspm_payment_gateway_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vipspm_payment_methods`
--
ALTER TABLE `vipspm_payment_methods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_projects`
--
ALTER TABLE `vipspm_projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_project_attachments`
--
ALTER TABLE `vipspm_project_attachments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_project_comments`
--
ALTER TABLE `vipspm_project_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_project_sprints`
--
ALTER TABLE `vipspm_project_sprints`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_project_sprint_members`
--
ALTER TABLE `vipspm_project_sprint_members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_project_sprint_tasks`
--
ALTER TABLE `vipspm_project_sprint_tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_project_sprint_task_members`
--
ALTER TABLE `vipspm_project_sprint_task_members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_providers`
--
ALTER TABLE `vipspm_providers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_roles`
--
ALTER TABLE `vipspm_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vipspm_slack_integration`
--
ALTER TABLE `vipspm_slack_integration`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_slack_settings`
--
ALTER TABLE `vipspm_slack_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vipspm_tasks`
--
ALTER TABLE `vipspm_tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_task_attachments`
--
ALTER TABLE `vipspm_task_attachments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_task_comments`
--
ALTER TABLE `vipspm_task_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_taxes`
--
ALTER TABLE `vipspm_taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_teams`
--
ALTER TABLE `vipspm_teams`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_teams_members`
--
ALTER TABLE `vipspm_teams_members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_timer_logs`
--
ALTER TABLE `vipspm_timer_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_timesheets`
--
ALTER TABLE `vipspm_timesheets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_todos`
--
ALTER TABLE `vipspm_todos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_translations`
--
ALTER TABLE `vipspm_translations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `vipspm_users`
--
ALTER TABLE `vipspm_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_user_activities`
--
ALTER TABLE `vipspm_user_activities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_user_role_department`
--
ALTER TABLE `vipspm_user_role_department`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vipspm_user_settings`
--
ALTER TABLE `vipspm_user_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `vipspm_announcements`
--
ALTER TABLE `vipspm_announcements`
  ADD CONSTRAINT `vipspm_announcements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`);

--
-- Constraints for table `vipspm_appointment`
--
ALTER TABLE `vipspm_appointment`
  ADD CONSTRAINT `vipspm_appointment_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `vipspm_providers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_appointment_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_appointment_recurrence`
--
ALTER TABLE `vipspm_appointment_recurrence`
  ADD CONSTRAINT `vipspm_appointment_recurrence_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `vipspm_appointment` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_clients`
--
ALTER TABLE `vipspm_clients`
  ADD CONSTRAINT `vipspm_clients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_custom_fields`
--
ALTER TABLE `vipspm_custom_fields`
  ADD CONSTRAINT `vipspm_custom_fields_form_id_foreign` FOREIGN KEY (`form_id`) REFERENCES `vipspm_form` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_database_backups`
--
ALTER TABLE `vipspm_database_backups`
  ADD CONSTRAINT `vipspm_database_backups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_defects_history`
--
ALTER TABLE `vipspm_defects_history`
  ADD CONSTRAINT `vipspm_defects_history_defect_id_foreign` FOREIGN KEY (`defect_id`) REFERENCES `vipspm_defects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_defect_attachments`
--
ALTER TABLE `vipspm_defect_attachments`
  ADD CONSTRAINT `vipspm_defect_attachments_defect_id_foreign` FOREIGN KEY (`defect_id`) REFERENCES `vipspm_defects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_defect_comments`
--
ALTER TABLE `vipspm_defect_comments`
  ADD CONSTRAINT `vipspm_defect_comments_defect_id_foreign` FOREIGN KEY (`defect_id`) REFERENCES `vipspm_defects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_defect_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_defect_user`
--
ALTER TABLE `vipspm_defect_user`
  ADD CONSTRAINT `vipspm_defect_user_defect_id_foreign` FOREIGN KEY (`defect_id`) REFERENCES `vipspm_defects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_defect_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_departments_roles`
--
ALTER TABLE `vipspm_departments_roles`
  ADD CONSTRAINT `vipspm_departments_roles_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `vipspm_departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_departments_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `vipspm_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_department_role_menu`
--
ALTER TABLE `vipspm_department_role_menu`
  ADD CONSTRAINT `vipspm_department_role_menu_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `vipspm_departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_department_role_menu_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `vipspm_menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_department_role_menu_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `vipspm_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_email_inbox`
--
ALTER TABLE `vipspm_email_inbox`
  ADD CONSTRAINT `vipspm_email_inbox_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_email_inbox_attachment`
--
ALTER TABLE `vipspm_email_inbox_attachment`
  ADD CONSTRAINT `vipspm_email_inbox_attachment_mailbox_id_foreign` FOREIGN KEY (`mailbox_id`) REFERENCES `vipspm_email_inbox` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vipspm_email_template`
--
ALTER TABLE `vipspm_email_template`
  ADD CONSTRAINT `vipspm_email_template_email_group_id_foreign` FOREIGN KEY (`email_group_id`) REFERENCES `vipspm_email_template_groups` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vipspm_estimates`
--
ALTER TABLE `vipspm_estimates`
  ADD CONSTRAINT `vipspm_estimates_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_estimates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_estimate_items`
--
ALTER TABLE `vipspm_estimate_items`
  ADD CONSTRAINT `vipspm_estimate_items_estimate_id_foreign` FOREIGN KEY (`estimate_id`) REFERENCES `vipspm_estimates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_estimate_item_taxes`
--
ALTER TABLE `vipspm_estimate_item_taxes`
  ADD CONSTRAINT `vipspm_estimate_item_taxes_estimate_item_id_foreign` FOREIGN KEY (`estimate_item_id`) REFERENCES `vipspm_estimate_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_estimate_item_taxes_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `vipspm_taxes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_files`
--
ALTER TABLE `vipspm_files`
  ADD CONSTRAINT `vipspm_files_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_folders`
--
ALTER TABLE `vipspm_folders`
  ADD CONSTRAINT `vipspm_folders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_incident_attachments`
--
ALTER TABLE `vipspm_incident_attachments`
  ADD CONSTRAINT `vipspm_incident_attachments_incident_id_foreign` FOREIGN KEY (`incident_id`) REFERENCES `vipspm_incidents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_incident_comments`
--
ALTER TABLE `vipspm_incident_comments`
  ADD CONSTRAINT `vipspm_incident_comments_incident_id_foreign` FOREIGN KEY (`incident_id`) REFERENCES `vipspm_incidents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_incident_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_incident_history`
--
ALTER TABLE `vipspm_incident_history`
  ADD CONSTRAINT `vipspm_incident_history_incident_id_foreign` FOREIGN KEY (`incident_id`) REFERENCES `vipspm_incidents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_incident_user`
--
ALTER TABLE `vipspm_incident_user`
  ADD CONSTRAINT `vipspm_incident_user_incident_id_foreign` FOREIGN KEY (`incident_id`) REFERENCES `vipspm_incidents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_incident_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_invoices`
--
ALTER TABLE `vipspm_invoices`
  ADD CONSTRAINT `vipspm_invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_invoice_items`
--
ALTER TABLE `vipspm_invoice_items`
  ADD CONSTRAINT `vipspm_invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `vipspm_invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_invoice_item_taxes`
--
ALTER TABLE `vipspm_invoice_item_taxes`
  ADD CONSTRAINT `vipspm_invoice_item_taxes_invoice_item_id_foreign` FOREIGN KEY (`invoice_item_id`) REFERENCES `vipspm_invoice_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_invoice_item_taxes_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `vipspm_taxes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_item_tax`
--
ALTER TABLE `vipspm_item_tax`
  ADD CONSTRAINT `vipspm_item_tax_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `vipspm_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_item_tax_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `vipspm_taxes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_knowledge_base_article`
--
ALTER TABLE `vipspm_knowledge_base_article`
  ADD CONSTRAINT `vipspm_knowledge_base_article_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `vipspm_knowledge_base_category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_knowledge_base_article_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_knowledge_base_category`
--
ALTER TABLE `vipspm_knowledge_base_category`
  ADD CONSTRAINT `vipspm_knowledge_base_category_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_leaves`
--
ALTER TABLE `vipspm_leaves`
  ADD CONSTRAINT `vipspm_leaves_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `vipspm_leave_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_leaves_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_leave_attachments`
--
ALTER TABLE `vipspm_leave_attachments`
  ADD CONSTRAINT `vipspm_leave_attachments_leave_id_foreign` FOREIGN KEY (`leave_id`) REFERENCES `vipspm_leaves` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_meetings`
--
ALTER TABLE `vipspm_meetings`
  ADD CONSTRAINT `vipspm_meetings_organizer_id_foreign` FOREIGN KEY (`organizer_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_meeting_members`
--
ALTER TABLE `vipspm_meeting_members`
  ADD CONSTRAINT `vipspm_meeting_members_meeting_id_foreign` FOREIGN KEY (`meeting_id`) REFERENCES `vipspm_meetings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_meeting_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_payments`
--
ALTER TABLE `vipspm_payments`
  ADD CONSTRAINT `vipspm_payments_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `vipspm_users` (`id`),
  ADD CONSTRAINT `vipspm_payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`);

--
-- Constraints for table `vipspm_project_attachments`
--
ALTER TABLE `vipspm_project_attachments`
  ADD CONSTRAINT `vipspm_project_attachments_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `vipspm_projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_project_comments`
--
ALTER TABLE `vipspm_project_comments`
  ADD CONSTRAINT `vipspm_project_comments_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `vipspm_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_project_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_project_sprints`
--
ALTER TABLE `vipspm_project_sprints`
  ADD CONSTRAINT `vipspm_project_sprints_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `vipspm_projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_project_sprint_members`
--
ALTER TABLE `vipspm_project_sprint_members`
  ADD CONSTRAINT `vipspm_project_sprint_members_project_sprint_id_foreign` FOREIGN KEY (`project_sprint_id`) REFERENCES `vipspm_project_sprints` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_project_sprint_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_project_sprint_tasks`
--
ALTER TABLE `vipspm_project_sprint_tasks`
  ADD CONSTRAINT `vipspm_project_sprint_tasks_project_sprint_id_foreign` FOREIGN KEY (`project_sprint_id`) REFERENCES `vipspm_project_sprints` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_project_sprint_task_members`
--
ALTER TABLE `vipspm_project_sprint_task_members`
  ADD CONSTRAINT `vipspm_project_sprint_task_members_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `vipspm_project_sprint_tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_project_sprint_task_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_project_user`
--
ALTER TABLE `vipspm_project_user`
  ADD CONSTRAINT `vipspm_project_user_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `vipspm_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_project_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_slack_integration`
--
ALTER TABLE `vipspm_slack_integration`
  ADD CONSTRAINT `vipspm_slack_integration_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_tasks`
--
ALTER TABLE `vipspm_tasks`
  ADD CONSTRAINT `vipspm_tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `vipspm_projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_task_attachments`
--
ALTER TABLE `vipspm_task_attachments`
  ADD CONSTRAINT `vipspm_task_attachments_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `vipspm_tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_task_comments`
--
ALTER TABLE `vipspm_task_comments`
  ADD CONSTRAINT `vipspm_task_comments_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `vipspm_tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_task_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_task_user`
--
ALTER TABLE `vipspm_task_user`
  ADD CONSTRAINT `vipspm_task_user_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `vipspm_tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_task_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_teams_members`
--
ALTER TABLE `vipspm_teams_members`
  ADD CONSTRAINT `vipspm_teams_members_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `vipspm_teams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_teams_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_timesheets`
--
ALTER TABLE `vipspm_timesheets`
  ADD CONSTRAINT `vipspm_timesheets_created_user_id_foreign` FOREIGN KEY (`created_user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_todos`
--
ALTER TABLE `vipspm_todos`
  ADD CONSTRAINT `vipspm_todos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vipspm_user_activities`
--
ALTER TABLE `vipspm_user_activities`
  ADD CONSTRAINT `vipspm_user_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`);

--
-- Constraints for table `vipspm_user_role_department`
--
ALTER TABLE `vipspm_user_role_department`
  ADD CONSTRAINT `vipspm_user_role_department_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `vipspm_departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_user_role_department_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `vipspm_roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vipspm_user_role_department_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vipspm_users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
