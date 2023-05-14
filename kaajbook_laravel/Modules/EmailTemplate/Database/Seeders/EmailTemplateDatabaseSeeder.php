<?php

namespace Modules\EmailTemplate\Database\Seeders;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\EmailTemplate\Models\EmailTemplate;

/**
 * Class EmailTemplateDatabaseSeeder
 *
 * The Seeder is Defined for Email Template.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\EmailTemplate
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class EmailTemplateDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // --
        // Email template
        DB::table(config('core.acl.email_template_table'))->delete();
        $data = [
            [
                'id' => 1,
                'email_group_id' => 1,
                'template_name' => 'Activate Account',
                'template_subject' => 'Activate Account',
                'template_body' => '<p>Welcome to {SITE_NAME}!</p><p>Thanks for joining {SITE_NAME}. We listed your sign in details below, make sure you keep them safe.</p><p>To verify your email address, please follow this link:<br><big><strong><a href="{ACTIVATE_URL}">Finish your registration...</a></strong></big><br>Link doesn\'t work? Copy the following link to your browser address bar:<br><a href="{ACTIVATE_URL}">{ACTIVATE_URL}</a></p><p><br>Please verify your email within {ACTIVATION_PERIOD} hours, otherwise your registration will become invalid and you will have to register again.<br>Your username: {USERNAME}<br>Your email address: {EMAIL}<br>Your password: {PASSWORD}<br><br>Have fun!<br>The {SITE_NAME} Team</p>',
                'type' => 'activate_account',
            ],
            [
                'id' => 2,
                'email_group_id' => 1,
                'template_name' => 'Change Email',
                'template_subject' => 'Change Email',
                'template_body' => '<p>New email address on {SITE_NAME}</p><p>You have changed your email address for {SITE_NAME}.<br>Follow this link to confirm your new email address:<br><big><strong><a href="{NEW_EMAIL_KEY_URL}">Confirm your new email</a></strong></big><br>Link doesn\'t work? Copy the following link to your browser address bar:<br><a href="{NEW_EMAIL_KEY_URL}">{NEW_EMAIL_KEY_URL}</a><br><br>Your email address: {NEW_EMAIL}<br><br>You received this email, because it was requested by a <a href="{SITE_URL}">{SITE_NAME}</a> user. If you have received this by mistake, please DO NOT click the confirmation link, and simply delete this email. After a short time, the request will be removed from the system.<br><br>Thank you,<br>The {SITE_NAME} Team</p>',
                'type' => 'change_email',
            ],
            [
                'id' => 3,
                'email_group_id' => 1,
                'template_name' => 'Forgot Password',
                'template_subject' => 'Forgot Password',
                'template_body' => '<p>Forgot your password, huh? No big deal.<br>To create a new password, just follow this link:<br><br><big><strong><a href="{PASS_KEY_URL}">Create a new password</a></strong></big><br>Link doesn\'t work? Copy the following link to your browser address bar:<br><a href="{PASS_KEY_URL}">{PASS_KEY_URL}</a><br>You received this email, because it was requested by a <a href="{SITE_URL}">{SITE_NAME}</a> user.</p><p>This is part of the procedure to create a new password on the system. If you DID NOT request a new password then please ignore this email and your password will remain the same.</p><p><br>Thank you,<br>The {SITE_NAME} Team</p>',
                'type' => 'forgot_password',
            ],
            [
                'id' => 4,
                'email_group_id' => 1,
                'template_name' => 'Register Email',
                'template_subject' => 'Register Email',
                'template_body' => '<p>Welcome to {SITE_NAME}</p><p>Thanks for joining {SITE_NAME}. We listed your sign in details below, make sure you keep them safe.<br>To open your {SITE_NAME} homepage, please follow this link:<br><big><strong><a href="{SITE_URL}">{SITE_NAME} Account!</a></strong></big><br>Link doesn\'t work? Copy the following link to your browser address bar:<br><a href="{SITE_URL}">{SITE_URL}</a><br>Your username: {USERNAME}<br>Your email address: {EMAIL}<br>Your password: {PASSWORD}<br>Have fun!<br>The {SITE_NAME} Team.<br>&nbsp;</p>',
                'type' => 'register_email',
            ],
            [
                'id' => 5,
                'email_group_id' => 1,
                'template_name' => 'Reset Password',
                'template_subject' => 'Reset Password',
                'template_body' => '<p>New password on {SITE_NAME}</p><p>You have changed your password.<br>Please, keep it in your records so you don\'t forget it.</p><p>Your username: {USERNAME}<br>Your email address: {EMAIL}<br>Your new password: {NEW_PASSWORD}<br><br>Thank you,<br>The {SITE_NAME} Team</p>',
                'type' => 'reset_password',
            ],
            [
                'id' => 6,
                'email_group_id' => 1,
                'template_name' => 'Welcome Email',
                'template_subject' => 'Welcome Email',
                'template_body' => '<p>Hello <strong>{NAME}</strong>,</p><p>Welcome to <strong>{COMPANY_NAME}</strong> .Thanks for joining <strong>{COMPANY_NAME}</strong>.</p><p>We just wanted to say welcome.</p><p>Please contact us if you need any help.</p><p>To verify your email address, please click on below link:<br><div style="text-align: center;"><big><strong><a href="{ACTIVATE_URL}">Finish Your Registration...</a></strong></big></div></p><p><br>Have fun!<br>The <strong>{COMPANY_NAME}</strong> Team.</p>',
                'type' => 'welcome_email',
            ],
            [
                'id' => 7,
                'email_group_id' => 1,
                'template_name' => 'New User Added',
                'template_subject' => 'New User Added #{USER_ID} {USER_NAME}', 
                'template_body' => '<p>Hello <b>{NAME}</b>,</p><p>New user <b>{USER_NAME}</b> has registered as a <b>{ROLE}</b> role.</p><p>You can view his/her profile by logging in to the portal using the below link:</p><p style="text-align: center;"><a href="{PROFILE_URL}"><big><b>View Profile</b></big></a></p><p>Best Regards,<br>The {COMPANY_NAME} Team</p>',
                'type' => 'new_user_added'
            ],
            [
                'id' => 8,
                'email_group_id' => 1,
                'template_name' => 'New Client Added',
                'template_subject' => 'NNew Client Added #{CLIENT_ID} {CLIENT_NAME}', 
                'template_body' => '<p>Hello <b>{NAME}</b>,</p><p>New client <b>{CLIENT_NAME}</b> has registered.</p><p>You can view his/her profile by logging in to the portal using the below link:</p><p style="text-align: center;"><a href="{PROFILE_URL}"><big><b>View Profile</b></big></a></p><p>Best Regards,<br>The {COMPANY_NAME} Team</p>',
                'type' => 'new_client_added'
            ],
            [
                'id' => 9,
                'email_group_id' => 1,
                'template_name' => 'Announcement',
                'template_subject' => 'Announcement',
                'template_body' => 'Hi <strong>{NAME}</strong>,<div><br></div><div><strong>{TITLE}</strong></div><div><br></div><div><strong>Start Date :</strong> {START_DATE}</div><div><strong>End Date :</strong> {END_DATE}</div><div><br></div><div>{DESCRIPTION}</div><div><br></div><div>Have fun!<br>The&nbsp;<strong>{COMPANY_NAME}</strong>&nbsp;Team.<br></div><div><br></div>',
                'type' => 'announcement',
            ],
            [
                'id' => 10,
                'email_group_id' => 2,
                'template_name' => 'Assigned Project',
                'template_subject' => 'Assigned Project #{PROJECT_ID} {PROJECT_NAME}',
                'template_body' => '<p>Hi There,</p><p>A<strong> {PROJECT_NAME}</strong> has been assigned by <strong>{ASSIGNED_BY} </strong>to you.You can view this project by logging in to the portal using the link below:<br><br><div style="text-align: center;"><big><a href="{PROJECT_URL}"><strong>View Project</strong></a></big></div><br><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br>Best Regards,<br>The {SITE_NAME} Team</p><p>&nbsp;</p>',
                'type' => 'assigned_project'
            ],
            [
                'id' => 11,
                'email_group_id' => 2,
                'template_name' => 'Notification Client',
                'template_subject' => 'New Project Created #{PROJECT_ID} {PROJECT_NAME}',
                'template_body' => '<p>Hello <strong>{CLIENT_NAME}</strong>,<br><br>We have created a new project with your account.<br>You can view this project by logging in to the portal using the link below:<br><div style="text-align: center;"><big><a href="{PROJECT_URL}"><strong>View Project</strong></a></big></div></p><p>Project Name: <b>{PROJECT_NAME}</b></p><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><p><br>Best Regards,<br>The {SITE_NAME} Team</p><p>&nbsp;</p>',
                'type' => 'notification_client'
            ],
            [
                'id' => 12,
                'email_group_id' => 2,
                'template_name' => 'Complete Projects',
                'template_subject' => 'Project Completed #{PROJECT_ID} {PROJECT_NAME}',
                'template_body' => '<p>Hi <strong>{CLIENT_NAME}</strong>,</p><p>Project <strong>{PROJECT_NAME}</strong> &nbsp;has been completed.<br>You can view this project by logging in to the portal using the link below:<br><div style="text-align: center;"><big><a href="{PROJECT_URL}"><strong>View Project</strong></a></big></div><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br><br>Best Regards,<br>The {SITE_NAME} Team</p>',
                'type' => 'complete_projects',
            ],
            [
                'id' => 13,
                'email_group_id' => 2,
                'template_name' => 'Project Update',
                'template_subject' => 'Project Updated #{PROJECT_ID} {PROJECT_NAME}',
                'template_body' => '<p>Hi There,</p><p>The Project <b> {PROJECT_NAME}</b> has been updated by <b>{UPDATED_BY} </b>.You can view this project by logging in to the portal using the below button:</p><div style="text-align: center;"><big><a href="{PROJECT_URL}"><b>View Project</b></a></big></div><br><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br>Best Regards,<br>The {SITE_NAME} Team</p><p>&nbsp;</p>',
                'type' => 'project_update'
            ],
            [
                'id' => 14,
                'email_group_id' => 2,
                'template_name' => 'Project Status Change',
                'template_subject' => 'Status Changed #{PROJECT_ID} {PROJECT_NAME}',
                'template_body' => '<p>Hi there,</p><p>The Project <strong>{PROJECT_NAME}</strong> &nbsp;has been marked as <b>{STATUS}</b> by <b>{MARKED_BY}</b>.</p><p>You can view the project by logging into your portal Account:</p><p><div style="text-align: center;"><b><a href="{PROJECT_URL}">View Project</a></b></div></p><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br>Best Regards,<br />{COMPANY_NAME} Team',
                'type' => 'project_status_change',
            ],
            [
                'id' => 15,
                'email_group_id' => 2,
                'template_name' => 'Project Comments',
                'template_subject' => 'New Project Comment Received',
                'template_body' => '<p>Hi There,</p><p>A new comment has been posted by <strong>{POSTED_BY}</strong> to project <strong>{PROJECT_NAME}</strong>.</p><p>You can view the comment using the link below:<br /><big><a href="{COMMENT_URL}"><strong>View Project</strong></a></big><br /><br /><em>{COMMENT_MESSAGE}</em><br /><br />Best Regards,<br />The {SITE_NAME} Team</p>',
                'type' => 'project_comments',
            ],
            [
                'id' => 16,
                'email_group_id' => 2,
                'template_name' => 'Project Attachment',
                'template_subject' => 'New Project Attachment',
                'template_body' => '<p>Hi There,</p><p>A new file has been uploaded by <strong>{UPLOADED_BY}</strong> to project <strong>{PROJECT_NAME}</strong>.<br />You can view the Project using the link below:<br><br><big><a href="{PROJECT_URL}"><strong>View Project</strong></a></big><br /><br />Best Regards,<br />The {SITE_NAME} Team</p>',
                'type' => 'project_attachment',
            ],
            [
                'id' => 17,
                'email_group_id' => 2,
                'template_name' => 'Project Reminder',
                'template_subject' => 'Reminder #{PROJECT_ID} {PROJECT_NAME}', 
                'template_body' => '<p>Hello {NAME},</p><p>The Project <b>{PROJECT_NAME}</b> assigned to you will be start on <b>{START_DATE}</b>.</p><p>You can view this project by logging in to the portal using the below link:</p><p></p><div style="text-align: center;"><b><a href="{PROJECT_URL}">View Project</a></b></div><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><p></p><p>Best Regards,<br>{COMPANY_NAME} Team</p>',
                'type' => 'project_reminder'
            ],
            [
                'id' => 18,
                'email_group_id' => 2,
                'template_name' => 'Project Overdue Reminder',
                'template_subject' => 'Project Overdue Reminder #{PROJECT_ID} {PROJECT_NAME}',
                'template_body' => '<p><b>Hello {NAME},</b></p><p>Hope You Are Well!<br></p><p>This is just to remind you that the project <b>{PROJECT_NAME}</b> is delayed by <b>{END_DATE}</b>. Its taking longer than expected,<br>Please review activities by login into portal using the link below:<br><big><p style="text-align: center;"><a href="{PROJECT_URL}"><strong>View Project</strong></a></p></big><br><p>Start Date: <b>{START_DATE}</b><br>Description: {DESCRIPTION}</p><br><p>Best Regards,<br>{COMPANY_NAME} Team</p>',
                'type' => 'project_overdue',
            ],
            [
                'id' => 19,
                'email_group_id' => 3,
                'template_name' => 'Task Assigned',
                'template_subject' => 'Task Assigned #{TASK_ID} {TASK_NAME}',
                'template_body' => '<p>Hi there,</p><p>A new task <strong>{TASK_NAME}</strong> &nbsp;has been assigned to <strong>{ASSIGNED_TO}</strong> by <strong>{ASSIGNED_BY}</strong>.</p><p>You can view this task by logging in to the portal using the link below.</p><p><div style="text-align: center;"><big><strong><a href="{TASK_URL}">View Task</a></strong></big></div><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br>Best Regards,<br>The {COMPANY_NAME} Team</p>',
                'type' => 'task_assigned'
            ],
            [
                'id' => 20,
                'email_group_id' => 3,
                'template_name' => 'Task Updated',
                'template_subject' => 'Task Updated #{TASK_ID} {TASK_NAME}',
                'template_body' => '<p>Hi there,</p><p><strong>{TASK_NAME}</strong> has been updated by <strong>{UPDATED_BY}</strong>.</p><p>You can view this Task by logging in to the portal using the link below.</p><p><br><div style="text-align: center;"><big><strong><a href="{TASK_URL}">View Task</a></strong></big></div><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br>Best Regards,<br>The {COMPANY_NAME} Team</p>',
                'type' => 'task_updated'
            ],
            [
                'id' => 21,
                'email_group_id' => 3,
                'template_name' => 'Task Status Change',
                'template_subject' => 'Status Changed #{TASK_ID} {TASK_NAME}', 
                'template_body' => '<p>Hi there,</p><p>The task <b>{TASK_NAME}</b> has been marked as <b>{STATUS}</b> by <b>{MARKED_BY}</b>.</p><p>You can view this task by logging in to the portal using the below button:</p><p><div style="text-align: center;"><b><a href="{TASK_URL}">View Task</a></b></div></p><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><p>Best Regards,<br>{COMPANY_NAME} Team</p>',
                'type' => 'task_status_change'
            ],
            [
                'id' => 22,
                'email_group_id' => 3,
                'template_name' => 'Task Reminder',
                'template_subject' => 'Reminder #{TASK_ID} {TASK_NAME}', 
                'template_body' => '<p>Hello {NAME},</p><p>The Task <b>{TASK_NAME}</b> assigned to you will be start on <b>{START_DATE}</b>.</p><p>You can view this task by logging in to the portal using the below button:</p><p></p><div style=\"text-align: center;\"><b><a href=\"{TASK_URL}\">View Task</a></b></div><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><p></p><p>Best Regards,<br>{COMPANY_NAME} Team</p>',
                'type' => 'task_reminder'
            ],
            [
                'id' => 23,
                'email_group_id' => 3,
                'template_name' => 'Task Overdue Reminder',
                'template_subject' => 'Task Overdue Reminder #{TASK_ID} {TASK_NAME}',
                'template_body' => '<p><b>Hello {NAME},</b></p><p>Hope You Are Well!<br></p><p>This is just to remind you that the task <b>{TASK_NAME}</b> is delayed by <b>{END_DATE}</b>. Its taking longer than expected,<br>Please review activities by login into portal using the link below:<br><big><p style="text-align: center;"><a href="{TASK_URL}"><strong>View Task</strong></a></p></big><br><p>Start Date: <b>{START_DATE}</b><br>Description: {DESCRIPTION}</p><br><p>Best Regards,<br>{COMPANY_NAME} Team</p>',
                'type' => 'task_overdue',
            ],
            [
                'id' => 24,
                'email_group_id' => 3,
                'template_name' => 'Task Comments',
                'template_subject' => 'New Task Comment Received',
                'template_body' => '<p>Hi There,</p><p>A new comment has been posted by <strong>{POSTED_BY}</strong> to <strong>{TASK_NAME}</strong>.</p><p>You can view the comment using the link below:<br /><big><strong><a href="{COMMENT_URL}">View Comment</a></strong></big><br /><br /><em>{COMMENT_MESSAGE}</em><br /><br />Best Regards,<br />The {SITE_NAME} Team</p>',
                'type' => 'task_comments',
            ],
            [
                'id' => 25,
                'email_group_id' => 3,
                'template_name' => 'Tasks Attachment',
                'template_subject' => 'New Tasks Attachment',
                'template_body' => '<p>Hi There,</p><p>A new file has been uploaded by <strong>{UPLOADED_BY} </strong>to Task <strong>{TASK_NAME}</strong>.<br>You can view the Task&nbsp;using the link below:</p><p><br><big><a href="{TASK_URL}"><strong>View Task</strong></a></big><br><br>Best Regards,<br>The {SITE_NAME} Team</p>',
                'type' => 'task_attachment',
            ],
            [
                'id' => 26,
                'email_group_id' => 4,
                'template_name' => 'Defect Assigned',
                'template_subject' => 'Defect Assigned #{DEFECT_ID} {DEFECT_NAME}',
                'template_body' => '<p>Hi there,</p><p>A new defect {DEFECT_NAME} has been assigned to <strong>{ASSIGNED_TO}</strong> by <strong>{ASSIGNED_BY}</strong>.</p><p>You can view this defect by logging in to the portal using the link below.</p><p><br><div style="text-align: center;"><b><a href="{DEFECT_URL}">View Defect</a></b></div><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br><br>Best Regards,<br>The {COMPANY_NAME} Team</p>',
                'type' => 'defect_assigned'
            ],
            [
                'id' => 27,
                'email_group_id' => 4,
                'template_name' => 'Defect Updated',
                'template_subject' => 'Defect Updated #{DEFECT_ID} {DEFECT_NAME}',
                'template_body' => '<p>Hi there,</p><p><strong>{DEFECT_NAME}</strong> has been updated by <strong>{UPDATED_BY}</strong>.</p><p>You can view this Defect by logging in to the portal using the link below.</p><p><br><div style="text-align: center;"><big><strong><a href="{DEFECT_URL}">View Defect</a></strong></big></div><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br>Best Regards,<br>The {COMPANY_NAME} Team</p>',
                'type' => 'defect_updated'
            ],
            [
                'id' => 28,
                'email_group_id' => 4,
                'template_name' => 'Defect Status Changed',
                'template_subject' => 'Status Changed #{DEFECT_ID} {DEFECT_NAME}',
                'template_body' => '<p>Hi there,</p><p>Defect <b>{DEFECT_NAME}</b> has been marked as <b>{STATUS}</b> by <b>{MARKED_BY}</b>.</p><p>You can view this defect by logging in to the portal using the link below.</p><p><div style="text-align: center;"><b><a href="{DEFECT_URL}">View Defect</a></b></div></p><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><p><br>Best Regards,<br>The {COMPANY_NAME} Team</p>',
                'type' => 'defect_status_changed'
            ],
            [
                'id' => 29,
                'email_group_id' => 4,
                'template_name' => 'Defect Reminder',
                'template_subject' => 'Reminder #{DEFECT_ID} {DEFECT_NAME}', 
                'template_body' => '<p>Hello {NAME},</p><p>The Defect <b>{DEFECT_NAME}</b> assigned to you will be start on <b>{START_DATE}</b>.</p><p>You can view this defect by logging in to the portal using the below button:</p><br><div style="text-align: center;"><b><a href="{DEFECT_URL}">View Defect</a></b></div><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br><p>Best Regards,<br>{COMPANY_NAME} Team</p>',
                'type' => 'defect_reminder'
            ],
            [
                'id' => 30,
                'email_group_id' => 4,
                'template_name' => 'Defect Overdue Reminder',
                'template_subject' => 'Defect Overdue Reminder #{DEFECT_ID} {DEFECT_NAME}',
                'template_body' => '<p><b>Hello {NAME},</b></p><p>Hope You Are Well!<br></p><p>This is just to remind you that the defect <b>{DEFECT_NAME}</b> is delayed by <b>{END_DATE}</b>. Its taking longer than expected,<br>Please review activities by login into portal using the link below:<br><big><p style="text-align: center;"><a href="{DEFECT_URL}"><strong>View Defect</strong></a></p></big><br><p>Start Date: <b>{START_DATE}</b><br>Description: {DESCRIPTION}</p><br><p>Best Regards,<br>{COMPANY_NAME} Team</p>',
                'type' => 'defect_overdue',
            ],
            [
                'id' => 31,
                'email_group_id' => 4,
                'template_name' => 'Defect Comments',
                'template_subject' => 'New Defect Comment Received',
                'template_body' => '<p>Hi there,</p><p>A new comment has been posted by {POSTED_BY} to defect {DEFECT_TITLE}.</p><p>You can view the comment using the link below.</p><p><em>{COMMENT_MESSAGE}</em></p><p><br /><big><strong><a href="{COMMENT_URL}">View Comment</a></strong></big><br><br>Best Regards,<br />The {SITE_NAME} Team</p><p>&nbsp;</p>',
                'type' => 'defect_comments'
            ],
            [
                'id' => 32,
                'email_group_id' => 4,
                'template_name' => 'Defect Attachment',
                'template_subject' => 'New Defect Attachment',
                'template_body' => '<p>Hi there,</p><p>A new attachment&nbsp;has been uploaded by {UPLOADED_BY} to issue {DEFECT_TITLE}.</p><p>You can view the defect using the link below.</p><p><br /><big><strong><a href="{DEFECT_URL}">View Defect</a></strong></big></p><p><br />Best Regards,<br />The {SITE_NAME} Team</p>',
                'type' => 'defect_attachment'
            ],
            [
                'id' => 33,
                'email_group_id' => 5,
                'template_name' => 'Incident Assigned',
                'template_subject' => 'Incident Assigned #{INCIDENT_ID} {INCIDENT_NAME}',
                'template_body' => '<p>Hi there,</p><p>A new incident <strong>{INCIDENT_NAME}</strong> has been assigned to <strong>{ASSIGNED_TO}</strong> by <strong>{ASSIGNED_BY}</strong>.</p><p>You can view this incident by logging in to the portal using the link below.</p><div style="text-align: center;"><strong><a href="{INCIDENT_URL}">View Incident</a></strong></div><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><p><br>Best Regards,<br>The {COMPANY_NAME} Team</p>',
                'type' => 'incident_assigned'
            ],
            [
                'id' => 34,
                'email_group_id' => 5,
                'template_name' => 'Incident Updated',
                'template_subject' => 'Incident Updated #{INCIDENT_ID} {INCIDENT_NAME}',
                'template_body' => '<p>Hi there,</p><p><strong>{INCIDENT_NAME}</strong> has been updated by <strong>{UPDATED_BY}</strong>.</p><p>You can view this Incident by logging in to the portal using the link below.</p><p><br><div style="text-align: center;"><big><strong><a href="{INCIDENT_URL}">View Incident</a></strong></big></div><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br>Best Regards,<br>The {COMPANY_NAME} Team</p>',
                'type' => 'incident_updated'
            ],
            [
                'id' => 35,
                'email_group_id' => 5,
                'template_name' => 'Incident Status Changed',
                'template_subject' => 'Status Changed #{INCIDENT_ID} {INCIDENT_NAME}',
                'template_body' => '<p>Hi there,</p><p>Incident <b>{INCIDENT_NAME}</b> has been marked as <b>{STATUS}</b> by <b>{MARKED_BY}</b>.</p><p>You can view this incident by logging in to the portal using the link below.</p><p><div style="text-align: center;"><b><a href="{INCIDENT_URL}">View Incident</a></b></div></p><p>Start Date: <b>{START_DATE}</b></p><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><p><br>Best Regards,<br>The {COMPANY_NAME} Team</p>',
                'type' => 'incident_status_changed'
            ],
            [
                'id' => 36,
                'email_group_id' => 5,
                'template_name' => 'Incident Reminder',
                'template_subject' => 'Reminder #{INCIDENT_ID} {INCIDENT_NAME}', 
                'template_body' => '<p>Hello {NAME},</p><p>The Incident <b>{INCIDENT_NAME}</b> assigned to you will be start on <b>{START_DATE}</b>.</p><p>You can view this incident by logging in to the portal using the below button:</p><br><div style="text-align: center;"><b><a href="{INCIDENT_URL}">View Incident</a></b></div><p>End Date: <b>{END_DATE}</b></p><p>Description: {DESCRIPTION}</p><br><p>Best Regards,<br>{COMPANY_NAME} Team</p>',
                'type' => 'incident_reminder'
            ],
            [
                'id' => 37,
                'email_group_id' => 5,
                'template_name' => 'Incident Overdue Reminder',
                'template_subject' => 'Incident Overdue Reminder #{INCIDENT_ID} {INCIDENT_NAME}',
                'template_body' => '<p><b>Hello {NAME},</b></p><p>Hope You Are Well!<br></p><p>This is just to remind you that the incident <b>{INCIDENT_NAME}</b> is delayed by <b>{END_DATE}</b>. Its taking longer than expected,<br>Please review activities by login into portal using the link below:<br><big><p style="text-align: center;"><a href="{INCIDENT_URL}"><strong>View Incident</strong></a></p></big><br><p>Start Date: <b>{START_DATE}</b><br />Description: {DESCRIPTION}</p><br><p>Best Regards,<br>{COMPANY_NAME} Team</p>',
                'type' => 'incident_overdue',
            ],
            [
                'id' => 38,
                'email_group_id' => 5,
                'template_name' => 'Incident Comments',
                'template_subject' => 'New Incident Comment Received',
                'template_body' => '<p>Hi there,</p><p>A new comment has been posted by {POSTED_BY} to incident {INCIDENT_TITLE}.</p><p>You can view the comment using the link below.</p><p><em>{COMMENT_MESSAGE}</em></p><p><br><big><strong><a href="{COMMENT_URL}">View Comment</a></strong></big><br><br>Best Regards,<br>The {SITE_NAME} Team</p><p>&nbsp;</p>',
                'type' => 'incident_comments'
            ],
            [
                'id' => 39,
                'email_group_id' => 5,
                'template_name' => 'Incident Attachment',
                'template_subject' => 'New Incident Attachment',
                'template_body' => '<p>Hi there,</p><p>A new attachment&nbsp;has been uploaded by {UPLOADED_BY} to issue {INCIDENT_TITLE}.</p><p>You can view the incident using the link below.</p><p><br><big><strong><a href="{INCIDENT_URL}">View Incident</a></strong></big></p><p><br>Best Regards,<br>The {SITE_NAME} Team</p>',
                'type' => 'incident_attachment'
            ],
            [
                'id' => 40,
                'email_group_id' => 6,
                'template_name' => 'Appointment Mail',
                'template_subject' => 'Appointment #{APPOINTMENT_ID} {APPOINTMENT_TITLE}',
                'template_body' => 'Hi <strong>{NAME}</strong>,<br><div>A appointment has been <b>{STATUS}</b> on <strong>{APPOINTEMENT_DATE}</strong>, in the <strong>{LOCATION}</strong> at <strong>{APPOINTEMENT_TIME}</strong>.</div><br><div>End Time: <b>{END_TIME}</b></div><div>Description: {DESCRIPTION}</div><br><div>Have fun!<br>The <strong>{COMPANY_NAME}</strong> Team.<br></div>',
                'type' => 'appointment_mail',
            ],
            [
                'id' => 41,
                'email_group_id' => 6,
                'template_name' => 'Appointment Reminder',
                'template_subject' => 'Reminder #{APPOINTMENT_ID} {APPOINTMENT_TITLE}', 
                'template_body' => '<<p>Hello <b>{NAME}</b>,</p><p>This is <b>{COMPANY_NAME}</b> reminding you for your appointment on <b>{DATE}</b> at <b>{TIME}</b>. Call/text us if you need to reschedule. See you then!</b></p><br><div>End Time: <b>{END_TIME}</b></div><div>Description: {DESCRIPTION}</div><br><p>Best Regards,<br>{COMPANY_NAME} Team</p>',
                'type' => 'appointment_reminder'
            ],
            [
                'id' => 42,
                'email_group_id' => 7,
                'template_name' => 'Leave Request',
                'template_subject' => 'Leave Request',
                'template_body' => 'Hello Sir/Ma\'am,<br><br>I am writing to request for a {LEAVE_TYPE} Leave on {DATE}, due to {REASON}. Kindly grant me the leave.<br><br>Best Regards,<br>{NAME}',
                'type' => 'leave_request',
            ],
            [
                'id' => 43,
                'email_group_id' => 7,
                'template_name' => 'Approve Leave',
                'template_subject' => 'Approval Letter for Leave Application',
                'template_body' => 'Hello {NAME},<br><br>We have gone through your request for your {LEAVE_TYPE} Leave. We are happy to grant you leave on {DATE}.<br><br>We request you to complete all your pending work so that the company does not face any loss or problem during your absence.<br><br>Have a good time.<br><br>Best Regards,<br>{APPROVED_BY}',
                'type' => 'approve_leave',
            ],
            [
                'id' => 44,
                'email_group_id' => 7,
                'template_name' => 'Reject Leave',
                'template_subject' => 'Rejection Letter for Leave Application',
                'template_body' => 'Dear {NAME},<br><br>After reviewing your request, I am sorry to say that I am unable to grant you {LEAVE_TYPE} Leave on {DATE}. While {REJECT_REASON}.<br><br>Best Regards,<br>{REJECT_BY}',
                'type' => 'reject_leave',
            ],
            [
                'id' => 45,
                'email_group_id' => 7,
                'template_name' => 'Cancel Leave',
                'template_subject' => 'Request for Cancelling Leave',
                'template_body' => 'Dear Sir/Ma\'am,<br><br>I am writing to request for the cancellation of my submitted leave for <b>{DATE}</b>. So there will be no effect on my attendance and salary. <br>Sorry for the inconvenience. <br>Thanking you in anticipation.<br><br>Best Regards,<br>{NAME}',
                'type' => 'cancel_leave',
            ],
            [
                'id' => 46,
                'email_group_id' => 8,
                'template_name' => 'Send Estimate to Customer',
                'template_subject' => 'Estimate Created # {ESTIMATE_NUMBER}',
                'template_body' => 'Dear {CUSTOMER_NAME},<br><br>We have created an estimate for you in the amount of {AMOUNT}.<br><br>Please find the attached estimate <b># {ESTIMATE_NUMBER}</b><br><br>You can view the estimate on the following link: <a href="{ESTIMATE_URL}">{ESTIMATE_NUMBER}</a><br><br>We are looking forward to your reply.<br><br>Best Regards,<br>The {COMPANY_NAME} Team',
                'type' => 'send_estimate',
            ],
            [
                'id' => 47,
                'email_group_id' => 8,
                'template_name' => 'Estimate Declined',
                'template_subject' => 'Customer Declined Estimate # {ESTIMATE_NUMBER}',
                'template_body' => 'Hi,<br><br>{CUSTOMER_NAME} declined the estimate <b># {ESTIMATE_NUMBER}</b><br><br>You can view the estimate on the following link: <a href="{ESTIMATE_URL}">{ESTIMATE_NUMBER}</a><br><br>{CUSTOMER_NAME}',
                'type' => 'estimate_declined',
            ],
            [
                'id' => 48,
                'email_group_id' => 8,
                'template_name' => 'Estimate Accepted',
                'template_subject' => 'Customer Accepted Estimate # {ESTIMATE_NUMBER}',
                'template_body' => 'Hi,<br><br>{CUSTOMER_NAME} accepted the estimate <b># {ESTIMATE_NUMBER}</b><br><br>You can view the estimate on the following link: <a href="{ESTIMATE_URL}">{ESTIMATE_NUMBER}</a><br><br>{CUSTOMER_NAME}',
                'type' => 'estimate_accepted',
            ],
            [
                'id' => 49,
                'email_group_id' => 8,
                'template_name' => 'Thank You Email',
                'template_subject' => 'Thank you for accepting estimate # {ESTIMATE_NUMBER}',
                'template_body' => 'Dear {CUSTOMER_NAME},<br><br>Thank you for accepting the estimate <b># {ESTIMATE_NUMBER}</b><br><br>We are looking forward to doing business with you.<br><br>We will contact you as soon as possible.<br><br>Best Regards,<br>The {COMPANY_NAME} Team',
                'type' => 'thank_you_for_accepting_estimate',
            ],
            [
                'id' => 50,
                'email_group_id' => 8,
                'template_name' => 'Estimate Expiration Reminder',
                'template_subject' => 'Estimate Expiration Reminder # {ESTIMATE_NUMBER}',
                'template_body' => 'Hello {CUSTOMER_NAME},<br><br>The estimate <b># {ESTIMATE_NUMBER}</b> will expire on <b>{ESTIMATE_VALID_TILL}</b><br><br>You can view the estimate on the following link: <a href="{ESTIMATE_URL}">{ESTIMATE_NUMBER}</a><br><br>Best Regards,<br>The {COMPANY_NAME} Team',
                'type' => 'estimate_expiration_reminder',
            ],
            [
                'id' => 51,
                'email_group_id' => 9,
                'template_name' => 'Send Invoice to Customer',
                'template_subject' => 'Invoice Created # {INVOICE_NUMBER}',
                'template_body' => 'Dear {CUSTOMER_NAME},<br><br>We have created an invoice for you in the amount of {AMOUNT}.<br><br>Please find the attached invoice <b># {INVOICE_NUMBER}</b><br><br>You can view the invoice on the following link: <a href="{INVOICE_URL}">{INVOICE_NUMBER}</a><br><br>Don’t hesitate to reach out if you have any questions.<br><br>Best Regards,<br>The {COMPANY_NAME} Team',
                'type' => 'send_invoice',
            ],
            [
                'id' => 52,
                'email_group_id' => 9,
                'template_name' => 'Invoice Overdue Notice',
                'template_subject' => 'Invoice Overdue Notice # {INVOICE_NUMBER}',
                'template_body' => 'Dear {CUSTOMER_NAME},<br><br>I hope you are doing well.<br><br>This is an overdue notice for invoice <b># {INVOICE_NUMBER}</b><br><br>This invoice was due on <b>{DUE_DATE}</b><br><br>You can view the invoice on the following link: <a href="{INVOICE_URL}">{INVOICE_NUMBER}</a><br><br>Please let me know in case of any questions.<br><br>Best Regards,<br>The {COMPANY_NAME} Team',
                'type' => 'invoice_overdue_notice',
            ],
            [
                'id' => 53,
                'email_group_id' => 10,
                'template_name' => 'Payment Added (Sent to Customer)',
                'template_subject' => 'Payment Added Against # {INVOICE_NUMBER}',
                'template_body' => 'Hello {CUSTOMER_NAME},<br><br>Thank you for the payment.<br><br>Please find the attached payment receipt # <b>{RECEIPT_NUMBER}</b><br><br>You can always view the invoice for this payment at the following link: <a href="{INVOICE_URL}"><b>{INVOICE_NUMBER}</b></a><br><br>We are looking forward working with you.<br><br>Best  Regards,<br>The {COMPANY_NAME} Team',
                'type' => 'payment_added_send_to_customer',
            ],
            [
                'id' => 54,
                'email_group_id' => 10,
                'template_name' => 'Payment Added (Sent to Staff)',
                'template_subject' => 'Payment Added Against # {INVOICE_NUMBER}',
                'template_body' => 'Hello,<br><br>Customer added payment for invoice # {INVOICE_NUMBER}<br><br>You can view the invoice for this payment at the following link: <a href="{INVOICE_URL}"><b>{INVOICE_NUMBER}</b></a><br><br>{CUSTOMER_NAME}',
                'type' => 'payment_added_send_to_staff',
            ],
            [
                'id' => 55,
                'email_group_id' => 11,
                'template_name' => 'Meeting',
                'template_subject' => 'Meeting #{MEETING_ID} {MEETING_TITLE}',
                'template_body' => 'Hi <strong>{NAME}</strong>,<div><br></div><div>A meeting has been scheduled on <strong>{MEETING_DATE}</strong>, in the <strong>{LOCATION}</strong> at <strong>{MEETING_TIME}</strong>.</div><div><br></div><div>End Time: <b>{END_TIME}</b></div><div>Description: {DESCRIPTION}</div><div><br></div><div>Have fun!<br>The <strong>{COMPANY_NAME}</strong> Team.<br></div><div><br></div>',
                'type' => 'meeting',
            ],
            [
                'id' => 56,
                'email_group_id' => 11,
                'template_name' => 'Cancel Meeting',
                'template_subject' => 'Meeting Canceled #{MEETING_ID} {MEETING_TITLE}',
                'template_body' => 'Hi <strong>{NAME}</strong>,<div><br></div><div>The meeting scheduled on <strong>{MEETING_DATE}</strong>, in the <strong>{LOCATION}</strong> at <strong>{MEETING_TIME}</strong> has been canceled.</div><br><div>End Time: <b>{END_TIME}</b></div><div>Description: {DESCRIPTION}</div><br><div>Have fun!<br>The <strong>{COMPANY_NAME}</strong> Team.<br></div>',
                'type' => 'cancel_meeting',
            ],
            [
                'id' => 57,
                'email_group_id' => 11,
                'template_name' => 'Meeting Reminder',
                'template_subject' => 'Reminder #{MEETING_ID} {MEETING_TITLE}', 
                'template_body' => '<p>Hi <b>{NAME}</b>,</p><p>Friendly reminder that we have a meeting booked for <b>{DATE}</b> at <b>{TIME}</b></b>.</p><p>You can view this meeting by logging in to the portal using the below button:</p><p></p><div style="text-align: center;"><b><a href="{MEETING_URL}">View Meeting</a></b></div><div>End Time: <b>{END_TIME}</b><div>Description: {DESCRIPTION}</div><p></p><p>Best Regards,<br>{COMPANY_NAME} Team</p>',
                'type' => 'meeting_reminder'
            ]
        ];
        EmailTemplate::insert($data);
    }
}
