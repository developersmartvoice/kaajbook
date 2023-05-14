<?php

namespace Modules\Installer\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Modules\Installer\Http\Middleware\canInstall;
use Modules\Installer\Http\Middleware\canUpdate;

/**
 * Class LaravelInstallerServiceProvider
 *
 * The Service is Defined for LaravelInstaller.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\LaravelInstaller
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class LaravelInstallerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->publishFiles();
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    /**
     * Bootstrap the application events.
     *
     * @param Router $router [router]
     * 
     * @return void
     */
    public function boot(Router $router)
    {
        $router->middlewareGroup('install', [CanInstall::class]);
        $router->middlewareGroup('update', [canUpdate::class]);
    }

    /**
     * Publish config file for the installer.
     *
     * @return void
     */
    protected function publishFiles()
    {
        $this->publishes(
            [
            __DIR__.'/../Config/installer.php' => base_path('config/installer.php'),
            ],
            'laravelinstaller'
        );

        $this->publishes(
            [
            __DIR__.'/../assets' => public_path('installer'),
            ],
            'laravelinstaller'
        );

        $this->publishes(
            [
            __DIR__.'/../Views' => base_path('resources/views/vendor/installer'),
            ],
            'laravelinstaller'
        );

        $this->publishes(
            [
            __DIR__.'/../Lang' => base_path('resources/lang'),
            ],
            'laravelinstaller'
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
