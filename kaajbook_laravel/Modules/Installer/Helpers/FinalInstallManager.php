<?php

namespace Modules\Installer\Helpers;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Class FinalInstallManager
 *
 * Final generate app key and publish vendor packages.
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
class FinalInstallManager
{
    /**
     * Run final commands.
     *
     * @return Collection
     */
    public function runFinal()
    {
        $outputLog = new BufferedOutput;

        $this->generateKey($outputLog);
        $this->publishVendorAssets($outputLog);

        return $outputLog->fetch();
    }

    /**
     * Generate New Application Key.
     *
     * @param Collection $outputLog [Output log]
     *
     * @return Collection
     */
    public function generateKey($outputLog)
    {
        try {
            $configs = getConfigs();
            if ($configs['final']['key']) {
                Artisan::call('key:generate', ["--force"=> true], $outputLog);
            }
        } catch (Exception $e) {
            return $this->response($e->getMessage(), $outputLog);
        }

        return $outputLog;
    }

    /**
     * Publish vendor assets.
     *
     * @param Collection $outputLog [Output log]
     *
     * @return Collection
     */
    public function publishVendorAssets($outputLog)
    {
        try {
            $configs = getConfigs();
            if ($configs['final']['publish']) {
                Artisan::call('vendor:publish', ['--all' => true], $outputLog);
            }
        } catch (Exception $e) {
            return $this->response($e->getMessage(), $outputLog);
        }

        return $outputLog;
    }
    
    /**
     * Return a formatted error messages.
     *
     * @param String     $message   [Response message]
     * @param Collection $outputLog [Output log]
     *
     * @return Array
     */
    public function response($message, $outputLog)
    {
        return [
            'status' => 'error',
            'message' => $message,
            'dbOutputLog' => $outputLog->fetch()
        ];
    }
}
