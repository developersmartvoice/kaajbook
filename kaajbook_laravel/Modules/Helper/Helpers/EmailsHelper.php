<?php

namespace Modules\Helper\Helpers;

use Auth;
use Carbon\Carbon;
use DB;
use Mail;
use Modules\Defect\Models\Defect;
use Modules\EmailTemplate\Models\EmailTemplate;
use Modules\Helper\Jobs\SendEmailJob;
use Modules\Incident\Models\Incident;
use Modules\Projects\Models\Project;
use Modules\Setting\Models\Setting;
use Modules\Task\Models\Task;
use Modules\User\Models\User\User;

/**
 * Class EmailsHelper
 *
 * The Helper is Defined for send different mail.
 *
 * PHP version 7.1.3
 *
 * @category  Helper
 * @package   Modules\Helper
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class EmailsHelper
{
    /**
     * Send mail.
     *
     * @param String $template   [Email html]
     * @param Array  $parameters [Required params]
     * @param Array  $config     [Send mail config]
     *
     * @return void
     */
    public function sendmail($template = '', $parameters = [], $config = [])
    {
        try {
            Mail::send(
                $template,
                $parameters,
                function ($mail) use ($config) {
                    $mail->to($config['email'], $config['name'])
                        ->from($config['from'])
                        ->subject($config['subject']);
                }
            );
        } catch (\Exception $e) {
        }
    }

    /**
     * Send mail with attachments.
     *
     * @param String $template         [Email html]
     * @param Array  $parameters       [Required params]
     * @param Array  $config           [Send mail config]
     * @param Array  $attachmentsFiles [Attachment files]
     *
     * @return void
     */
    public function sendmailAttachments(
        $template = '',
        $parameters = [],
        $config = [],
        $attachmentsFiles = []
    ) {
        try {
            Mail::send(
                ['html' => $template],
                $parameters,
                function ($mail) use ($config, $attachmentsFiles) {
                    $mail->to($config['email'], $config['name'])
                        ->from($config['from'])
                        ->subject($config['subject']);

                    if (!empty($attachmentsFiles)) {
                        foreach ($attachmentsFiles as $key => $value) {
                            $mail->attach(
                                $value['file'],
                                array(
                                    'as'   => $value['name'],
                                    'mime' => $value['mime']
                                )
                            );
                        }
                    }
                }
            );
        } catch (\Exception $e) { }
    }

    /**
     * Send mails.
     *
     * @param String $to      [Mail to address]
     * @param String $name    [Mail from name]
     * @param String $subject [Mail subject]
     * @param String $body    [Mail body]
     *
     * @return void
     */
    private function _sendEmails($to, $name, $subject, $body)
    {
        try {
            Mail::send([], [], function ($message) use ($to, $name, $subject, $body) {
                    $message->setBody($body, 'text/html');
                    $message->to($to, $name);
                    $message->subject($subject);
                }
            );
            return [ 'status' => true ];
        } catch (\Exception $e) {
            return [ 'status' => false, 'msg' => $e->getMessage() ];
        }
    }

    /**
     * Send mails in queue.
     *
     * @param String $to      [Mail to]
     * @param String $name    [From name]
     * @param String $subject [Mail subject]
     * @param String $body    [Mail body]
     *
     * @return void
     */
    private function _sendEmailsInQueue($to, $name, $subject, $body)
    {
        try {
            $details['to'] = $to;
            $details['name'] = $name;
            $details['subject'] = $subject;
            $details['body'] = $body;
            // SendEmailJob::dispatch($details)->onConnection('sync');
            $job = (new SendEmailJob($details))->delay(Carbon::now()->addSeconds(5));
            dispatch($job);
        } catch (\Exception $e) {
            pr($e->getMessage(), 1);
        }
    }

    /**
     * Send user activate email.
     *
     * @param Object $user     [User object]
     * @param String $password [Password]
     *
     * @return Boolean
     */
    public function sendActivateEmail($user, $password)
    {
        $email_template = EmailTemplate::where('type', 'activate_account')->first();
        if (!empty($email_template)) {
            $name = $user->firstname. ' '. $user->lastname;
            $activate_url = str_replace("{ACTIVATE_URL}", config('app.url').'/api/verify/user/' . $user->email_verification_code, $email_template->template_body);
            $activate_period = str_replace("{ACTIVATION_PERIOD}", config('core.EMAIL_ACTIVATION_EXPIRE') / 3600, $activate_url);
            $username = str_replace("{USERNAME}", $user->username, $activate_period);
            $user_email = str_replace("{EMAIL}", $user->email, $username);
            $user_password = str_replace("{PASSWORD}", $password, $user_email);
            $message = str_replace("{SITE_NAME}", config('core.COMPANY_NAME'), $user_password);

            $this->_sendEmails(
                $user->email,
                $name,
                $email_template->template_subject,
                $message
            );
        }
        return true;
    }

    /**
     * Send user creation notification to admin.
     *
     * @param Object $adminUsers     [Admin users object]
     * @param Object $user     [User object]
     *
     * @return Boolean
     */
    public function sendUserCreationEmailToAdmin($adminUsers, $user)
    {
        $email_template = EmailTemplate::where('type', 'new_user_added')->first();
        if (!empty($email_template)) {
            $roles = [];
            foreach($user->roles as $role){
                $roles[] = $role->name;
            }
            foreach($adminUsers as $adminUser){

                $search = [
                    '{USER_ID}',
                    '{USER_NAME}',
                    '{NAME}',
                    '{ROLE}',
                    '{PROFILE_URL}',
                    '{COMPANY_NAME}'
                ];
                $replace = [
                    $user->user_generated_id,
                    $user->fullname,
                    $adminUser->fullname,
                    implode(",",$roles),
                    config('app.front_url').'/#/users/profile/'.$user->id,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);

                $this->_sendEmailsInQueue(
                    $adminUser->email,
                    $adminUser->fullname,
                    $subject,
                    $message
                );
            }
        }
        return true;
    }

    /**
     * Send client creation notification to admin.
     *
     * @param Object $adminUsers     [Admin users object]
     * @param Object $user     [User object]
     *
     * @return Boolean
     */
    public function sendClientCreationEmailToAdmin($adminUsers, $user)
    {
        $email_template = EmailTemplate::where('type', 'new_client_added')->first();
        if (!empty($email_template)) {
            
            foreach($adminUsers as $adminUser){

                $search = [
                    '{CLIENT_ID}',
                    '{CLIENT_NAME}',
                    '{NAME}',
                    '{PROFILE_URL}',
                    '{COMPANY_NAME}'
                ];
                $replace = [
                    $user->user_generated_id,
                    $user->fullname,
                    $adminUser->fullname,
                    config('app.front_url').'/#/users/profile/'.$user->id,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);

                $this->_sendEmailsInQueue(
                    $adminUser->email,
                    $adminUser->fullname,
                    $subject,
                    $message
                );
            }
        }
        return true;
    }

    /**
     * Send change email verify mail.
     *
     * @param Object $user  [User object]
     * @param Array  $input [Request data for change mail]
     *
     * @return Boolean
     */
    public function sendChangeEmailVerifyEmail($user, $input)
    {
        $email_template = EmailTemplate::where('type', 'change_email')->first();
        if (!empty($email_template)) {
            $name = $user->firstname. ' '. $user->lastname;
            $activate_url = str_replace("{NEW_EMAIL_KEY_URL}", config('app.url').'/api/verify/user/' . $user->email_verification_code .'/'.encrypt($input['email']), $email_template->template_body);
            $site_url = str_replace("{SITE_URL}", config('app.front_url'), $activate_url);
            $user_email = str_replace("{NEW_EMAIL}", $input['email'], $site_url);
            $message = str_replace("{SITE_NAME}", config('core.COMPANY_NAME'), $user_email);

            $this->_sendEmails(
                $input['email'],
                $name,
                $email_template->template_subject,
                $message
            );
        }
        return true;
    }

    /**
     * Send change password mail.
     *
     * @param Object $user     [User object]
     * @param String $password [Actual password]
     *
     * @return Boolean
     */
    public function sendChangePasswordEmail($user, $password)
    {
        $email_template = EmailTemplate::where('type', 'reset_password')->first();
        if (!empty($email_template)) {
            $name = $user->firstname. ' '. $user->lastname;

            $username = str_replace("{USERNAME}", $user->username, $email_template->template_body);
            $user_email = str_replace("{EMAIL}", $user->email, $username);
            $user_password = str_replace("{NEW_PASSWORD}", $password, $user_email);
            $message = str_replace("{SITE_NAME}", config('core.COMPANY_NAME'), $user_password);

            $this->_sendEmails(
                $user->email,
                $name,
                $email_template->template_subject,
                $message
            );
        }
        return true;
    }

    /**
     * Send meeting invite people emails.
     *
     * @param Array  $users      [Users array]
     * @param Object $meeting    [Meeting details]
     *
     * @return Boolean
     */
    public function sendMeetingEmails($users, $meeting)
    {
        $users = User::whereIn('id', $users)->get();
        $email_template = EmailTemplate::where('type', 'meeting')->first();

        if (!empty($email_template) && !empty($users)) {

            $setting = Setting::select('php_date_format', 'php_time_format', 'php_date_time_format')->first();

            foreach ($users as $key => $value) {

                $name = $value->firstname.' '.$value->lastname;

                $search = [
                    '{MEETING_ID}',
                    '{MEETING_TITLE}',
                    '{NAME}',
                    '{MEETING_DATE}',
                    '{MEETING_TIME}',
                    '{END_TIME}',
                    '{LOCATION}',
                    '{DESCRIPTION}',
                    '{COMPANY_NAME}'
                ];
                $replace = [
                    $meeting->generated_id,
                    $meeting->title,
                    $name,
                    $meeting->start_date->format($setting->php_date_format),
                    $meeting->start_date->format($setting->php_time_format),
                    $meeting->end_date->format($setting->php_date_time_format),
                    $meeting->location,
                    $meeting->description,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);
                
                $this->_sendEmailsInQueue($value->email, $name, $subject, $message);
            }
        }
    }

    /**
     * Send meeting reminder notification.
     *
     * @param Object $meeting [Meeting Object]
     *
     * @return Boolean
    */
    public function sendMeetingReminderEmails($meeting)
    {
        $email_template = EmailTemplate::where('type', 'meeting_reminder')->first();

        if (!empty($email_template)) {
            $setting = Setting::select('php_date_format', 'php_time_format', 'php_date_time_format')->first();
            $url = config('app.front_url').'/#/meetings/detail/'.$meeting->id;
            foreach($meeting->members as $user){
                $search = [
                    '{MEETING_ID}',
                    '{MEETING_TITLE}',
                    '{NAME}',
                    '{DATE}',
                    '{TIME}',
                    '{END_TIME}',
                    '{DESCRIPTION}',
                    '{MEETING_URL}',
                    '{COMPANY_NAME}'
                ];
                $replace = [
                    $meeting->generated_id,
                    $meeting->title,
                    $user->firstname .' '. $user->lastname,
                    $meeting->start_date->format($setting->php_date_format),
                    $meeting->start_date->format($setting->php_time_format),
                    $meeting->end_date->format($setting->php_date_time_format),
                    $meeting->description,
                    $url,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);

                $this->_sendEmailsInQueue($user->email, $user->firstname .' '. $user->lastname, $subject, $message);
            }
        }
    }

    /**
     * Send meeting cancel emails.
     *
     * @param Array  $users      [Users array]
     * @param Object $meeting    [Meeting details]
     *
     * @return Boolean
     */
    public function sendMeetingCancelEmails($users, $meeting)
    {
        $users = User::whereIn('id', $users)->get();
        $email_template = EmailTemplate::where('type', 'cancel_meeting')->first();

        if (!empty($email_template) && !empty($users)) {

            $setting = Setting::select('php_date_format', 'php_time_format', 'php_date_time_format')->first();

            foreach ($users as $key => $value) {

                $name = $value->firstname.' '.$value->lastname;

                $search = [
                    '{MEETING_ID}',
                    '{MEETING_TITLE}',
                    '{NAME}',
                    '{MEETING_DATE}',
                    '{MEETING_TIME}',
                    '{END_TIME}',
                    '{LOCATION}',
                    '{DESCRIPTION}',
                    '{COMPANY_NAME}'
                ];
                $replace = [
                    $meeting->generated_id,
                    $meeting->title,
                    $name,
                    $meeting->start_date->format($setting->php_date_format),
                    $meeting->start_date->format($setting->php_time_format),
                    $meeting->end_date->format($setting->php_date_time_format),
                    $meeting->location,
                    $meeting->description,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);
                
                $this->_sendEmailsInQueue($value->email, $name, $subject, $message);
            }
        }
    }

    /**
     * Send announcement emails.
     *
     * @param Array  $users        [Users array]
     * @param Object $announcement [Announcement details]
     *
     * @return Boolean
     */
    public function sendAnnouncementEmails($users, $announcement)
    {
        $setting = Setting::select("is_announcement_notification")->first();
        if($setting->is_announcement_notification){
            $email_template = EmailTemplate::where('type', 'announcement')->first();
            $setting = Setting::select('php_date_format')->first();
            if (!empty($email_template) && !empty($users)) {
                foreach ($users as $key => $value) {
                    $name = $value->firstname.' '.$value->lastname;
                    $message = $email_template->template_body;
                    $subject = $announcement->title .' '.date($setting->php_date_format, strtotime($announcement->start_date));
                    $announcement_start_date = date($setting->php_date_format, strtotime($announcement->start_date));
                    $announcement_end_date = date($setting->php_date_format, strtotime($announcement->end_date));
                    $site_name = str_replace("{COMPANY_NAME}", config('core.COMPANY_NAME'), $message);
                    $user_name = str_replace("{NAME}", $name, $site_name);
                    $title = str_replace("{TITLE}", $announcement->title, $user_name);
                    $announcement_start_date = str_replace("{START_DATE}", $announcement_start_date, $title);
                    $announcement_end_date = str_replace("{END_DATE}", $announcement_end_date, $announcement_start_date);
                    $message = str_replace("{DESCRIPTION}", $announcement->description, $announcement_end_date);
                    $this->_sendEmailsInQueue(
                        $value->email,
                        $name,
                        $subject,
                        $message
                    );
                }
            }
        }
    }

    /**
     * Forgot user password.
     *
     * @param String $url  [Forgot password URL]
     * @param Object $user [User object]
     *
     * @return Boolean
     */
    public function sendForgotPasswordEmail($url, $user)
    {
        $email_template = EmailTemplate::where('type', 'forgot_password')->first();

        if (!empty($email_template)) {
            $message = $email_template->template_body;
            $subject = $email_template->template_subject;

            $site_url = str_replace("{SITE_URL}", config('app.url'), $message);
            $key_url = str_replace("{PASS_KEY_URL}", $url, $site_url);
            $message = str_replace("{SITE_NAME}", config('core.COMPANY_NAME'), $key_url);

            $this->_sendEmails(
                $user->email,
                $user->firstname.' '.$user->lastname,
                $email_template->template_subject,
                $message
            );
        }
        return true;
    }

    /**
     * Send user reset password email.
     *
     * @param Object $user     [User object]
     * @param String $password [Password]
     *
     * @return Boolean
     */
    public function sendResetEmail($user, $password)
    {
        $email_template = EmailTemplate::where('type', 'reset_password')->first();

        if (!empty($email_template)) {
            $message = $email_template->template_body;
            $subject = $email_template->template_subject;

            $site_name = str_replace("{SITE_NAME}", config('core.COMPANY_NAME'), $message);
            $username = str_replace("{USERNAME}", $user->username, $site_name);
            $user_email = str_replace("{EMAIL}", $user->email, $username);
            $message = str_replace("{NEW_PASSWORD}", $password, $user_email);

            $this->_sendEmails(
                $user->email,
                $user->firstname,
                $subject,
                $message
            );
        }
        return true;
    }

    /**
     * Send invite people mail.
     *
     * @param Object $user [User object]
     *
     * @return Boolean
     */
    public function sendInviteUserMail($user)
    {
        $url = config('app.front_url').'/#/users/profile/'. $user->id;
        $email_template = EmailTemplate::where('type', 'welcome_email')->first();

        if (!empty($email_template)) {
            $message = $email_template->template_body;
            $subject = $email_template->template_subject;

            $username = str_replace("{NAME}", $user->firstname .' '. $user->lastname, $message);
            $activate_url = str_replace("{ACTIVATE_URL}", config('app.url').'/api/verify/user/' . $user->email_verification_code, $username);
            $site_url = str_replace("{COMPANY_URL}", $url, $activate_url);
            $message = str_replace("{COMPANY_NAME}", config('core.COMPANY_NAME'), $site_url);

            return $this->_sendEmails($user->email, $user->firstname, $subject, $message);
        }
        return false;
    }

    /**
     * Send project assign email.
     *
     * @param Object $users      [Users object]
     * @param Object    $project [Project object]
     *
     * @return Boolean
     */
    public function sendProjectAssignUserEmail($users, $project)
    {
        $email_template = EmailTemplate::where('type', 'assigned_project')->first();

        if (!empty($email_template)) {
            $url = config('app.front_url').'/#/projects/detail/'.$project->id;
            $setting = Setting::select('php_date_format')->first();
            foreach ($users as $key => $value) {

                $search = [
                    '{PROJECT_ID}',
                    '{PROJECT_NAME}',
                    '{PROJECT_URL}',
                    '{START_DATE}',
                    '{END_DATE}',
                    '{DESCRIPTION}',
                    '{ASSIGNED_BY}',
                    '{SITE_NAME}',
                ];
                $replace = [
                    $project->generated_id,
                    $project->project_name,
                    $url,
                    $project->start_date->format($setting->php_date_format),
                    $project->end_date->format($setting->php_date_format),
                    $project->description,
                    auth()->user()->firstname . ' ' . auth()->user()->lastname,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);

                $this->_sendEmailsInQueue($value['email'], $value['name'], $subject, $message);
            }
        }

        return true;
    }

    /**
     * Send project create client notification.
     *
     * @param Object    $project [Project object]
     *
     * @return Boolean
     */
    public function sendCreateProjectNotiClientMail($project)
    {
        $email_template = EmailTemplate::where('type', 'notification_client')->first();

        if (!empty($email_template)) {
            $client = User::findOrFail($project->client_id);
            $client_name = $client->firstname.' '.$client->lastname;
            $url = config('app.front_url').'/#/projects/detail/'.$project->id;
            $setting = Setting::select('php_date_format')->first();

            $search = [
                '{PROJECT_ID}',
                '{PROJECT_NAME}',
                '{PROJECT_URL}',
                '{START_DATE}',
                '{END_DATE}',
                '{DESCRIPTION}',
                '{CLIENT_NAME}',
                '{SITE_NAME}',
            ];
            $replace = [
                $project->generated_id,
                $project->project_name,
                $url,
                $project->start_date->format($setting->php_date_format),
                $project->end_date->format($setting->php_date_format),
                $project->description,
                $client_name,
                config('core.COMPANY_NAME'),
            ];

            $subject = str_replace($search, $replace, $email_template->template_subject);
            $message = str_replace($search, $replace, $email_template->template_body);

            $this->_sendEmailsInQueue($client->email, $client_name, $subject, $message);
        }
    }

    /**
     * Send project update email.
     *
     * @param Object $users      [Users object]
     * @param Object    $project [Project object]
     *
     * @return Boolean
     */
    public function sendProjectUpdateEmail($users, $project)
    {
        $email_template = EmailTemplate::where('type', 'project_update')->first();

        if (!empty($email_template)) {
            $url = config('app.front_url').'/#/projects/detail/'.$project->id;
            $setting = Setting::select('php_date_format')->first();
            foreach ($users as $key => $value) {

                $search = [
                    '{PROJECT_ID}',
                    '{PROJECT_NAME}',
                    '{PROJECT_URL}',
                    '{START_DATE}',
                    '{END_DATE}',
                    '{DESCRIPTION}',
                    '{UPDATED_BY}',
                    '{SITE_NAME}',
                ];
                $replace = [
                    $project->generated_id,
                    $project->project_name,
                    $url,
                    $project->start_date->format($setting->php_date_format),
                    $project->end_date->format($setting->php_date_format),
                    $project->description,
                    auth()->user()->firstname . ' ' . auth()->user()->lastname,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);

                $this->_sendEmailsInQueue($value['email'], $value['name'], $subject, $message);
            }
        }

        return true;
    }

    /**
     * Send project completed client notification.
     *
     * @param Object $project    [Project object]
     *
     * @return Boolean
     */
    public function sendProjectCompletedNotificationClientMail($project)
    {
        if($project->client_id){
            $client = User::findOrFail($project->client_id);
            $email_template = EmailTemplate::where('type', 'complete_projects')->first();

            if (!empty($email_template)) {
                $client_name = $client->firstname.' '.$client->lastname;
                $url = config('app.front_url').'/#/projects/detail/'.$project->id;
                $setting = Setting::select('php_date_format')->first();

                $search = [
                    '{PROJECT_ID}',
                    '{PROJECT_NAME}',
                    '{PROJECT_URL}',
                    '{START_DATE}',
                    '{END_DATE}',
                    '{DESCRIPTION}',
                    '{CLIENT_NAME}',
                    '{SITE_NAME}',
                ];
                $replace = [
                    $project->generated_id,
                    $project->project_name,
                    $url,
                    $project->start_date->format($setting->php_date_format),
                    $project->end_date->format($setting->php_date_format),
                    $project->description,
                    $client_name,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);

                $this->_sendEmailsInQueue($client->email, $client_name, $subject, $message);
            }
        }
    }

    /**
     * Send project status change notification.
     *
     * @param Object $project [Project Object]
     *
     * @return Boolean
    */
    public function sendProjectStatusChangeEmails($project)
    {
        $email_template = EmailTemplate::where('type', 'project_status_change')->first();

        if (!empty($email_template)) {
            $status = [
                1 => __('messages.status.open'),
                2 => __('messages.status.in_progress'),
                3 => __('messages.status.on_hold'),
                4 => __('messages.status.cancel'),
                5 => __('messages.status.completed')
            ];
            $project = $this->_getAssignProjectUsers($project->id);
            $url = config('app.front_url').'/#/projects/detail/'.$project->id;
            $setting = Setting::select('php_date_format')->first();
            foreach ($project->users as $user) {
                $search = [
                    '{PROJECT_ID}',
                    '{PROJECT_NAME}',
                    '{STATUS}',
                    '{MARKED_BY}',
                    '{PROJECT_URL}',
                    '{START_DATE}',
                    '{END_DATE}',
                    '{DESCRIPTION}',
                    '{COMPANY_NAME}'
                ];
                $replace = [
                    $project->generated_id,
                    $project->project_name,
                    $status[$project->status],
                    auth()->user()->full_name,
                    $url,
                    $project->start_date->format($setting->php_date_format),
                    $project->end_date->format($setting->php_date_format),
                    $project->description,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);

                $this->_sendEmailsInQueue($user->email, $user->name, $subject, $message);
            }
        }
    }

    /**
     * Send project reminder notification.
     *
     * @param Object $project [Project Object]
     *
     * @return Boolean
    */
    public function sendProjectReminderEmails($project)
    {
        $email_template = EmailTemplate::where('type', 'project_reminder')->first();

        if (!empty($email_template)) {
            $project = $this->_getAssignProjectUsers($project->id);
            $setting = Setting::select('php_date_format')->first();
            $url = config('app.front_url').'/#/projects/detail/'.$project->id;
            foreach ($project->users as $user) {
                $search = [
                    '{PROJECT_ID}',
                    '{PROJECT_NAME}',
                    '{NAME}',
                    '{START_DATE}',
                    '{END_DATE}',
                    '{DESCRIPTION}',
                    '{PROJECT_URL}',
                    '{COMPANY_NAME}'
                ];
                $replace = [
                    $project->generated_id,
                    $project->project_name,
                    $user->name,
                    $project->start_date->format($setting->php_date_format),
                    $project->end_date->format($setting->php_date_format),
                    $project->description,
                    $url,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);

                $this->_sendEmailsInQueue($user->email, $user->name, $subject, $message);
            }
        }
    }

    /**
     * Send project user comment.
     *
     * @param Object $logginUser     [Login user object]
     * @param Object $projectcomment [Project comment object]
     *
     * @return Boolean
     */
    public function sendProjectCommentMail($logginUser, $projectcomment)
    {
        $project = $this->_getAssignProjectUsers($projectcomment->project_id);
        $email_template = EmailTemplate::where('type', 'project_comments')->first();

        if (!empty($email_template)) {
            $url = config('app.front_url').'/#/projects/detail/'. $projectcomment->project_id;
            foreach ($project->users as $key => $value) {
                $message = $email_template->template_body;
                $subject = $email_template->template_subject;

                $posted_by = str_replace("{POSTED_BY}", $logginUser->firstname.' '.$logginUser->lastname, $message);
                $project_name = str_replace("{PROJECT_NAME}", $project->project_name, $posted_by);
                $site_url = str_replace("{COMMENT_URL}", $url, $project_name);
                $comment = str_replace("{COMMENT_MESSAGE}", $projectcomment->comment, $site_url);
                $message = str_replace("{SITE_NAME}", config('core.COMPANY_NAME'), $comment);

                $this->_sendEmailsInQueue(
                    $value->email,
                    $value->name,
                    $subject,
                    $message
                );
            }
        }
    }

    /**
     * Send project user attachment.
     *
     * @param Object $logginUser        [Login user object]
     * @param Object $projectAttachment [Project attachment object]
     *
     * @return Boolean
     */
    public function sendProjectAttachmentMail($logginUser, $projectAttachment)
    {
        $project = $this->_getAssignProjectUsers($projectAttachment->project_id);
        $email_template = EmailTemplate::where('type', 'project_attachment')->first();

        if (!empty($email_template)) {
            $url = config('app.front_url').'/#/projects/detail/'. $projectAttachment->project_id;
            foreach ($project->users as $key => $value) {
                $message = $email_template->template_body;
                $subject = $email_template->template_subject;

                $posted_by = str_replace("{UPLOADED_BY}", $logginUser->firstname.' '.$logginUser->lastname, $message);
                $project_name = str_replace("{PROJECT_NAME}", $project->project_name, $posted_by);
                $site_url = str_replace("{PROJECT_URL}", $url, $project_name);
                $message = str_replace("{SITE_NAME}", config('core.COMPANY_NAME'), $site_url);

                $this->_sendEmailsInQueue(
                    $value->email,
                    $value->name,
                    $subject,
                    $message
                );
            }
        }
    }

    /**
     * Get project users.
     *
     * @param Int $projectId [Project id]
     *
     * @return Object
     */
    private function _getAssignProjectUsers($projectId)
    {
        return Project::with([
            'users' => function ($query) {
                    $query->select('id','email',DB::raw("CONCAT(firstname,' ',lastname) as name"))
                        ->where('is_active', true)    
                        ->where('edit',1);
                }
            ])
            ->where('id',$projectId)
            ->first();
    }

    /**
     * Send task/subtask assign email.
     *
     * @param Array  $users   [Users array]
     * @param Object $task    [Task]
     *
     * @return Boolean
     */
    public function sendTaskAssignUserEmail($users, $task)
    {
        $email_template = EmailTemplate::where('type', 'task_assigned')->first();

        if (!empty($email_template)) {

            $url = config('app.front_url').'/#/tasks/detail/'.$task->id;
            $setting = Setting::select('php_date_format')->first();
            $assignUser= User::select('id', 'firstname', 'lastname')->find($task->assign_to);

            foreach ($users as $key => $value) {

                $search = [
                    '{TASK_ID}',
                    '{TASK_NAME}',
                    '{ASSIGNED_TO}',
                    '{ASSIGNED_BY}',
                    '{START_DATE}',
                    '{END_DATE}',
                    '{DESCRIPTION}',
                    '{TASK_URL}',
                    '{COMPANY_NAME}'
                ];
                $replace = [
                    $task->generated_id,
                    $task->name,
                    $assignUser->firstname.' '.$assignUser->lastname,
                    auth()->user()->firstname . ' ' . auth()->user()->lastname,
                    ($task->task_start_date) ? $task->task_start_date->format($setting->php_date_format) : null,
                    ($task->task_end_date) ? $task->task_end_date->format($setting->php_date_format) : null,
                    $task->description,
                    $url,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);

                $this->_sendEmailsInQueue(
                    $value['email'],
                    $value['firstname'].' '.$value['lastname'],
                    $subject,
                    $message
                );
            }
        }
        return true;
    }

    /**
     * Send task update mail.
     *
     * @param Array  $users   [Users array]
     * @param Object $task     [Task object]
     *
     * @return Boolean
     */
    public function sendTaskUpdateMails($users, $task)
    {
        $email_template = EmailTemplate::where('type', 'task_updated')->first();
        if (!empty($email_template)) {

            $url = config('app.front_url').'/#/tasks/detail/'. $task->id;
            $setting = Setting::select('php_date_format')->first();

            foreach ($users as $key => $value) {

                $search = [
                    '{TASK_ID}',
                    '{TASK_NAME}',
                    '{UPDATED_BY}',
                    '{START_DATE}',
                    '{END_DATE}',
                    '{DESCRIPTION}',
                    '{TASK_URL}',
                    '{COMPANY_NAME}'
                ];
                $replace = [
                    $task->generated_id,
                    $task->name,
                    auth()->user()->full_name,
                    ($task->task_start_date) ? $task->task_start_date->format($setting->php_date_format) : null,
                    ($task->task_end_date) ? $task->task_end_date->format($setting->php_date_format) : null,
                    $task->description,
                    $url,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);

                $this->_sendEmailsInQueue(
                    $value->email,
                    $value->name,
                    $subject,
                    $message
                );
            }
        }
    }

    /**
     * Send task change status mail.
     *
     * @param Object $task     [Task object]
     *
     * @return Boolean
     */
    public function sendTaskStatusChangeMails($task)
    {
        $email_template = EmailTemplate::where('type', 'task_status_change')->first();

        if (!empty($email_template)) {

            $url = config('app.front_url').'/#/tasks/detail/'. $task->id;
            $setting = Setting::select('php_date_format')->first();
            $tasks = $this->_getAssignTaskUsers($task->id);

            $status = [
                1 => __('messages.status.open'),
                2 => __('messages.status.in_progress'),
                3 => __('messages.status.on_hold'),
                4 => __('messages.status.waiting'),
                5 => __('messages.status.cancel'),
                6 => __('messages.status.completed')
            ];

            foreach ($tasks->users as $user) {
                $search = [
                    '{TASK_ID}',
                    '{TASK_NAME}',
                    '{STATUS}',
                    '{MARKED_BY}',
                    '{TASK_URL}',
                    '{START_DATE}',
                    '{END_DATE}',
                    '{DESCRIPTION}',
                    '{COMPANY_NAME}'
                ];
                $replace = [
                    $task->generated_id,
                    $task->name,
                    $status[$task->status],
                    auth()->user()->full_name,
                    $url,
                    ($task->task_start_date) ? $task->task_start_date->format($setting->php_date_format) : null,
                    ($task->task_end_date) ? $task->task_end_date->format($setting->php_date_format) : null,
                    $task->description,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);

                $this->_sendEmailsInQueue($user->email, $user->name, $subject, $message);
            }
        }
    }

    /**
     * Send task reminder notification.
     *
     * @param Object $task [Task Object]
     *
     * @return Boolean
    */
    public function sendTaskReminderEmails($task)
    {
        $email_template = EmailTemplate::where('type', 'task_reminder')->first();

        if (!empty($email_template) && $task->assign_to) {
            $setting = Setting::select('php_date_format')->first();
            $url = config('app.front_url').'/#/tasks/detail/'.$task->id;
            $search = [
                '{TASK_ID}',
                '{TASK_NAME}',
                '{NAME}',
                '{START_DATE}',
                '{END_DATE}',
                '{DESCRIPTION}',
                '{TASK_URL}',
                '{COMPANY_NAME}'
            ];
            $replace = [
                $task->generated_id,
                $task->name,
                $task->assignUser->firstname .' '. $task->assignUser->lastname,
                $task->task_start_date->format($setting->php_date_format),
                $task->task_end_date->format($setting->php_date_format),
                $task->description,
                $url,
                config('core.COMPANY_NAME'),
            ];

            $subject = str_replace($search, $replace, $email_template->template_subject);
            $message = str_replace($search, $replace, $email_template->template_body);

            $this->_sendEmailsInQueue($task->assignUser->email, $task->assignUser->firstname .' '. $task->assignUser->lastname, $subject, $message);
        }
    }

    /**
     * Send task assign user comment.
     *
     * @param Object $logginUser  [Login user object]
     * @param Object $taskComment [Task comment object]
     *
     * @return Boolean
     */
    public function sendTaskCommentMail($logginUser, $taskComment)
    {
        $url = config('app.front_url').'/#/tasks/detail/'. $taskComment->task_id;
        $tasks = $this->_getAssignTaskUsers($taskComment->task_id);
        $email_template = EmailTemplate::where('type', 'task_comments')->first();
        if (!empty($email_template)) {
            foreach ($tasks->users as $key => $value) {
                $message = $email_template->template_body;
                $subject = $email_template->template_subject;

                $posted_by = str_replace("{POSTED_BY}", $logginUser->firstname.' '.$logginUser->lastname, $message);
                $task_name = str_replace("{TASK_NAME}", $tasks->name, $posted_by);
                $site_url = str_replace("{COMMENT_URL}", $url, $task_name);
                $comment = str_replace("{COMMENT_MESSAGE}", $taskComment->comment, $site_url);
                $message = str_replace("{SITE_NAME}", config('core.COMPANY_NAME'), $comment);
                
                $this->_sendEmailsInQueue(
                    $value->email,
                    $value->name,
                    $subject,
                    $message
                );
            }
        }
    }

    /**
     * Send task assign user attachment.
     *
     * @param Object $logginUser     [Login user object]
     * @param Object $taskAttachment [Task attachment object]
     *
     * @return Boolean
     */
    public function sendTaskAttachmentMail($logginUser, $taskAttachment)
    {
        $url = config('app.front_url').'/#/tasks/detail/'. $taskAttachment->task_id;
        $tasks = $this->_getAssignTaskUsers($taskAttachment->task_id);
        $email_template = EmailTemplate::where('type', 'task_attachment')->first();
        
        if (!empty($email_template)) {
            foreach ($tasks->users as $key => $value) {
                $message = $email_template->template_body;
                $subject = $email_template->template_subject;

                $posted_by = str_replace("{UPLOADED_BY}", $logginUser->firstname.' '.$logginUser->lastname, $message);
                $task_name = str_replace("{TASK_NAME}", $tasks->name, $posted_by);
                $site_url = str_replace("{TASK_URL}", $url, $task_name);
                $message = str_replace("{SITE_NAME}", config('core.COMPANY_NAME'), $site_url);
                
                $this->_sendEmailsInQueue(
                    $value->email,
                    $value->name,
                    $subject,
                    $message
                );
            }
        }
    }

    /**
     * Get assign task users.
     *
     * @param Int $taskId [Task id]
     *
     * @return Object
     */
    private function _getAssignTaskUsers($taskId)
    {
        return Task::with([
            'users' => function ($query) {
                    $query->select('id','email',DB::raw("CONCAT(firstname,' ',lastname) as name"))
                        ->where('is_active', true)
                        ->where('is_client', false);
                }
            ])
            ->where('id',$taskId)
            ->first();
    }

    /**
     * Send defect assign email.
     *
     * @param Object $users      [Users object]
     * @param Object $defect     [Defect]
     *
     * @return Boolean
     */
    public function sendDefectAssignMails($users, $defect)
    {
        $email_template = EmailTemplate::where('type', 'defect_assigned')->first();

        if (!empty($email_template)) {
            $url = config('app.front_url').'/#/defects/detail/'.$defect->id;
            $setting = Setting::select('php_date_format')->first();
            $assignUser= User::select('id', 'firstname', 'lastname')->find($defect->assign_member);

            foreach ($users as $key => $value) {

                $search = [
                    '{DEFECT_ID}',
                    '{DEFECT_NAME}',
                    '{ASSIGNED_TO}',
                    '{ASSIGNED_BY}',
                    '{START_DATE}',
                    '{END_DATE}',
                    '{DESCRIPTION}',
                    '{DEFECT_URL}',
                    '{COMPANY_NAME}'
                ];
                $replace = [
                    $defect->generated_id,
                    $defect->defect_name,
                    $assignUser->firstname.' '.$assignUser->lastname,
                    auth()->user()->fullname, 
                    ($defect->start_date) ? $defect->start_date->format($setting->php_date_format) : null,
                    ($defect->end_date) ? $defect->end_date->format($setting->php_date_format) : null,
                    $defect->description,
                    $url,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);

                $this->_sendEmailsInQueue(
                    $value['email'],
                    $value['firstname'].' '.$value['lastname'],
                    $subject,
                    $message
                );
            }
        }
        return true;
    }

    /**
     * Send defect update email.
     *
     * @param Object $users      [Users object]
     * @param Object $defect     [Defect]
     *
     * @return Boolean
     */
    public function sendDefectUpdateMails($users, $defect)
    {
        $email_template = EmailTemplate::where('type', 'defect_updated')->first();

        if (!empty($email_template)) {
            $url = config('app.front_url').'/#/defects/detail/'.$defect->id;
            $setting = Setting::select('php_date_format')->first();
            $assignUser= User::select('id', 'firstname', 'lastname')->find($defect->assign_member);

            foreach ($users as $key => $value) {

                $search = [
                    '{DEFECT_ID}',
                    '{DEFECT_NAME}',
                    '{UPDATED_BY}',
                    '{START_DATE}',
                    '{END_DATE}',
                    '{DESCRIPTION}',
                    '{DEFECT_URL}',
                    '{COMPANY_NAME}'
                ];
                $replace = [
                    $defect->generated_id,
                    $defect->defect_name,
                    auth()->user()->fullname, 
                    ($defect->start_date) ? $defect->start_date->format($setting->php_date_format) : null,
                    ($defect->end_date) ? $defect->end_date->format($setting->php_date_format) : null,
                    $defect->description,
                    $url,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);

                $this->_sendEmailsInQueue(
                    $value['email'],
                    $value['name'],
                    $subject,
                    $message
                );
            }
        }
        return true;
    }

    /**
     * Send defects change status mail.
     *
     * @param Object $defect     [Defect object]
     *
     * @return Boolean
     */
    public function sendDefectStatusChangeMails($defect)
    {
        $status_list = [
            1 => 'Assigned',
            2 => 'Closed',
            3 => 'In Progress',
            4 => 'Open',
            5 => 'Solved',
            6 => 'Re-open',
            7 => 'Deferred',
        ];
        $url = config('app.front_url').'/#/defects/detail/'. $defect->id;
        $setting = Setting::select('php_date_format')->first();
        $defects = $this->_getAssignDefectsUsers($defect->id);
        $email_template = EmailTemplate::where('type', 'defect_status_changed')->first();

        if (!empty($email_template)) {
            foreach ($defects->users as $key => $value) {

                $search = [
                    '{DEFECT_ID}',
                    '{DEFECT_NAME}',
                    '{STATUS}',
                    '{MARKED_BY}',
                    '{START_DATE}',
                    '{END_DATE}',
                    '{DESCRIPTION}',
                    '{DEFECT_URL}',
                    '{COMPANY_NAME}'
                ];
                $replace = [
                    $defect->generated_id,
                    $defect->defect_name,
                    $status_list[$defect->status],
                    auth()->user()->fullname, 
                    ($defect->start_date) ? $defect->start_date->format($setting->php_date_format) : null,
                    ($defect->end_date) ? $defect->end_date->format($setting->php_date_format) : null,
                    $defect->description,
                    $url,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);

                $this->_sendEmailsInQueue(
                    $value->email,
                    $value->name,
                    $subject,
                    $message
                );
            }
        }
    }

    /**
     * Send defect reminder notification.
     *
     * @param Object $defect [Defect Object]
     *
     * @return Boolean
    */
    public function sendDefectReminderEmails($defect)
    {
        $email_template = EmailTemplate::where('type', 'defect_reminder')->first();

        if (!empty($email_template) && $defect->assign_member) {
            $setting = Setting::select('php_date_format')->first();
            $url = config('app.front_url').'/#/defects/detail/'.$defect->id;

            $search = [
                '{DEFECT_ID}',
                '{DEFECT_NAME}',
                '{NAME}',
                '{START_DATE}',
                '{END_DATE}',
                '{DESCRIPTION}',
                '{DEFECT_URL}',
                '{COMPANY_NAME}'
            ];
            $replace = [
                $defect->generated_id,
                $defect->defect_name,
                $defect->assignUser->firstname .' '. $defect->assignUser->lastname,
                $defect->start_date->format($setting->php_date_format),
                $defect->end_date->format($setting->php_date_format),
                $defect->description,
                $url,
                config('core.COMPANY_NAME'),
            ];

            $subject = str_replace($search, $replace, $email_template->template_subject);
            $message = str_replace($search, $replace, $email_template->template_body);

            $this->_sendEmailsInQueue($defect->assignUser->email, $defect->assignUser->firstname .' '. $defect->assignUser->lastname, $subject, $message);
        }
    }

    /**
     * Send defects assign user comment.
     *
     * @param Object $logginUser    [Login user object]
     * @param Object $defectComment [Defect comment object]
     *
     * @return Boolean
     */
    public function sendDefectCommentMail($logginUser, $defectComment)
    {
        $url = config('app.front_url').'/#/defects/detail/'. $defectComment->defect_id;
        $defects = $this->_getAssignDefectsUsers($defectComment->defect_id);
        $email_template = EmailTemplate::where('type', 'defect_comments')->first();

        if (!empty($email_template)) {
            foreach ($defects->users as $key => $value) {
                $message = $email_template->template_body;
                $subject = $email_template->template_subject;

                $posted_by = str_replace("{POSTED_BY}", $logginUser->firstname.' '.$logginUser->lastname, $message);
                $defect_name = str_replace("{DEFECT_TITLE}", $defects->defect_name, $posted_by);
                $site_url = str_replace("{COMMENT_URL}", $url, $defect_name);
                $comment = str_replace("{COMMENT_MESSAGE}", $defectComment->comment, $site_url);
                $message = str_replace("{SITE_NAME}", config('core.COMPANY_NAME'), $comment);

                $this->_sendEmailsInQueue(
                    $value->email,
                    $value->name,
                    $subject,
                    $message
                );
            }
        }
    }

    /**
     * Send defects assign user attachment.
     *
     * @param Object $logginUser       [Login user object]
     * @param Object $defectAttachment [Defect Attachment Object]
     *
     * @return Boolean
     */
    public function sendDefectAttachmentMail($logginUser, $defectAttachment)
    {
        $url = config('app.front_url').'/#/defects/detail/'. $defectAttachment->defect_id;
        $defects = $this->_getAssignDefectsUsers($defectAttachment->defect_id);
        $email_template = EmailTemplate::where('type', 'defect_attachment')->first();

        if (!empty($email_template)) {
            foreach ($defects->users as $key => $value) {
                $message = $email_template->template_body;
                $subject = $email_template->template_subject;

                $posted_by = str_replace("{UPLOADED_BY}", $logginUser->firstname.' '.$logginUser->lastname, $message);
                $defect_name = str_replace("{DEFECT_TITLE}", $defects->defect_name, $posted_by);
                $site_url = str_replace("{DEFECT_URL}", $url, $defect_name);
                $message = str_replace("{SITE_NAME}", config('core.COMPANY_NAME'), $site_url);

                $this->_sendEmailsInQueue(
                    $value->email,
                    $value->name,
                    $subject,
                    $message
                );
            }
        }
    }

    /**
     * Get assign defect users.
     *
     * @param Int $defectId [Defect Id]
     *
     * @return Object
     */
    private function _getAssignDefectsUsers($defectId)
    {
        return Defect::with([
            'users' => function ($query) {
                    $query->select('id','email',DB::raw("CONCAT(firstname,' ',lastname) as name"))
                        ->where('is_active', true)
                        ->where('is_client', false);
                }
            ])
            ->where('id',$defectId)
            ->first();
    }

    /**
     * Send incident assign email.
     *
     * @param Array  $users        [Users array]
     * @param Object $incident [Incident]
     *
     * @return Boolean
     */
    public function sendIncidentAssignMails($users, $incident)
    {
        $email_template = EmailTemplate::where('type', 'incident_assigned')->first();
        if (!empty($email_template)) {
            $url = config('app.front_url').'/#/incidents/detail/'.$incident->id;
            $setting = Setting::select('php_date_format')->first();
            $assignUser= User::select('id', 'firstname', 'lastname')->find($incident->assign_to);

            foreach ($users as $key => $value) {

                $search = [
                    '{INCIDENT_ID}',
                    '{INCIDENT_NAME}',
                    '{ASSIGNED_TO}',
                    '{ASSIGNED_BY}',
                    '{START_DATE}',
                    '{END_DATE}',
                    '{DESCRIPTION}',
                    '{INCIDENT_URL}',
                    '{COMPANY_NAME}'
                ];
                $replace = [
                    $incident->generated_id,
                    $incident->incident_name,
                    $assignUser->firstname.' '.$assignUser->lastname,
                    auth()->user()->fullname,
                    ($incident->start_date) ? $incident->start_date->format($setting->php_date_format) : null,
                    ($incident->end_date) ? $incident->end_date->format($setting->php_date_format) : null,
                    $incident->description,
                    $url,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);

                $this->_sendEmailsInQueue(
                    $value['email'],
                    $value['firstname'].' '.$value['lastname'],
                    $subject,
                    $message
                );
            }
        }

        return true;
    }

    /**
     * Send incident update email.
     *
     * @param Object $users      [Users object]
     * @param Object $incident     [Incident]
     *
     * @return Boolean
     */
    public function sendIncidentUpdateMails($users, $incident)
    {
        $email_template = EmailTemplate::where('type', 'incident_updated')->first();

        if (!empty($email_template)) {
            $url = config('app.front_url').'/#/incidents/detail/'.$incident->id;
            $setting = Setting::select('php_date_format')->first();

            foreach ($users as $key => $value) {

                $search = [
                    '{INCIDENT_ID}',
                    '{INCIDENT_NAME}',
                    '{UPDATED_BY}',
                    '{START_DATE}',
                    '{END_DATE}',
                    '{DESCRIPTION}',
                    '{INCIDENT_URL}',
                    '{COMPANY_NAME}'
                ];
                $replace = [
                    $incident->generated_id,
                    $incident->incident_name,
                    auth()->user()->fullname, 
                    ($incident->start_date) ? $incident->start_date->format($setting->php_date_format) : null,
                    ($incident->end_date) ? $incident->end_date->format($setting->php_date_format) : null,
                    $incident->description,
                    $url,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);

                $this->_sendEmailsInQueue(
                    $value['email'],
                    $value['name'],
                    $subject,
                    $message
                );
            }
        }
        return true;
    }

    /**
     * Send incident change status mail.
     *
     * @param Object $incident     [Incident object]
     *
     * @return Boolean
     */
    public function sendIncidentStatusChangeMails($incident)
    {
        $status_list = [
            1 => 'Open',
            2 => 'Assigned',
            3 => 'In Progress',
            4 => 'Solved',
            5 => 'Deferred',
            6 => 'Re-open',
            7 => 'Closed',
        ];

        $url = config('app.front_url').'/#/incidents/detail/'. $incident->id;
        $setting = Setting::select('php_date_format')->first();
        $incidentUsers = $this->_getAssignIncidentUsers($incident->id);
        $email_template = EmailTemplate::where('type', 'incident_status_changed')->first();

        if (!empty($email_template) && !empty($incidentUsers)) {
            foreach ($incidentUsers->users as $key => $value) {

                $search = [
                    '{INCIDENT_ID}',
                    '{INCIDENT_NAME}',
                    '{STATUS}',
                    '{MARKED_BY}',
                    '{START_DATE}',
                    '{END_DATE}',
                    '{DESCRIPTION}',
                    '{INCIDENT_URL}',
                    '{COMPANY_NAME}'
                ];
                $replace = [
                    $incident->generated_id,
                    $incident->incident_name,
                    $status_list[$incident->status],
                    auth()->user()->fullname,
                    ($incident->start_date) ? $incident->start_date->format($setting->php_date_format) : null,
                    ($incident->end_date) ? $incident->end_date->format($setting->php_date_format) : null,
                    $incident->description,
                    $url,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);

                $this->_sendEmailsInQueue(
                    $value->email,
                    $value->name,
                    $subject,
                    $message
                );
            }
        }
    }

    /**
     * Send incident reminder notification.
     *
     * @param Object $incident [Incident Object]
     *
     * @return Boolean
    */
    public function sendIncidentReminderEmails($incident)
    {
        $email_template = EmailTemplate::where('type', 'incident_reminder')->first();

        if (!empty($email_template) && $incident->assign_to) {
            $setting = Setting::select('php_date_format')->first();
            $url = config('app.front_url').'/#/incidents/detail/'.$incident->id;
            $search = [
                '{INCIDENT_ID}',
                '{INCIDENT_NAME}',
                '{NAME}',
                '{START_DATE}',
                '{END_DATE}',
                '{DESCRIPTION}',
                '{INCIDENT_URL}',
                '{COMPANY_NAME}'
            ];
            $replace = [
                $incident->generated_id,
                $incident->incident_name,
                $incident->assignUser->firstname .' '. $incident->assignUser->lastname,
                $incident->start_date->format($setting->php_date_format),
                $incident->end_date->format($setting->php_date_format),
                $incident->description,
                $url,
                config('core.COMPANY_NAME'),
            ];

            $subject = str_replace($search, $replace, $email_template->template_subject);
            $message = str_replace($search, $replace, $email_template->template_body);

            $this->_sendEmailsInQueue($incident->assignUser->email, $incident->assignUser->firstname .' '. $incident->assignUser->lastname, $subject, $message);
        }
    }

    /**
     * Send incidents comment mail.
     *
     * @param Object $logginUser      [Login user object]
     * @param Object $incidentComment [Incident comment]
     *
     * @return Boolean
     */
    public function sendIncidentCommentMail($logginUser, $incidentComment)
    {
        $url = config('app.front_url').'/#/incidents/detail/'. $incidentComment->incident_id;
        $incidentUsers = $this->_getAssignIncidentUsers($incidentComment->incident_id);
        $email_template = EmailTemplate::where('type', 'incident_comments')->first();

        if (!empty($email_template) && !empty($incidentUsers)) {
            $incidentName = $incidentUsers->incident_name;
            foreach ($incidentUsers->users as $key => $value) {
                $message = $email_template->template_body;
                $subject = $email_template->template_subject;

                $posted_by = str_replace("{POSTED_BY}", $logginUser->firstname.' '.$logginUser->lastname, $message);
                $incident_name = str_replace("{INCIDENT_TITLE}", $incidentName, $posted_by);
                $site_url = str_replace("{COMMENT_URL}", $url, $incident_name);
                $comment = str_replace("{COMMENT_MESSAGE}", $incidentComment->comment, $site_url);
                $message = str_replace("{SITE_NAME}", config('core.COMPANY_NAME'), $comment);

                $this->_sendEmailsInQueue(
                    $value->email,
                    $value->name,
                    $subject,
                    $message
                );
            }
        }
    }

    /**
     * Send incidents assign user attachment.
     *
     * @param Object $logginUser         [Login user object]
     * @param Object $incidentAttachment [Incident Attachment]
     *
     * @return Boolean
     */
    public function sendIncidentAttachmentMail($logginUser, $incidentAttachment)
    {
        $url = config('app.front_url').'/#/incidents/detail/'. $incidentAttachment->incident_id;
        $incidentUsers = $this->_getAssignIncidentUsers($incidentAttachment->incident_id);
        $email_template = EmailTemplate::where('type', 'incident_attachment')->first();

        if (!empty($email_template) && !empty($incidentUsers)) {
            $incidentName = $incidentUsers->incident_name;
            foreach ($incidentUsers->users as $key => $value) {
                $message = $email_template->template_body;
                $subject = $email_template->template_subject;

                $posted_by = str_replace("{UPLOADED_BY}", $logginUser->firstname.' '.$logginUser->lastname, $message);
                $incident_name = str_replace("{INCIDENT_TITLE}", $incidentName, $posted_by);
                $site_url = str_replace("{INCIDENT_URL}", $url, $incident_name);
                $message = str_replace("{SITE_NAME}", config('core.COMPANY_NAME'), $site_url);

                $this->_sendEmailsInQueue(
                    $value->email,
                    $value->name,
                    $subject,
                    $message
                );
            }
        }
    }

    /**
     * Get assign incident users.
     *
     * @param Int $incidentId [Row id]
     *
     * @return Object
     */
    private function _getAssignIncidentUsers($incidentId)
    {
        return Incident::with([
            'users' => function ($query) {
                $query->select('id',DB::raw("CONCAT(firstname,' ',lastname) as name"),'email')
                    ->where('is_active', true)
                    ->where('is_client', false);
            }
        ])
        ->where('id', $incidentId)
        ->first();
    }

    /**
     * Send appointment email.
     *
     * @param Array  $users      [Users array]
     * @param Object $appointment    [Appointment details]
     *
     * @return Boolean
     */
    public function sendAppointmentEmail($users, $appointment)
    {
        $email_template = EmailTemplate::where('type', 'appointment_mail')->first();

        if (!empty($email_template)) {

            $status = [
                1 => 'Reserved',
                2 => 'Confirmed',
                3 => 'Finished',
                4 => 'Canceled'
            ];

            foreach ($users as $key => $value) {

                $setting = Setting::select('php_date_format', 'php_time_format', 'php_date_time_format')->first();
                $name = $value->firstname.' '.$value->lastname;

                $search = [
                    '{APPOINTMENT_ID}',
                    '{APPOINTMENT_TITLE}',
                    '{STATUS}',
                    '{NAME}',
                    '{APPOINTEMENT_DATE}',
                    '{APPOINTEMENT_TIME}',
                    '{END_TIME}',
                    '{DESCRIPTION}',
                    '{LOCATION}',
                    '{COMPANY_NAME}'
                ];
                $replace = [
                    $appointment->generated_id,
                    $appointment->title,
                    $status[$appointment->status],
                    $name,
                    $appointment->start_date_time->format($setting->php_date_format),
                    $appointment->start_date_time->format($setting->php_time_format),
                    $appointment->end_date_time->format($setting->php_date_time_format),
                    $appointment->note,
                    $appointment->location,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);
                
                $this->_sendEmailsInQueue($value->email, $name, $subject, $message);
            }
        }
    }

    /**
     * Send appointment reminder notification.
     *
     * @param Object $appointment [Appointment Object]
     *
     * @return Boolean
    */
    public function sendAppointmentReminderEmails($appointment)
    {
        $email_template = EmailTemplate::where('type', 'appointment_reminder')->first();

        if (!empty($email_template)) {
            $setting = Setting::select('php_date_format', 'php_time_format', 'php_date_time_format')->first();
            $search = [
                '{APPOINTMENT_ID}',
                '{APPOINTMENT_TITLE}',
                '{NAME}',
                '{DATE}',
                '{TIME}',
                '{END_TIME}',
                '{DESCRIPTION}',
                '{COMPANY_NAME}'
            ];
            $replace = [
                $appointment->generated_id,
                $appointment->title,
                $appointment->requesters->firstname .' '. $appointment->requesters->lastname,
                $appointment->start_date_time->format($setting->php_date_format),
                $appointment->start_date_time->format($setting->php_time_format),
                $appointment->end_date_time->format($setting->php_date_time_format),
                $appointment->note,
                config('core.COMPANY_NAME'),
            ];

            $subject = str_replace($search, $replace, $email_template->template_subject);
            $message = str_replace($search, $replace, $email_template->template_body);

            $this->_sendEmailsInQueue($appointment->requesters->email, $appointment->requesters->firstname .' '. $appointment->requesters->lastname, $subject, $message);
        }
    }

    /**
     * Send leave request email.
     *
     * @param Object $leave    [Leave details]
     *
     * @return Boolean
     */
    public function sendLeaveRequestEmail($leave, $input = [])
    {
        $email_template = EmailTemplate::where('type', 'leave_request')->first();

        if (!empty($email_template)) {
            $leaveTypes = \Modules\LeaveType\Models\LeaveType::find($leave->leave_type_id);
            $user = User::find($leave->user_id);
            $users = [];
            array_push($users, $user->primary_manager, $user->secondary_manager);
            foreach ($users as $key => $value) {
                $u = User::select('email', 'firstname', 'lastname')->find($value);
                if (!empty($u)){
                    $message = $email_template->template_body;
                    $leave_type = str_replace("{LEAVE_TYPE}", $leaveTypes->leave_type, $message);
                    if ($leave->duration == 'multiple' && isset($input['multi_date'])) {
                        $leave_date = str_replace("{DATE}", implode(',', $input['multi_date']), $leave_type);
                    }else{
                        if ($leave->duration == "half") {
                           $leave_date = str_replace("{DATE}", $leave->leave_date. " (" .ucwords(str_replace('_', ' ', $leave->duration_type)). ")" , $leave_type);
                        }else{
                            $leave_date = str_replace("{DATE}", $leave->leave_date, $leave_type);
                        }
                    }
                    $reason = str_replace("{REASON}", $leave->reason, $leave_date);
                    $message = str_replace("{NAME}", $user->firstname." ".$user->lastname, $reason);

                    $this->_sendEmailsInQueue(
                        $u->email,
                        $u->firstname." ".$u->lastname, 
                        $email_template->template_subject, 
                        $message
                    );
                }
            }
        }
    }

    /**
     * Send leave approvel email.
     *
     * @param Object $leave    [Leave details]
     *
     * @return Boolean
     */
    public function sendLeaveApprovelEmail($leave)
    {
        $email_template = EmailTemplate::where('type', 'approve_leave')->first();

        if (!empty($email_template)) {

            $user = Auth::user();
            $req_user = User::find($leave->user_id);
            $req_user_name = $req_user->firstname." ".$req_user->lastname;

            $message = $email_template->template_body;
            $name = str_replace("{NAME}", $req_user_name, $message);
            $leave_type = str_replace("{LEAVE_TYPE}", $leave->leaveType->leave_type, $name);
            $leave_date = str_replace("{DATE}", $leave->leave_date, $leave_type);
            $message = str_replace("{APPROVED_BY}", $user->firstname." ".$user->lastname, $leave_date);

            $this->_sendEmailsInQueue(
                $req_user->email,
                $req_user_name, 
                $email_template->template_subject, 
                $message
            );
        }
    }

    /**
     * Send leave rejection email.
     *
     * @param Object $leave    [Leave details]
     *
     * @return Boolean
     */
    public function sendLeaveRejectionEmail($leave)
    {
        $email_template = EmailTemplate::where('type', 'reject_leave')->first();

        if (!empty($email_template)) {

            $user = Auth::user();
            $req_user = User::find($leave->user_id);
            $req_user_name = $req_user->firstname." ".$req_user->lastname;

            $message = $email_template->template_body;
            $name = str_replace("{NAME}", $req_user_name, $message);
            $leave_type = str_replace("{LEAVE_TYPE}", $leave->leaveType->leave_type, $name);
            $leave_date = str_replace("{DATE}", $leave->leave_date, $leave_type);
            $reject_reason = str_replace("{REJECT_REASON}", $leave->reject_reason, $leave_date);
            $message = str_replace("{REJECT_BY}", $user->firstname." ".$user->lastname, $reject_reason);

            $this->_sendEmailsInQueue(
                $req_user->email,
                $req_user_name, 
                $email_template->template_subject, 
                $message
            );
        }
    }

    /**
     * Send leave cancel email.
     *
     * @param Object $leave    [Leave details]
     *
     * @return Boolean
     */
    public function sendLeaveCancelEmail($leave)
    {
        $email_template = EmailTemplate::where('type', 'cancel_leave')->first();

        if (!empty($email_template)) {
            $setting = Setting::select('php_date_format')->first();
            $requester = User::find($leave->user_id);
            $users = [];
            array_push($users, $requester->primary_manager, $requester->secondary_manager);
            foreach ($users as $key => $value) {
                $user = User::select('email', 'firstname', 'lastname')->find($value);
                if (!empty($user)){
                    $search = [
                        '{DATE}',
                        '{NAME}'
                    ];
                    $replace = [
                        $leave->leave_date->format($setting->php_date_format),
                        $requester->fullname
                    ];
                    $message = str_replace($search, $replace, $email_template->template_body);

                    $this->_sendEmailsInQueue(
                        $user->email,
                        $user->fullname, 
                        $email_template->template_subject, 
                        $message
                    );
                }
            }
        }
    }

    /**
     * Send estimate to customer.
     *
     * @param Object $estimate    [Estimate details]
     *
     * @return Boolean
     */
    public function sendEstimateEmail($estimate)
    {
        $email_template = EmailTemplate::where('type', 'send_estimate')->first();

        if (!empty($email_template)) {
            $currency = Setting::Currency()->first();
            $setting = Setting::select('company_name', 'company_address', 'company_phone')->first();
            $estimateSetting = \Modules\Estimate\Models\EstimateSetting::first();
            $url = config('app.front_url').'/#/estimates/detail/'.$estimate->id;
            $body = $email_template->template_body;
            $customer_name = str_replace("{CUSTOMER_NAME}", $estimate->client->full_name, $body);
            $amount = str_replace("{AMOUNT}", $currency->symbol.' '.number_format($estimate->total_amount,2), $customer_name);
            $estimate_number = str_replace("{ESTIMATE_NUMBER}", $estimate->estimate_number, $amount);
            $estimate_url = str_replace("{ESTIMATE_URL}", $url, $estimate_number);
            $body = str_replace("{COMPANY_NAME}", config('core.COMPANY_NAME'), $estimate_url);

            $subject = str_replace("{ESTIMATE_NUMBER}", $estimate->estimate_number, $email_template->template_subject);

            \View::addLocation(base_path().'/Modules/Estimate/Resources/views');
            $pdf = \PDF::loadView('estimate-1', compact('estimate', 'currency', 'setting', 'estimateSetting'));
            $pdfStream = $pdf->stream();

            try {
                Mail::send([], [], function ($message) use ($subject, $estimate, $body, $pdfStream) {
                    $message->setBody($body, 'text/html');
                    $message->to($estimate->client->email, $estimate->client->full_name);
                    $message->subject($subject);
                    $message->attachData($pdfStream, $estimate->estimate_number.'.pdf', ['mime' => 'application/pdf']);
                });
                return [ 'status' => true ];
            } catch (\Exception $e) {
                return [ 'status' => false, 'msg' => $e->getMessage() ];
            }
        }
    }

    /**
     * Send estimate declined email to staff.
     *
     * @param Object $estimate    [Estimate details]
     *
     * @return Boolean
     */
    public function sendEstimateDeclinedEmail($estimate)
    {
        $email_template = EmailTemplate::where('type', 'estimate_declined')->first();

        if (!empty($email_template)) {
            $url = config('app.front_url').'/#/estimates/detail/'.$estimate->id;
            $client_name = $estimate->client->firstname.' '.$estimate->client->lastname;
            $subject = str_replace("{ESTIMATE_NUMBER}", $estimate->estimate_number, $email_template->template_subject);
            $body = $email_template->template_body;
            $customer_name = str_replace("{CUSTOMER_NAME}", $client_name, $body);
            $estimate_number = str_replace("{ESTIMATE_NUMBER}", $estimate->estimate_number, $customer_name);
            $message = str_replace("{ESTIMATE_URL}", $url, $estimate_number);

            $this->_sendEmailsInQueue(
                $estimate->user->email,
                $estimate->user->firstname.' '.$estimate->user->lastname, 
                $subject, 
                $message
            );
        }
    }

    /**
     * Send estimate accepted email to staff.
     *
     * @param Object $estimate    [Estimate details]
     *
     * @return Boolean
     */
    public function sendEstimateAcceptedEmail($estimate)
    {
        $email_template = EmailTemplate::where('type', 'estimate_accepted')->first();

        if (!empty($email_template)) {
            $url = config('app.front_url').'/#/estimates/detail/'.$estimate->id;
            $client_name = $estimate->client->firstname.' '.$estimate->client->lastname;
            $subject = str_replace("{ESTIMATE_NUMBER}", $estimate->estimate_number, $email_template->template_subject);
            $body = $email_template->template_body;
            $customer_name = str_replace("{CUSTOMER_NAME}", $client_name, $body);
            $estimate_number = str_replace("{ESTIMATE_NUMBER}", $estimate->estimate_number, $customer_name);
            $message = str_replace("{ESTIMATE_URL}", $url, $estimate_number);

            $this->_sendEmailsInQueue(
                $estimate->user->email,
                $estimate->user->firstname.' '.$estimate->user->lastname, 
                $subject, 
                $message
            );
        }
    }

    /**
     * Send estimate thank you email to customer.
     *
     * @param Object $estimate    [Estimate details]
     *
     * @return Boolean
     */
    public function sendEstimateThankYouEmail($estimate)
    {
        $email_template = EmailTemplate::where('type', 'thank_you_for_accepting_estimate')->first();
        
        if (!empty($email_template)) {
            $client_name = $estimate->client->firstname.' '.$estimate->client->lastname;
            $subject = str_replace("{ESTIMATE_NUMBER}", $estimate->estimate_number, $email_template->template_subject);
            $body = $email_template->template_body;
            $customer_name = str_replace("{CUSTOMER_NAME}", $client_name, $body);
            $estimate_number = str_replace("{ESTIMATE_NUMBER}", $estimate->estimate_number, $customer_name);
            $message = str_replace("{COMPANY_NAME}", config('core.COMPANY_NAME'), $estimate_number);

            $this->_sendEmailsInQueue(
                $estimate->client->email,
                $client_name, 
                $subject, 
                $message
            );
        }
    }

    /**
     * Send estimate expiration reminder email to customer.
     *
     * @param Object $estimate    [Estimate details]
     *
     * @return Boolean
     */
    public function sendEstimateExpirationReminderEmail($estimate)
    {
        $email_template = EmailTemplate::where('type', 'estimate_expiration_reminder')->first();
        
        if (!empty($email_template)) {
            $url = config('app.front_url').'/#/estimates/detail/'.$estimate->id;
            $client_name = $estimate->client->firstname.' '.$estimate->client->lastname;
            $subject = str_replace("{ESTIMATE_NUMBER}", $estimate->estimate_number, $email_template->template_subject);
            $body = $email_template->template_body;
            $customer_name = str_replace("{CUSTOMER_NAME}", $client_name, $body);
            $estimate_number = str_replace("{ESTIMATE_NUMBER}", $estimate->estimate_number, $customer_name);
            $estimate_valid_till = str_replace("{ESTIMATE_VALID_TILL}", date("d-m-Y", strtotime($estimate->valid_till)), $estimate_number);
            $estimate_url = str_replace("{ESTIMATE_URL}", $url, $estimate_valid_till);
            $message = str_replace("{COMPANY_NAME}", config('core.COMPANY_NAME'), $estimate_url);

            $this->_sendEmailsInQueue(
                $estimate->client->email,
                $client_name, 
                $subject, 
                $message
            );
        }
    }

    /**
     * Send invoice to customer.
     *
     * @param Object $invoice    [Invoice details]
     *
     * @return Boolean
     */
    public function sendInvoiceEmail($invoice)
    {
        $email_template = EmailTemplate::where('type', 'send_invoice')->first();

        if (!empty($email_template)) {
            $currency = Setting::Currency()->first();
            $setting = Setting::select('company_name', 'company_address', 'company_phone')->first();
            $invoiceSetting = \Modules\Invoice\Models\InvoiceSetting::first();
            $paymentMethods = \Modules\Payment\Models\PaymentMethod::where('status', 1)->where('show_on_pdf', 1)->get();

            $url = config('app.front_url').'/#/invoices/detail/'.$invoice->id;
            $body = $email_template->template_body;
            $customer_name = str_replace("{CUSTOMER_NAME}", $invoice->client->full_name, $body);
            $amount = str_replace("{AMOUNT}", $currency->symbol.' '.number_format($invoice->total_amount,2), $customer_name);
            $invoice_number = str_replace("{INVOICE_NUMBER}", $invoice->invoice_number, $amount);
            $invoice_url = str_replace("{INVOICE_URL}", $url, $invoice_number);
            $body = str_replace("{COMPANY_NAME}", config('core.COMPANY_NAME'), $invoice_url);

            $subject = str_replace("{INVOICE_NUMBER}", $invoice->invoice_number, $email_template->template_subject);

            \View::addLocation(base_path().'/Modules/Invoice/Resources/views');
            $pdf = \PDF::loadView('invoice-1', compact('invoice', 'currency', 'setting', 'invoiceSetting', 'paymentMethods'));
            $pdfStream = $pdf->stream();

            try {
                Mail::send([], [], function ($message) use ($subject, $invoice, $body, $pdfStream) {
                    $message->setBody($body, 'text/html');
                    $message->to($invoice->client->email, $invoice->client->full_name);
                    $message->subject($subject);
                    $message->attachData($pdfStream, $invoice->invoice_number.'.pdf', ['mime' => 'application/pdf']);
                });
                return [ 'status' => true ];
            } catch (\Exception $e) {
                return [ 'status' => false, 'msg' => $e->getMessage() ];
            }
        }
    }

    /**
     * Send invoice expiration reminder email to customer.
     *
     * @param Object $invoice    [Invoice details]
     *
     * @return Boolean
     */
    public function sendInvoiceExpirationReminderEmail($invoice)
    {
        $email_template = EmailTemplate::where('type', 'invoice_overdue_notice')->first();
        
        if (!empty($email_template)) {
            $url = config('app.front_url').'/#/invoices/detail/'.$invoice->id;
            $subject = str_replace("{INVOICE_NUMBER}", $invoice->invoice_number, $email_template->template_subject);
            $body = $email_template->template_body;
            $customer_name = str_replace("{CUSTOMER_NAME}", $invoice->client->full_name, $body);
            $invoice_number = str_replace("{INVOICE_NUMBER}", $invoice->invoice_number, $customer_name);
            $invoice_due_date = str_replace("{DUE_DATE}", date("d-m-Y", strtotime($invoice->due_date)), $invoice_number);
            $invoice_url = str_replace("{INVOICE_URL}", $url, $invoice_due_date);
            $message = str_replace("{COMPANY_NAME}", config('core.COMPANY_NAME'), $invoice_url);

            $this->_sendEmailsInQueue(
                $invoice->client->email,
                $invoice->client->full_name, 
                $subject, 
                $message
            );
        }
    }

    /**
     * Send payment receipt to customer.
     *
     * @param Object $payment    [Payment details]
     *
     * @return Boolean
     */
    public function sendPaymentEmailToCustomer($payment)
    {
        $email_template = EmailTemplate::where('type', 'payment_added_send_to_customer')->first();

        if (!empty($email_template)) {
            $currency = Setting::Currency()->first();
            $setting = Setting::select('company_name', 'company_address', 'company_phone')->first();
            $invoiceSetting = \Modules\Invoice\Models\InvoiceSetting::first();
            $url = config('app.front_url').'/#/invoices/detail/'.$payment->invoice_id;
            $body = $email_template->template_body;
            $customer_name = str_replace("{CUSTOMER_NAME}", $payment->client->full_name, $body);
            $receipt_number = str_replace("{RECEIPT_NUMBER}", $payment->receipt_number, $customer_name);
            $invoice_number = str_replace("{INVOICE_NUMBER}", $payment->invoice->invoice_number, $receipt_number);
            $invoice_url = str_replace("{INVOICE_URL}", $url, $invoice_number);
            $body = str_replace("{COMPANY_NAME}", config('core.COMPANY_NAME'), $invoice_url);

            $subject = str_replace("{INVOICE_NUMBER}", $payment->invoice->invoice_number, $email_template->template_subject);

            \View::addLocation(base_path().'/Modules/Payment/Resources/views');
            $pdf = \PDF::loadView('payment-1', compact('payment', 'currency', 'setting', 'invoiceSetting'));
            $pdfStream = $pdf->stream();

            try {
                Mail::send([], [], function ($message) use ($subject, $payment, $body, $pdfStream) {
                    $message->setBody($body, 'text/html');
                    $message->to($payment->client->email, $payment->client->full_name);
                    $message->subject($subject);
                    $message->attachData($pdfStream, $payment->receipt_number.'.pdf', ['mime' => 'application/pdf']);
                });
                return [ 'status' => true ];
            } catch (\Exception $e) {
                return [ 'status' => false, 'msg' => $e->getMessage() ];
            }
        }
    }

    /**
     * Send payment email to staff.
     *
     * @param Object $payment    [Payment details]
     *
     * @return Boolean
     */
    public function sendPaymentEmailToStaff($payment)
    {
        $email_template = EmailTemplate::where('type', 'payment_added_send_to_staff')->first();

        if (!empty($email_template)) {
            $url = config('app.front_url').'/#/invoices/detail/'.$payment->invoice_id;
            $subject = str_replace("{INVOICE_NUMBER}", $payment->invoice->invoice_number, $email_template->template_subject);
            $body = $email_template->template_body;
            $invoice_number = str_replace("{INVOICE_NUMBER}",  $payment->invoice->invoice_number, $body);
            $invoice_url = str_replace("{INVOICE_URL}", $url, $invoice_number);
            $message = str_replace("{CUSTOMER_NAME}", $payment->client->full_name, $invoice_url);

            $this->_sendEmailsInQueue(
                $payment->invoice->user->email,
                $payment->invoice->user->full_name, 
                $subject, 
                $message
            );
        }
    }

    /**
     * Send project overdue reminder.
     *
     * @param Object $project [Project Details]
     *
     * @return Boolean
     */
    public function sendProjectOverdueReminderEmails($project)
    {
        $email_template = EmailTemplate::where('type', 'project_overdue')->first();
        $setting = Setting::select('php_date_format')->first();
        if (!empty($email_template)) {
            $url = config('app.front_url').'/#/projects/detail/'.$project->id;
            foreach ($project->users as $user) {
                $search = [
                    '{PROJECT_ID}',
                    '{NAME}',
                    '{PROJECT_NAME}',
                    '{END_DATE}',
                    '{PROJECT_URL}',
                    '{START_DATE}',
                    '{DESCRIPTION}',
                    '{COMPANY_NAME}'
                ];
                $replace = [
                    $project->generated_id,
                    $user->fullname,
                    $project->project_name,
                    $project->end_date->format($setting->php_date_format),
                    $url,
                    $project->start_date->format($setting->php_date_format),
                    $project->description,
                    config('core.COMPANY_NAME'),
                ];

                $subject = str_replace($search, $replace, $email_template->template_subject);
                $message = str_replace($search, $replace, $email_template->template_body);

                $this->_sendEmailsInQueue($user->email, $user->name, $subject, $message);
            }
        }
    }

    /**
     * Send task overdue reminder.
     *
     * @param Object $task [Task Details]
     *
     * @return Boolean
     */
    public function sendTaskOverdueReminderEmails($task)
    {
        $email_template = EmailTemplate::where('type', 'task_overdue')->first();
        $setting = Setting::select('php_date_format')->first();
       
        if (!empty($email_template) && $task->assign_to) {
            $setting = Setting::select('php_date_format')->first();
            $url = config('app.front_url').'/#/tasks/detail/'.$task->id;
            $search = [
                '{TASK_ID}',
                '{NAME}',
                '{TASK_NAME}',
                '{END_DATE}',
                '{TASK_URL}',
                '{START_DATE}',
                '{DESCRIPTION}',
                '{COMPANY_NAME}'
            ];
            $replace = [
                $task->generated_id,
                $task->assignUser->firstname .' '. $task->assignUser->lastname,
                $task->name,
                $task->task_end_date->format($setting->php_date_format),
                $url,
                $task->task_start_date->format($setting->php_date_format),
                $task->description,
                config('core.COMPANY_NAME'),
            ];

            $subject = str_replace($search, $replace, $email_template->template_subject);
            $message = str_replace($search, $replace, $email_template->template_body);

            $this->_sendEmailsInQueue($task->assignUser->email, $task->assignUser->firstname .' '. $task->assignUser->lastname, $subject, $message);
        }
    }

    /**
     * Send defect overdue reminder.
     *
     * @param Object $defect [Defect Details]
     *
     * @return Boolean
     */
    public function sendDefectOverdueReminderEmails($defect)
    {
        $email_template = EmailTemplate::where('type', 'defect_overdue')->first();
        $setting = Setting::select('php_date_format')->first();
        
        if (!empty($email_template) && $defect->assign_member) {
            $url = config('app.front_url').'/#/defects/detail/'.$defect->id;
            $search = [
                '{DEFECT_ID}',
                '{NAME}',
                '{DEFECT_NAME}',
                '{END_DATE}',
                '{DEFECT_URL}',
                '{START_DATE}',
                '{DESCRIPTION}',
                '{COMPANY_NAME}'
            ];
            $replace = [
                $defect->generated_id,
                $defect->assignUser->firstname .' '. $defect->assignUser->lastname,
                $defect->defect_name,
                $defect->end_date->format($setting->php_date_format),
                $url,
                $defect->start_date->format($setting->php_date_format),
                $defect->description,
                config('core.COMPANY_NAME'),
            ];

            $subject = str_replace($search, $replace, $email_template->template_subject);
            $message = str_replace($search, $replace, $email_template->template_body);

            $this->_sendEmailsInQueue($defect->assignUser->email, $defect->assignUser->firstname .' '. $defect->assignUser->lastname, $subject, $message);
        }
    }

    /**
     * Send incident overdue reminder.
     *
     * @param Object $task [Incident Details]
     *
     * @return Boolean
     */
    public function sendIncidentOverdueReminderEmails($incident)
    {
        $email_template = EmailTemplate::where('type', 'incident_overdue')->first();
        $setting = Setting::select('php_date_format')->first();
        
        if (!empty($email_template) && $incident->assign_to) {
            $setting = Setting::select('php_date_format')->first();
            $url = config('app.front_url').'/#/incidents/detail/'.$incident->id;
            $search = [
                '{INCIDENT_ID}',
                '{NAME}',
                '{INCIDENT_NAME}',
                '{END_DATE}',
                '{INCIDENT_URL}',
                '{START_DATE}',
                '{DESCRIPTION}',
                '{COMPANY_NAME}'
            ];
            $replace = [
                $incident->generated_id,
                $incident->assignUser->firstname .' '. $incident->assignUser->lastname,
                $incident->incident_name,
                $incident->end_date->format($setting->php_date_format),
                $url,
                $incident->start_date->format($setting->php_date_format),
                $incident->description,
                config('core.COMPANY_NAME'),
            ];

            $subject = str_replace($search, $replace, $email_template->template_subject);
            $message = str_replace($search, $replace, $email_template->template_body);

            $this->_sendEmailsInQueue($incident->assignUser->email, $incident->assignUser->firstname .' '. $incident->assignUser->lastname, $subject, $message);
        }
    }
}
