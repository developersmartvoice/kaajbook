DELETE FROM `vipspm_menus` WHERE `vipspm_menus`.`id` = 8;
DELETE FROM `vipspm_menus` WHERE `vipspm_menus`.`id` = 26;
DELETE FROM `vipspm_menus` WHERE `vipspm_menus`.`id` = 45;
DELETE FROM `vipspm_menus` WHERE `vipspm_menus`.`id` = 48;
UPDATE `vipspm_menus` SET `parent_menu_id` = '0', `module` = 'utilities', `order` = '0' WHERE `vipspm_menus`.`id` = 9;

INSERT INTO `vipspm_menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES ('29', '0', 'pm', 'timesheet', 'Timesheet', '/timesheet', 'fa fa-clock-o', '10', '1', '1');
DELETE FROM `vipspm_menus` WHERE `vipspm_menus`.`id` = 71;
INSERT INTO `vipspm_menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES ('71', '0', 'crm', 'appointment', 'Appointments', '/appointments', 'fa fa-calendar-times-o', '3', '1', '1');

INSERT INTO `vipspm_email_template_groups` (`id`, `email_group_name`, `created_at`, `updated_at`, `deleted_at`) VALUES ('6', 'Appointment Emails', NULL, NULL, NULL);
INSERT INTO `vipspm_email_template` (`id`, `email_group_id`, `template_name`, `template_subject`, `template_body`, `type`, `deleted_at`, `created_at`, `updated_at`) VALUES ('26', '6', 'Appointment Mail', 'Appointment Mail', 'Hi <strong>{NAME}</strong>,<br><div>A appointment has been scheduled on <strong>{APPOINTEMENT_DATE}</strong>, in the <strong>{LOCATION}</strong> at <strong>{APPOINTEMENT_TIME}</strong>.</div><br><div>{DESCRIPTION}</div><br><div>Have fun!<br>The&nbsp;<strong>{COMPANY_NAME}</strong>&nbsp;Team.<br></div>', 'appointment_mail', NULL, NULL, NULL);

INSERT INTO `vipspm_modules` (`id`, `module_id`, `module_name`, `module_slug`, `status`) VALUES
(1, 1, 'Project', 'projects', 1),
(2, 2, 'Task', 'task', 1),
(3, 3, 'Defect', 'defect', 1),
(4, 4, 'Incident', 'incident', 1),
(5, 5, 'Meeting', 'meeting', 1),
(6, 6, 'Other', 'other', 1);

INSERT INTO `vipspm_translations` (`id`, `name`, `language`, `icon`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES 
(NULL, 'English', 'en', 'United-States.png', '1', NULL, NULL, NULL), 
(NULL, 'Chinese', 'zh', 'China.png', '1', NULL, NULL, NULL), 
(NULL, 'Czech', 'cs', 'Czech-Republic.png', '1', NULL, NULL, NULL), 
(NULL, 'French', 'fr', 'France.png', '1', NULL, NULL, NULL), 
(NULL, 'German', 'de', 'Germany.png', '1', NULL, NULL, NULL), 
(NULL, 'Italian', 'it', 'Italy.png', '1', NULL, NULL, NULL), 
(NULL, 'Japanese', 'ja', 'Japan.png', '1', NULL, NULL, NULL), 
(NULL, 'Russian', 'ru', 'Russia.png', '1', NULL, NULL, NULL), 
(NULL, 'Spanish', 'es', 'Spain.png', '1', NULL, NULL, NULL);

ALTER TABLE `vipspm_projects` ADD `generated_id` VARCHAR(255) NOT NULL AFTER `user_id`;
ALTER TABLE `vipspm_projects` CHANGE `client_id` `client_id` INT(11) NULL;
ALTER TABLE `vipspm_projects` CHANGE `assign_to` `assign_to` TINYINT(4) NULL;

UPDATE
  vipspm_projects AS t1,
  (SELECT
    id,
    CONCAT ('P', LPAD (id, 5, '0')) AS generated_id
  FROM
    vipspm_projects) AS t2
SET
  t1.generated_id = t2.generated_id
WHERE t1.id = t2.id;

ALTER TABLE `vipspm_user_settings` ADD `time_format` VARCHAR(50) NOT NULL DEFAULT 'HH:mm' after `date_format`; 
ALTER TABLE `vipspm_user_settings` ADD `date_time_format` VARCHAR(50) NOT NULL DEFAULT 'YYYY-MM-DD hh:mm A' after `time_format`; 
ALTER TABLE `vipspm_user_settings` ADD `daily_report_emails` TEXT NULL AFTER `automatic_backup`;

ALTER TABLE `vipspm_projects` CHANGE `price_rate` `price_rate` DOUBLE(15,8) NULL;
ALTER TABLE `vipspm_projects` CHANGE `billing_type` `billing_type` TINYINT(4) NULL COMMENT '1=Fixed Rate,2=Hourly Rate';

ALTER TABLE `vipspm_project_sprints` CHANGE `start_date` `start_date` DATE NULL;
ALTER TABLE `vipspm_project_sprints` CHANGE `end_date` `end_date` DATE NULL;

ALTER TABLE `vipspm_project_sprint_tasks` CHANGE `start_date` `start_date` DATE NULL;
ALTER TABLE `vipspm_project_sprint_tasks` CHANGE `end_date` `end_date` DATE NULL;

ALTER TABLE `vipspm_tasks` CHANGE `project_version` `project_version` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `vipspm_tasks` CHANGE `planned_start_date` `planned_start_date` DATE NULL;
ALTER TABLE `vipspm_tasks` CHANGE `planned_end_date` `planned_end_date` DATE NULL;
ALTER TABLE `vipspm_tasks` CHANGE `task_start_date` `task_start_date` DATETIME NULL;
ALTER TABLE `vipspm_tasks` CHANGE `task_end_date` `task_end_date` DATETIME NULL;
ALTER TABLE `vipspm_tasks` CHANGE `assign_to` `assign_to` TINYINT(4) NULL;

ALTER TABLE `vipspm_defects` ADD `start_date` DATE NULL AFTER `defect_name`, ADD `end_date` DATE NULL AFTER `start_date`;
ALTER TABLE `vipspm_defects` CHANGE `project_version` `project_version` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `vipspm_defects` CHANGE `assigned_group_id` `assigned_group_id` INT(11) NULL;
ALTER TABLE `vipspm_defects` CHANGE `assign_member` `assign_member` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `vipspm_defects` ADD `orders` INT(11) NULL AFTER `attachment_hash`;

ALTER TABLE `vipspm_incidents` ADD `start_date` DATE NULL AFTER `incident_name`, ADD `end_date` DATE NULL AFTER `start_date`;
ALTER TABLE `vipspm_incidents` CHANGE `assigned_group_id` `assigned_group_id` INT(11) NULL;
ALTER TABLE `vipspm_incidents` ADD `orders` INT(11) NULL AFTER `notes`;

ALTER TABLE `vipspm_project_sprints` CHANGE `status` `status` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '1=Open,2=InProgress,3=Closed';

ALTER TABLE `vipspm_project_sprint_tasks` CHANGE `status` `status` TINYINT(4) NOT NULL DEFAULT '1' COMMENT 'Task:1=Open,2=InProgress,3=OnHold,4=Waiting,5=Cancel,6=Completed|Story:1=Open,2=InProgress,3=Closed';

ALTER TABLE `vipspm_user_activities` CHANGE `status` `diff_for_humans` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `vipspm_user_activities` ADD `self_parent_id` INT NULL AFTER `module`;

ALTER TABLE `vipspm_meetings` ADD `hour_time` VARCHAR(10) NULL AFTER `end_date`;