<?php

namespace Modules\Installer\Helpers;

/**
 * Class InstalledFileManager
 *
 * Create installed file when successfuly installed.
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
class InstalledFileManager
{
    /**
     * Create installed file.
     *
     * @return Int
     */
    public function create()
    {
        $installedLogFile = storage_path('installed');

        $dateStamp = date("Y/m/d h:i:sa");

        if (!file_exists($installedLogFile)) {
            $message = trans('installer_messages.installed.success_log_message') . 
            $dateStamp . "\n";

            file_put_contents($installedLogFile, $message);
        } else {
            $message = trans('installer_messages.updater.log.success_message') 
            . $dateStamp;

            file_put_contents(
                $installedLogFile,
                $message.PHP_EOL,
                FILE_APPEND | LOCK_EX
            );
        }

        return $message;
    }

    /**
     * Update installed file.
     *
     * @return Int
     */
    public function update()
    {
        return $this->create();
    }
}
