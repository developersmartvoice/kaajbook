<?php

namespace Modules\Installer\Http\Middleware;

use Closure;
use Modules\Installer\Http\Middleware\canInstall;

/**
 * Class canUpdate
 *
 * The class is Defined for update product.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\CreateAnnouncementsTable
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class canUpdate
{
    use \Modules\Installer\Helpers\MigrationsHelper;

    /**
     * Handle an incoming request.
     *
     * @param Request $request [Request]
     * @param Closure $next    [next]
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $configs = getConfigs();

        $updateEnabled = filter_var($configs['updaterEnabled'], FILTER_VALIDATE_BOOLEAN);
        switch ($updateEnabled) {
        case true:
            $canInstall = new canInstall;

            // if the application has not been installed,
            // redirect to the installer
            if (!$canInstall->alreadyInstalled()) {
                return redirect()->route('LaravelInstaller::welcome');
            }

            // --
            // Check for migration counts
            // if($this->alreadyUpdated()) {
            //     abort(404);
            // }
            break;

        case false:
        default:
            abort(404);
            break;
        }

        return $next($request);
    }

    /**
     * If application is already updated.
     *
     * @return bool
     */
    public function alreadyUpdated()
    {
        $migrations = $this->getMigrations();
        $dbMigrations = $this->getExecutedMigrations();

        // If the count of migrations and dbMigrations is equal,
        // then the update as already been updated.
        if (count($migrations) == count($dbMigrations)) {
            return true;
        }

        // Continue, the app needs an update
        return false;
    }
}
