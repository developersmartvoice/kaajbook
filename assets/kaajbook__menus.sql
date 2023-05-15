-- phpMyAdmin SQL Dump
-- version 4.9.11
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 15, 2023 at 02:21 AM
-- Server version: 10.3.38-MariaDB-log-cll-lve
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kaajbook_spmp`
--

-- --------------------------------------------------------

--
-- Table structure for table `kaajbook__menus`
--

CREATE TABLE `kaajbook__menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_menu_id` int(11) NOT NULL,
  `module` varchar(255) DEFAULT NULL,
  `label` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `subscription` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=Subscription, 0=Unsubscription',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=Active, 0=Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `kaajbook__menus`
--

INSERT INTO `kaajbook__menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES
(1, 0, 'dashboard', 'dashboard', 'Dashboard', '/dashboard', 'fa fa-dashboard', 4, 1, 1),
(2, 0, 'utilities', 'calendar', 'Calendar', '/calendar', 'fa fa-calendar', 0, 1, 1),
(3, 0, 'admin', 'administration', 'Administration', '#', 'fa fa-user-circle', 5, 1, 1),
(4, 3, 'admin', 'roles', 'Roles', '/roles', 'fa fa-user-circle', 0, 1, 1),
(5, 3, 'admin', 'departments', 'Departments', '/departments', 'fa fa-building-o', 1, 1, 1),
(6, 3, 'hrm', 'users', 'Users', '/users', 'fa fa-user-circle', 2, 1, 1),
(7, 3, 'admin', 'teams', 'Teams', '/teams', 'fa fa-universal-access', 3, 1, 1),
(9, 101, 'hrm', 'holidays', 'Holidays', '/holidays', 'fa fa-calendar-plus-o', 1, 1, 0),
(10, 0, 'meetings', 'meetings', 'Meetings', '/meetings', 'fa fa-group', 4, 1, 0),
(11, 0, 'clients', 'clients', 'Clients', '/clients', 'fa fa-user-circle', 10, 1, 1),
(12, 0, 'utilities', 'todos', 'Todos', '/todos', 'fa fa-list-ul', 1, 1, 1),
(13, 0, 'utilities', 'announcements', 'Announcements', '/announcements', 'fa fa-announcements', 2, 1, 1),
(14, 0, 'utilities', 'mailbox', 'Mailbox', '/mailbox', 'fa fa-envelope-o', 3, 1, 1),
(15, 0, 'filemanager', 'file manager', 'File Manager', '/file-browser', 'fa fa-folder', 11, 1, 1),
(16, 0, 'setting', 'settings', 'Settings', '/settings', 'fa fa-cogs', 13, 1, 1),
(17, 16, 'settings', 'company detail', 'Company Detail', '#', 'fa fa-info-circle', 0, 1, 1),
(18, 16, 'settings', 'email settings', 'Email Settings', '#', 'fa fa-envelope', 1, 1, 1),
(19, 16, 'settings', 'email Templates', 'Email Templates', '#', 'fa fa-pencil-square', 2, 1, 1),
(20, 16, 'settings', 'email notifications', 'Email Notifications', '#', 'fa fa-bell-o', 3, 1, 1),
(21, 16, 'settings', 'cronjob', 'Cronjob', '#', 'fa fa-contao', 4, 1, 1),
(22, 16, 'settings', 'menu allocation', 'Menu Allocation', '#', 'fa fa-language', 5, 1, 1),
(23, 16, 'settings', 'theme settings', 'Theme Settings', '#', 'fa fa-compass', 6, 1, 1),
(24, 16, 'settings', 'dashboard settings', 'Dashboard Settings', '#', 'fa fa-cog', 7, 1, 1),
(25, 16, 'settings', 'system settings', 'System Settings', '#', 'fa fa-desktop', 8, 1, 1),
(27, 16, 'settings', 'backup database', 'Backup Database', '#', 'fa fa-database', 9, 1, 1),
(28, 16, 'settings', 'custom fields', 'Custom Fields', '#', 'fa fa-list-alt', 10, 1, 1),
(29, 101, 'pm', 'timesheet', 'Timesheet', '/timesheet', 'fa fa-clock-o', 2, 1, 0),
(30, 16, 'settings', 'translations', 'Translations', '#', 'fa fa-language', 11, 1, 1),
(31, 16, 'settings', 'leave types', 'Leave Types', '#', 'fa fa-calendar-times-o', 12, 1, 1),
(32, 16, 'settings', 'estimate settings', 'Estimate Settings', '#', 'fa fa-external-link', 13, 1, 1),
(33, 16, 'settings', 'invoice settings', 'Invoice Settings', '#', 'fa fa-file-pdf-o', 14, 1, 1),
(34, 16, 'settings', 'payment method', 'Payment Method', '#', 'fa fa-th', 15, 1, 1),
(35, 16, 'settings', 'payment gateway settings', 'Payment Gateway Settings', '#', 'fa fa-credit-card', 16, 1, 1),
(36, 16, 'settings', 'slack settings', 'Slack Settings', '#', 'fa fa-slack', 17, 1, 1),
(41, 0, 'pm', 'project management', 'PM', '#', 'fa fa-product-hunt', 0, 1, 0),
(42, 0, 'pm', 'project planner', 'Project Planner', '/projects-planner', 'fa fa-american-sign-language-interpreting', 2, 1, 0),
(43, 0, 'pm', 'projects', 'Projects', '/projects', 'fa fa-product-hunt', 7, 1, 1),
(44, 0, 'pm', 'tasks', 'Tasks', '/tasks', 'fa fa-tasks', 8, 1, 1),
(46, 0, 'pm', 'defects', 'Defects', '/defects', 'fa fa-bug', 9, 1, 1),
(47, 0, 'pm', 'incidents', 'Incidents', '/incidents', 'fa fa-ticket', 3, 1, 0),
(49, 0, 'pm', 'knowledge base', 'Knowledge Base', '/knowledgebase', 'fa fa-graduation-cap', 7, 1, 0),
(51, 0, 'pm', 'reports', 'Reports', '/reports', 'fa fa-area-chart', 12, 1, 1),
(71, 0, 'crm', 'appointment', 'Appointments', '/appointments', 'fa fa-calendar-plus-o', 6, 1, 0),
(101, 0, 'hrm', 'hrm', 'HRM', '#', 'fa fa-users', 1, 1, 0),
(102, 101, 'hrm', 'leaves', 'Leaves', '/leaves', 'fa fa-calendar-times-o', 0, 1, 0),
(111, 0, 'sales', 'sales', 'Sales', '#', 'fa fa-balance-scale', 6, 1, 1),
(112, 0, 'sales', 'estimates', 'Estimates', '/estimates', 'fa fa-external-link', 5, 1, 0),
(113, 111, 'sales', 'invoices', 'Invoices', '/invoices', 'fa fa-file-pdf-o', 0, 1, 1),
(114, 111, 'sales', 'payments', 'Payments', '/payments', 'fa fa-money', 1, 1, 1),
(115, 111, 'sales', 'items', 'Items', '/items', 'fa fa-shopping-basket', 2, 1, 1),
(116, 111, 'sales', 'taxes', 'Taxes', '/taxes', 'fa fa-percent', 3, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kaajbook__menus`
--
ALTER TABLE `kaajbook__menus`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kaajbook__menus`
--
ALTER TABLE `kaajbook__menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
