<?php

namespace Modules\Installer\Http\Middleware;

use Closure;
use DB;
use Redirect;
use Illuminate\Http\Request;

/**
 * Class CreateAnnouncementsTable
 *
 * The Migrations is Defined for CreateAnnouncementsTable.
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
class canInstall
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request [Request object]
     * @param Closure $next    [Closure object]
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $configs = getConfigs();
        if ($this->alreadyInstalled()) {
            $installedRedirect = $configs['installedAlreadyAction'];

            switch ($installedRedirect) {
            case 'route':
                $routeName = $configs['installed']['redirectOptions']['route']['name'];
                $data = $configs['installed']['redirectOptions']['route']['data'];
                return redirect()->route($routeName)->with(['data' => $data]);
                    break;

            case 'abort':
                abort($configs['installed']['redirectOptions']['abort']['type']);
                break;

            case 'dump':
                $dump = $configs['installed']['redirectOptions']['dump']['data'];
                dd($dump);
                break;

            case '404':
            case 'default':
            default:
                abort(404);
                break;
            }
        }
        return $next($request);
    }

    /**
     * If application is already installed.
     *
     * @return bool
     */
    public function alreadyInstalled()
    {
        return file_exists(storage_path('installed'));
    }
}
