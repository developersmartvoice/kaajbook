ALTER TABLE `vipspm_users` ADD `primary_manager` INT NULL AFTER `mother_name`, ADD `secondary_manager` INT NULL AFTER `primary_manager`;
ALTER TABLE `vipspm_users` ADD `state` VARCHAR(50) NULL AFTER `city`;
ALTER TABLE `vipspm_users` ADD `postal_code` VARCHAR(10) NULL AFTER `country`;

ALTER TABLE `vipspm_user_settings` ADD `is_demo` BOOLEAN NOT NULL DEFAULT FALSE AFTER `product_version`;
ALTER TABLE `vipspm_user_settings` ADD `allowed_for_registration` TINYINT(1) NOT NULL DEFAULT '1' AFTER `product_version`;
ALTER TABLE `vipspm_user_settings` ADD `currency_id` INT UNSIGNED NULL AFTER `date_time_format`;

INSERT INTO `vipspm_menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES ('31', '16', 'settings', 'leave types', 'Leave Types', '#', 'fa fa-calendar-times-o', '14', '1', '1');
INSERT INTO `vipspm_menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES ('101', '0', 'hrm', 'hrm', 'HRM', '#', 'fa fa-users', '3', '1', '1');
INSERT INTO `vipspm_menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES ('102', '101', 'hrm', 'leaves', 'Leaves', '/leaves', 'fa fa-calendar-times-o', '2', '1', '1');
UPDATE `vipspm_menus` SET `parent_menu_id` = '101', `module` = 'hrm', `order` = '1' WHERE `vipspm_menus`.`id` = 6;
UPDATE `vipspm_menus` SET `parent_menu_id` = '101', `module` = 'hrm', `order` = '3' WHERE `vipspm_menus`.`id` = 9;
UPDATE `vipspm_menus` SET `parent_menu_id` = '101', `order` = '4' WHERE `vipspm_menus`.`id` = 29;

INSERT INTO `vipspm_menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES ('32', '16', 'settings', 'estimate settings', 'Estimate Settings', '#', 'fa fa-external-link', '16', '1', '1');
INSERT INTO `vipspm_menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES ('33', '16', 'settings', 'invoice settings', 'Invoice Settings', '#', 'fa fa-file-pdf-o', '17', '1', '1');
INSERT INTO `vipspm_menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES ('34', '16', 'settings', 'payment method', 'Payment Method', '#', 'fa fa-th', '18', '1', '1');
INSERT INTO `vipspm_menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES ('35', '16', 'settings', 'payment gateway settings', 'Payment Gateway Settings', '#', 'fa fa-credit-card', '19', '1', '1');
INSERT INTO `vipspm_menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES ('111', '0', 'sales', 'sales', 'Sales', '#', 'fa fa-balance-scale', '4', '1', '1');
INSERT INTO `vipspm_menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES ('112', '111', 'sales', 'estimates', 'Estimates', '/estimates', 'fa fa-external-link', '1', '1', '1');
INSERT INTO `vipspm_menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES ('113', '111', 'sales', 'invoices', 'Invoices', '/invoices', 'fa fa-file-pdf-o', '2', '1', '1');
INSERT INTO `vipspm_menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES ('114', '111', 'sales', 'payments', 'Payments', '/payments', 'fa fa-money', '3', '1', '1');
INSERT INTO `vipspm_menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES ('115', '111', 'sales', 'items', 'Items', '/items', 'fa fa-shopping-basket', '4', '1', '1');
INSERT INTO `vipspm_menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES ('116', '111', 'sales', 'taxes', 'Taxes', '/taxes', 'fa fa-percent', '5', '1', '1');

INSERT INTO `vipspm_menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES ('36', '16', 'settings', 'slack settings', 'Slack Settings', '#', 'fa fa-slack', '20', '1', '1');

UPDATE `vipspm_menus` SET `order` = '1' WHERE `vipspm_menus`.`id` = 1;
UPDATE `vipspm_menus` SET `order` = '2' WHERE `vipspm_menus`.`id` = 3;

UPDATE `vipspm_menus` SET `module` = 'utilities' WHERE `vipspm_menus`.`id` = 2;
UPDATE `vipspm_menus` SET `text` = 'PM' WHERE `vipspm_menus`.`id` = 41;

INSERT INTO `vipspm_email_template_groups` (`id`, `email_group_name`, `created_at`, `updated_at`, `deleted_at`) VALUES ('7', 'Leave Emails', NULL, NULL, NULL);
INSERT INTO `vipspm_email_template` (`id`, `email_group_id`, `template_name`, `template_subject`, `template_body`, `type`, `deleted_at`, `created_at`, `updated_at`) VALUES ('28', '7', 'Leave Request', 'Leave Request', 'Hello Sir/Ma\'am,<br><br>I am writing to request for a {LEAVE_TYPE} Leave on {DATE}, due to {REASON}. Kindly grant me the leave.<br><br>Best Regards,<br>{NAME}', 'leave_request', NULL, NULL, NULL);
INSERT INTO `vipspm_email_template` (`id`, `email_group_id`, `template_name`, `template_subject`, `template_body`, `type`, `deleted_at`, `created_at`, `updated_at`) VALUES ('29', '7', 'Approve Leave', 'Approval Letter for Leave Application', 'Hello {NAME},<br><br>We have gone through your request for your {LEAVE_TYPE} Leave. We are happy to grant you leave request on {DATE}.<br><br>We request you to complete all your pending work or any other important issue so that the company does not face any loss or problem during your absence.<br><br>Have a good time.<br><br>Best Regards,<br>{NAME}', 'approve_leave', NULL, NULL, NULL);
INSERT INTO `vipspm_email_template` (`id`, `email_group_id`, `template_name`, `template_subject`, `template_body`, `type`, `deleted_at`, `created_at`, `updated_at`) VALUES ('30', '7', 'Reject Leave', 'Rejection Letter for Leave Application', 'Dear {NAME},<br><br>After reviewing your request, I am sorry to say that I am unable to grant you {LEAVE_TYPE} Leave on {DATE}. While {REJECT_REASON}.<br><br>Best Regards,<br>{NAME}', 'reject_leave', NULL, NULL, NULL);

INSERT INTO `vipspm_email_template_groups` (`id`, `email_group_name`, `created_at`, `updated_at`, `deleted_at`) VALUES ('8', 'Estimate Emails', NULL, NULL, NULL);
INSERT INTO `vipspm_email_template` (`id`, `email_group_id`, `template_name`, `template_subject`, `template_body`, `type`, `deleted_at`, `created_at`, `updated_at`) VALUES ('31', '8', 'Send Estimate to Customer', 'Estimate Created # {ESTIMATE_NUMBER}', 'Dear {CUSTOMER_NAME},<br><br>We have created an estimate for you in the amount of {AMOUNT}.<br><br>Please find the attached estimate <b># {ESTIMATE_NUMBER}</b><br><br>You can view the estimate on the following link: <a href="{ESTIMATE_URL}">{ESTIMATE_NUMBER}</a><br><br>We are looking forward to your reply.<br><br>Best Regards,<br>The {COMPANY_NAME} Team', 'send_estimate', NULL, NULL, NULL);
INSERT INTO `vipspm_email_template` (`id`, `email_group_id`, `template_name`, `template_subject`, `template_body`, `type`, `deleted_at`, `created_at`, `updated_at`) VALUES ('32', '8', 'Estimate Declined', 'Customer Declined Estimate # {ESTIMATE_NUMBER}', 'Hi,<br><br>{CUSTOMER_NAME} declined the estimate <b># {ESTIMATE_NUMBER}</b><br><br>You can view the estimate on the following link: <a href="{ESTIMATE_URL}">{ESTIMATE_NUMBER}</a><br><br>{CUSTOMER_NAME}', 'estimate_declined', NULL, NULL, NULL);
INSERT INTO `vipspm_email_template` (`id`, `email_group_id`, `template_name`, `template_subject`, `template_body`, `type`, `deleted_at`, `created_at`, `updated_at`) VALUES ('33', '8', 'Estimate Accepted', 'Customer Accepted Estimate # {ESTIMATE_NUMBER}', 'Hi,<br><br>{CUSTOMER_NAME} accepted the estimate <b># {ESTIMATE_NUMBER}</b><br><br>You can view the estimate on the following link: <a href="{ESTIMATE_URL}">{ESTIMATE_NUMBER}</a><br><br>{CUSTOMER_NAME}', 'estimate_accepted', NULL, NULL, NULL);
INSERT INTO `vipspm_email_template` (`id`, `email_group_id`, `template_name`, `template_subject`, `template_body`, `type`, `deleted_at`, `created_at`, `updated_at`) VALUES ('34', '8', 'Thank You Email', 'Thank you for accepting estimate # {ESTIMATE_NUMBER}', 'Dear {CUSTOMER_NAME},<br><br>Thank you for accepting the estimate <b># {ESTIMATE_NUMBER}</b><br><br>We are looking forward to doing business with you.<br><br>We will contact you as soon as possible.<br><br>Best Regards,<br>The {COMPANY_NAME} Team', 'thank_you_for_accepting_estimate', NULL, NULL, NULL);
INSERT INTO `vipspm_email_template` (`id`, `email_group_id`, `template_name`, `template_subject`, `template_body`, `type`, `deleted_at`, `created_at`, `updated_at`) VALUES ('35', '8', 'Estimate Expiration Reminder', 'Estimate Expiration Reminder # {ESTIMATE_NUMBER}', 'Hello {CUSTOMER_NAME},<br><br>The estimate <b># {ESTIMATE_NUMBER}</b> will expire on <b>{ESTIMATE_VALID_TILL}</b><br><br>You can view the estimate on the following link: <a href="{ESTIMATE_URL}">{ESTIMATE_NUMBER}</a><br><br>Best Regards,<br>The {COMPANY_NAME} Team', 'estimate_expiration_reminder', NULL, NULL, NULL);

INSERT INTO `vipspm_email_template_groups` (`id`, `email_group_name`, `created_at`, `updated_at`, `deleted_at`) VALUES ('9', 'Invoice Emails', NULL, NULL, NULL);
INSERT INTO `vipspm_email_template` (`id`, `email_group_id`, `template_name`, `template_subject`, `template_body`, `type`, `deleted_at`, `created_at`, `updated_at`) VALUES ('36', '9', 'Send Invoice to Customer', 'Invoice Created # {INVOICE_NUMBER}', 'Dear {CUSTOMER_NAME},<br><br>We have created an invoice for you in the amount of {AMOUNT}.<br><br>Please find the attached invoice <b># {INVOICE_NUMBER}</b><br><br>You can view the invoice on the following link: <a href=\"{INVOICE_URL}\">{INVOICE_NUMBER}</a><br><br>Don’t hesitate to reach out if you have any questions.<br><br>Best Regards,<br>The {COMPANY_NAME} Team', 'send_invoice', NULL, NULL, NULL);
INSERT INTO `vipspm_email_template` (`id`, `email_group_id`, `template_name`, `template_subject`, `template_body`, `type`, `deleted_at`, `created_at`, `updated_at`) VALUES ('37', '9', 'Invoice Overdue Notice', 'Invoice Overdue Notice # {INVOICE_NUMBER}', 'Dear {CUSTOMER_NAME},<br><br>I hope you are doing well.<br><br>This is an overdue notice for invoice <b># {INVOICE_NUMBER}</b><br><br>This invoice was due on <b>{DUE_DATE}</b><br><br>You can view the invoice on the following link: <a href=\"{INVOICE_URL}\">{INVOICE_NUMBER}</a><br><br>Please let me know in case of any questions.<br><br>Best Regards,<br>The {COMPANY_NAME} Team', 'invoice_overdue_notice', NULL, NULL, NULL);

INSERT INTO `vipspm_email_template_groups` (`id`, `email_group_name`, `created_at`, `updated_at`, `deleted_at`) VALUES ('10', 'Payment Emails', NULL, NULL, NULL);
INSERT INTO `vipspm_email_template` (`id`, `email_group_id`, `template_name`, `template_subject`, `template_body`, `type`, `deleted_at`, `created_at`, `updated_at`) VALUES ('38', '10', 'Payment Added (Sent to Customer)', 'Payment Added Against # {INVOICE_NUMBER}', 'Hello {CUSTOMER_NAME},<br><br>Thank you for the payment.<br><br>Please find the attached payment receipt # <b>{RECEIPT_NUMBER}</b><br><br>You can always view the invoice for this payment at the following link: <a href=\"{INVOICE_URL}\"><b>{INVOICE_NUMBER}</b></a><br><br>We are looking forward working with you.<br><br>Best Regards,<br>The {COMPANY_NAME} Team', 'payment_added_send_to_customer', NULL, NULL, NULL);
INSERT INTO `vipspm_email_template` (`id`, `email_group_id`, `template_name`, `template_subject`, `template_body`, `type`, `deleted_at`, `created_at`, `updated_at`) VALUES ('39', '10', 'Payment Added (Sent to Staff)', 'Payment Added Against # {INVOICE_NUMBER}', 'Hello,<br><br>Customer added payment for invoice # <b>{INVOICE_NUMBER}</b><br><br>You can view the invoice for this payment at the following link: <a href=\"{INVOICE_URL}\">{INVOICE_NUMBER}</a><br><br>{CUSTOMER_NAME}', 'payment_added_send_to_staff', NULL, NULL, NULL);

UPDATE `vipspm_currency` SET `symbol` = '₹' WHERE `vipspm_currency`.`id` = 16;

INSERT INTO `vipspm_modules` (`id`, `module_id`, `module_name`, `module_slug`, `status`) VALUES (NULL, '7', 'Leave', 'leave', '1');

INSERT INTO `vipspm_translations` (`id`, `name`, `language`, `icon`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES (NULL, 'Vietnamese', 'vi', 'Vietnam.png', '1', NULL, NULL, NULL);

ALTER TABLE `vipspm_user_settings`
  DROP `is_announcement_dashboard`,
  DROP `is_activities_dashboard`,
  DROP `is_todos_dashboard`,
  DROP `is_meetings_dashboard`,
  DROP `is_projects_dashboard`,
  DROP `is_tasks_dashboard`,
  DROP `is_defects_dashboard`,
  DROP `is_incidents_dashboard`;

ALTER TABLE `vipspm_project_attachments` CHANGE `file_description` `file_extension` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `vipspm_task_attachments` CHANGE `file_description` `file_extension` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `vipspm_defect_attachments` CHANGE `file_description` `file_extension` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `vipspm_incident_attachments` CHANGE `file_description` `file_extension` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `vipspm_countries` ADD `code` VARCHAR(255) NOT NULL AFTER `id`;

ALTER TABLE `vipspm_defects_history` CHANGE `description` `description` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `vipspm_incident_history` CHANGE `description` `description` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;