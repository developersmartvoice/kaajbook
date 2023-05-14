<?php

namespace Modules\Installer\Helpers;

use Exception;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Output\BufferedOutput;
use Modules\CustomField\Database\Seeders\CustomFieldDatabaseSeeder;
use Modules\EmailGroup\Database\Seeders\EmailGroupDatabaseSeeder;
use Modules\EmailTemplate\Database\Seeders\EmailTemplateDatabaseSeeder;
use Modules\Estimate\Database\Seeders\EstimateDatabaseSeeder;
use Modules\Helper\Database\Seeders\HelperDatabaseSeeder;
use Modules\Invoice\Database\Seeders\InvoiceDatabaseSeeder;
use Modules\LeaveType\Database\Seeders\LeaveTypeDatabaseSeeder;
use Modules\Menu\Database\Seeders\MenuDatabaseSeeder;
use Modules\Setting\Models\Setting;
use Modules\Helper\Database\Seeders\CountryDatabaseTableSeeder;

/**
 * Class DatabaseManager
 *
 * Database functionality.
 *
 * Database migrate seed.
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
class DatabaseManager
{
    /**
     * Migrate and seed the database.
     *
     * @param Boolean $isReset [Boolean for reset]
     *
     * @return Array
     */
    public function migrateAndSeed($isReset = true)
    {
        $outputLog = new BufferedOutput;

        $this->_sqlite($outputLog);

        return $this->_migrate($outputLog, $isReset);
    }

    /**
     * Run the migration and call the seeder.
     *
     * @param collection $outputLog [Output log]
     * @param Boolean    $isReset   [Boolean for reset]
     *
     * @return Collection
     */
    private function _migrate($outputLog, $isReset)
    {
        try {
            ini_set('max_execution_time', 30000);

            if ($isReset) {
                // --
                // Database connection
                $mysqli = new \mysqli(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'));
                // Check for errors
                if (mysqli_connect_errno()) {
                    return $this->_response(
                        "Error: Unable to connect to MySQL." . PHP_EOL,
                        $outputLog,
                        'error'
                    );
                }

                $path_to_file = base_path().'/install.sql';
                $file_contents = file_get_contents($path_to_file);
                $file_contents = str_replace(
                    "vipspm",
                    env('TABLE_PREFIX'),
                    $file_contents
                );
                // Create temp SQL file.
                $path_to_temp_file = base_path().'/public/uploads/install_temp.sql';
                file_put_contents($path_to_temp_file, $file_contents);
                // Open the default SQL file.
                $query = @file_get_contents($path_to_temp_file);
                // Execute a multi query.
                $mysqli->multi_query($query);

                do {
                } while (mysqli_more_results($mysqli) && mysqli_next_result($mysqli));

                unlink($path_to_temp_file); // Unlink temp sql file.
                $mysqli->close(); // Close the connection.
            } else {
                Artisan::call('migrate', ["--force"=> true], $outputLog);
            }
        } catch (Exception $e) {
            return $this->_response($e->getMessage(), $outputLog, 'error');
        }

        return $this->_seed($outputLog, $isReset);
    }

    /**
     * Seed the database.
     *
     * @param collection $outputLog [Output log]
     * @param Boolean    $isReset   [Boolean for reset]
     *
     * @return Array
     */
    private function _seed($outputLog, $isReset)
    {
        try {
            if (!$isReset) {
                Artisan::call('db:seed', ["--class" => CountryDatabaseTableSeeder::class], $outputLog);
                Artisan::call('db:seed', ["--class" => HelperDatabaseSeeder::class], $outputLog);
                Artisan::call('db:seed', ["--class" => CustomFieldDatabaseSeeder::class], $outputLog);
                Artisan::call('db:seed', ["--class" => EmailGroupDatabaseSeeder::class], $outputLog);
                Artisan::call('db:seed', ["--class" => EmailTemplateDatabaseSeeder::class], $outputLog);
                Artisan::call('db:seed', ["--class" => MenuDatabaseSeeder::class], $outputLog);
            }
        } catch (Exception $e) {
            return $this->_response($e->getMessage(), $outputLog, 'error');
        }

        return $this->_response(
            trans('installer_messages.final.finished'),
            $outputLog,
            'success'
        );
    }

    /**
     * Return a formatted error messages.
     *
     * @param String     $message   [Response message]
     * @param Collection $outputLog [Response outputlog]
     * @param String     $status    [Response status]
     *
     * @return Array
     */
    private function _response($message, $outputLog, $status = 'danger')
    {
        return [
            'status' => $status,
            'message' => $message,
            'dbOutputLog' => $outputLog->fetch()
        ];
    }

    /**
     * Check database type. If SQLite, then create the database file.
     *
     * @param Collection $outputLog [Output log]
     *
     * @return database file.
     */
    private function _sqlite($outputLog)
    {
        if (DB::connection() instanceof SQLiteConnection) {
            $database = DB::connection()->getDatabaseName();
            if (!file_exists($database)) {
                touch($database);
                DB::reconnect(Config::get('database.default'));
            }
            $outputLog->write('Using SqlLite database: ' . $database, 1);
        }
    }

    /**
     * Run db changes script.
     *
     * @return Array
     */
    public function updateDB()
    {
        $outputLog = new BufferedOutput;
        try {
            $users_settings_table = config('core.acl.users_settings_table');
            if (!Schema::hasColumn($users_settings_table, 'product_version')) {
                Schema::table(
                    $users_settings_table,
                    function (Blueprint $table) {
                        $table->string('product_version', 10)->nullable();
                    }
                );
            }
            
            $settings = Setting::first();
            // For new module.
            Artisan::call('config:cache');
            Artisan::call('migrate', ["--force" => true], $outputLog);

            if ($settings->product_version == '' || $settings->product_version < 1.2) {
                // For Changes.
                $path_to_file = base_path('public/sql/update_1_2.sql');
                $file_contents = file_get_contents($path_to_file);
                $file_contents = str_replace("vipspm", config('core.table_prefix'), $file_contents);
                DB::unprepared($file_contents);

                $settings->product_version = '1.2';
                $settings->save();
            }

            if ($settings->product_version < 1.3) {
                // For Changes.
                $path_to_file = base_path('public/sql/update_1_3.sql');
                $file_contents = file_get_contents($path_to_file);
                $file_contents = str_replace("vipspm", config('core.table_prefix'), $file_contents);
                DB::unprepared($file_contents);

                $settings->product_version = '1.3';
                $settings->save();
            }

            if ($settings->product_version < 2.0) {
                // For Changes.
                $path_to_file = base_path('public/sql/update_2_0.sql');
                $file_contents = file_get_contents($path_to_file);
                $file_contents = str_replace("vipspm", config('core.table_prefix'), $file_contents);
                DB::unprepared($file_contents);

                // For seeding.
                Artisan::call('db:seed', [
                    "--class" => CountryDatabaseTableSeeder::class,
                    "--force" => true
                ], $outputLog);
                Artisan::call('db:seed', [
                    "--class" => LeaveTypeDatabaseSeeder::class,
                    "--force" => true
                ], $outputLog);
                Artisan::call('db:seed', [
                    "--class" => EstimateDatabaseSeeder::class,
                    "--force" => true
                ], $outputLog);
                Artisan::call('db:seed', [
                    "--class" => InvoiceDatabaseSeeder::class,
                    "--force" => true
                ], $outputLog);

                $settings->product_version = '2.0';
                $settings->save();
            }

            if ($settings->product_version < 2.1) {
                $settings->product_version = '2.1';
                $settings->save();
            }

            if ($settings->product_version < 2.2) {
                // --
                // Database connection
                $mysqli = new \mysqli(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'));
                // Check for errors
                if (mysqli_connect_errno()) {
                    return $this->_response(
                        "Error: Unable to connect to MySQL." . PHP_EOL,
                        $outputLog,
                        'error'
                    );
                }

                $path_to_file = base_path('/public/sql/update_2_2.sql');
                $file_contents = file_get_contents($path_to_file);
                $query = str_replace(
                    "vipspm_",
                    env('TABLE_PREFIX'). '_',
                    $file_contents
                );
                // Execute a multi query.
                $mysqli->multi_query($query);
                do {
                } while (mysqli_more_results($mysqli) && mysqli_next_result($mysqli));

                $settings->product_version = '2.2';
                $settings->save();
            }

        } catch (Exception $e) {
            return $this->_response($e->getMessage(), $outputLog, 'error');
        }

        return $this->_response(
            trans('installer_messages.updater.final.finished'),
            $outputLog,
            'success'
        );
    }
}
