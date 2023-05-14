DELETE FROM `vipspm_menus` WHERE `vipspm_menus`.`id` = 50;

UPDATE `vipspm_menus` SET `parent_menu_id` = '0', `module` = 'meetings' WHERE `vipspm_menus`.`id` = 10;

UPDATE `vipspm_menus` SET `parent_menu_id` = '0', `module` = 'crm' WHERE `vipspm_menus`.`id` = 11;

INSERT INTO `vipspm_menus` (`id`, `parent_menu_id`, `module`, `label`, `text`, `link`, `icon`, `order`, `subscription`, `status`) VALUES ('30', '16', 'settings', 'translations', 'Translations', '#', 'fa fa-language', '13', '1', '1');

ALTER TABLE `vipspm_projects` ADD `actual_hours` VARCHAR(255) NULL AFTER `estimated_hours`;

ALTER TABLE `vipspm_tasks` ADD `actual_hours` VARCHAR(255) NULL AFTER `estimated_hours`;

ALTER TABLE `vipspm_defects` CHANGE `assign_member` `assign_member` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `vipspm_defects` ADD `estimated_hours` VARCHAR(255) NULL AFTER `end_date`, ADD `actual_hours` VARCHAR(255) NULL AFTER `estimated_hours`;

ALTER TABLE `vipspm_incidents` CHANGE `assign_to` `assign_to` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `vipspm_incidents` ADD `estimated_hours` VARCHAR(255) NULL AFTER `end_date`, ADD `actual_hours` VARCHAR(255) NULL AFTER `estimated_hours`;

ALTER TABLE `vipspm_project_sprint_tasks` CHANGE `project_sprint_id` `project_sprint_id` INT(10) UNSIGNED NULL;
ALTER TABLE `vipspm_project_sprint_tasks` ADD `project_id` INT(10) NULL AFTER `project_sprint_id`;
ALTER TABLE `vipspm_project_sprint_tasks` ADD `assign_to` TINYINT(4) NULL AFTER `hours`;
ALTER TABLE `vipspm_project_sprint_tasks` CHANGE `status` `status` TINYINT(4) NOT NULL DEFAULT '1' COMMENT 'Task:1=Open, 2=OnHold, 3=Closed, 4=Released|Story:1=Open, 2=InProgress, 3=Closed';
ALTER TABLE `vipspm_project_sprint_tasks` CHANGE `task_name` `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `vipspm_project_sprint_tasks` CHANGE `hours` `estimated_hours` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `vipspm_clients` CHANGE `company_name` `company_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `vipspm_clients` CHANGE `company_email` `company_email` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `vipspm_clients` CHANGE `company_mobile` `company_mobile` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;

INSERT INTO `vipspm_translations` (`id`, `name`, `language`, `icon`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES (NULL, 'Dutch', 'nl', 'Netherlands.png', '1', NULL, NULL, NULL);
INSERT INTO `vipspm_translations` (`id`, `name`, `language`, `icon`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES (NULL, 'Portuguese', 'pt', 'Portugal.png', '1', NULL, NULL, NULL);
INSERT INTO `vipspm_translations` (`id`, `name`, `language`, `icon`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES (NULL, 'Turkish', 'tr', 'Turkey.png', '1', NULL, NULL, NULL);

ALTER TABLE `vipspm_custom_fields` CHANGE `show_on_details` `show_on_details` TINYINT(1) NOT NULL DEFAULT '1';

UPDATE `vipspm_email_template` SET `template_body` = '<p>Hi there,</p><p>A new task <strong>{TASK_NAME}</strong> &nbsp;has been assigned to <strong>{ASSIGNED_TO}</strong> by <strong>{ASSIGNED_BY}</strong>.</p><p>You can view this task by logging in to the portal using the link below.</p><p><big><strong><a href=\"{TASK_URL}\">View Task</a></strong></big><br><br>Regards<br>The {SITE_NAME} Team</p>', `deleted_at` = NULL, `created_at` = NULL WHERE `vipspm_email_template`.`id` = 14;

UPDATE `vipspm_email_template` SET `template_body` = '<p>Hi there,</p><p>A new defect &nbsp;{DEFECT_TITLE} &nbsp;has been assigned to <strong>{ASSIGNED_TO}</strong> by <strong>{ASSIGNED_BY}</strong>.</p><p>You can view this defect by logging in to the portal using the link below.</p><p><br><big><strong><a href=\"{DEFECT_URL}\">View Defect</a></strong></big><br><br>Regards<br>The {SITE_NAME} Team</p>', `deleted_at` = NULL, `created_at` = NULL WHERE `vipspm_email_template`.`id` = 18;

UPDATE `vipspm_email_template` SET `template_body` = '<p>Hi there,</p><p>A new incident &nbsp;<big><strong>{INCIDENT_TITLE}</big></strong> &nbsp;has been assigned to <strong>{ASSIGNED_TO}</strong> by <strong>{ASSIGNED_BY}</strong>.</p><p>You can view this incident by logging in to the portal using the link below.</p><p><br><big><strong><a href=\"{INCIDENT_URL}\">View Incident</a></strong></big><br><br>Regards<br>The {SITE_NAME} Team</p>', `deleted_at` = NULL, `created_at` = NULL, `updated_at` = NULL WHERE `vipspm_email_template`.`id` = 22;

INSERT INTO `vipspm_email_template` (`id`, `email_group_id`, `template_name`, `template_subject`, `template_body`, `type`, `deleted_at`, `created_at`, `updated_at`) VALUES (NULL, '1', 'Cancel Meeting', 'Cancel Meeting', 'Hi <strong>{NAME}</strong>,<div><br></div><div>The meeting scheduled on <strong>{MEETING_DATE}</strong> <strong>{MEETING_TIME}</strong> at <strong>{LOCATION}</strong> has been canceled.</div><br><div>{DESCRIPTION}</div><br><div>Have fun!<br>The&nbsp;<strong>{COMPANY_NAME}</strong>&nbsp;Team.<br></div>', 'cancel_meeting', NULL, NULL, NULL);

ALTER TABLE `vipspm_meetings` ADD `generated_id` VARCHAR(255) NOT NULL AFTER `id`;
ALTER TABLE `vipspm_meetings` ADD `client_id` INT NULL AFTER `organizer_id`;
ALTER TABLE `vipspm_meetings` CHANGE `status` `status` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '1=Open,2=In Progress,3=Cancel,4=Completed';
UPDATE
  vipspm_meetings AS t1,
  (SELECT
    id,
    CONCAT ('M', LPAD (id, 5, '0')) AS generated_id
  FROM
    vipspm_meetings) AS t2
SET
  t1.generated_id = t2.generated_id
WHERE t1.id = t2.id;

ALTER TABLE `vipspm_appointment` ADD `generated_id` VARCHAR(255) NOT NULL AFTER `id`;
UPDATE
  vipspm_appointment AS t1,
  (SELECT
    id,
    CONCAT ('A', LPAD (id, 5, '0')) AS generated_id
  FROM
    vipspm_appointment) AS t2
SET
  t1.generated_id = t2.generated_id
WHERE t1.id = t2.id;