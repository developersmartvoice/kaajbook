<?php

namespace Modules\Installer\Helpers;

use Exception;
use Illuminate\Http\Request;

/**
 * Class EnvironmentManager
 *
 * Environment store and get .env
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Installer
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @author    Another Author <another@example.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class EnvironmentManager
{
    private $_envPath;
    private $_envExamplePath;

    /**
     * Set the .env and .env.example paths.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_envPath = base_path('.env');
        $this->_envExamplePath = base_path('.env.example');
    }

    /**
     * Get the content of the .env file.
     *
     * @return String
     */
    public function getEnvContent()
    {
        if (!file_exists($this->_envPath)) {
            if (file_exists($this->_envExamplePath)) {
                copy($this->_envExamplePath, $this->_envPath);
            } else {
                touch($this->_envPath);
            }
        }

        return file_get_contents($this->_envPath);
    }

    /**
     * Get the the .env file path.
     *
     * @return String
     */
    public function getEnvPath()
    {
        return $this->_envPath;
    }

    /**
     * Get the .env.example file path.
     *
     * @return String
     */
    public function getEnvExamplePath()
    {
        return $this->_envExamplePath;
    }

    /**
     * Save the edited content to the .env file.
     *
     * @param Request $input [Request for save file classic]
     *
     * @return String
     */
    public function saveFileClassic(Request $input)
    {
        $message = trans('installer_messages.environment.success');

        try {
            file_put_contents($this->_envPath, $input->get('envConfig'));
        } catch (Exception $e) {
            $message = trans('installer_messages.environment.errors');
        }

        return $message;
    }

    /**
     * Save the form content to the .env file.
     *
     * @param Request $request [Request for set file wizard]
     *
     * @return String
     */
    public function saveFileWizard(Request $request)
    {
        $results = trans('installer_messages.environment.success');

        $envFileData =
        'APP_NAME=' . preg_replace('/\s+/', '', $request->app_name) . "\n" .
        'APP_ENV=' . $request->environment . "\n" .
        'APP_KEY=' . 'base64:bODi8VtmENqnjklBmNJzQcTTSC8jNjBysfnjQN59btE=' . "\n" .
        'APP_DEBUG=' . $request->app_debug . "\n" .
        'APP_LOG_LEVEL=' . $request->app_log_level . "\n" .
        'APP_URL=' . preg_replace('/\s+/', '', $request->app_url) . "\n" .
        'FRONT_URL=' . preg_replace('/\s+/', '', $request->front_url) . "\n\n" .
        'LOG_CHANNEL=stack' . "\n\n" .
        'DB_CONNECTION=mysql' . "\n" .
        'DB_HOST=' . preg_replace('/\s+/', '', $request->database_hostname) . "\n" .
        'DB_PORT=' . preg_replace('/\s+/', '', $request->database_port) . "\n" .
        'DB_DATABASE=' . preg_replace('/\s+/', '', $request->database_name) . "\n" .
        'DB_USERNAME=' . preg_replace('/\s+/', '', $request->database_username) . "\n" .
        'DB_PASSWORD=' . preg_replace('/\s+/', '', $request->database_password) . "\n\n" .
        'BROADCAST_DRIVER=log'. "\n" .
        'CACHE_DRIVER=file'. "\n" .
        'QUEUE_CONNECTION=database'. "\n" .
        'SESSION_DRIVER=file'. "\n" .
        'SESSION_LIFETIME=120'. "\n\n" .
        'REDIS_HOST=127.0.0.1'. "\n" .
        'REDIS_PASSWORD=null'. "\n" .
        'REDIS_PORT=6379'. "\n\n" .
        'MAIL_DRIVER=' . preg_replace('/\s+/', '', $request->mail_driver) . "\n" .
        'MAIL_HOST=' . preg_replace('/\s+/', '', $request->mail_host) . "\n" .
        'MAIL_PORT=' . preg_replace('/\s+/', '', $request->mail_port) . "\n" .
        'MAIL_USERNAME=' . preg_replace('/\s+/', '', $request->mail_username) . "\n" .
        'MAIL_PASSWORD=' . preg_replace('/\s+/', '', $request->mail_password) . "\n" .
        'MAIL_ENCRYPTION=' . preg_replace('/\s+/', '', $request->mail_encryption) . "\n" .
        'MAIL_FROM_ADDRESS='. preg_replace('/\s+/', '', $request->site_email). "\n" .
        'MAIL_FROM_NAME=' . "'" . preg_replace('/\s+/', '', $request->app_name). "'" . "\n" .
        'MAILGUN_DOMAIN='. "\n" .
        'MAILGUN_SECRET='. "\n" .
        'SPARKPOST_SECRET='. "\n" .
        'MANDRILL_SECRET='. "\n\n" .
        'PUSHER_APP_ID='. "\n" .
        'PUSHER_APP_KEY='. "\n" .
        'PUSHER_APP_SECRET='. "\n" .
        'PUSHER_APP_CLUSTER=mt1'. "\n\n" .
        'MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"'. "\n" .
        'MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"'. "\n\n" .
        'SUPPORT_MAIL_ID='. preg_replace('/\s+/', '', $request->support_email) . "\n\n" .
        'TABLE_PREFIX='. preg_replace('/\s+/', '', $request->table_prefix). "\n\n" .
        'TIMEZONE='. preg_replace('/\s+/', '', $request->timezone). "\n" .
        'LOCALE=en'. "\n" .
        'API_DEBUG=false'. "\n".
        'SYSTEM_UPDATE_URL=http://adminpanel.sw/api/verify/purchase_code'. "\n".
        'JWT_SECRET=yuzzhRrcg5NvjhPwXtCBjhVilwUczN0MBkQJyTU3IgbwaXMnDH24erackLZmUQUL';

        try {
            file_put_contents($this->_envPath, $envFileData);
        } catch (Exception $e) {
            $results = trans('installer_messages.environment.errors');
        }

        return $results;
    }
}
