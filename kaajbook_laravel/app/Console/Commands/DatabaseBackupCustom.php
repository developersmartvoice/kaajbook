<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Modules\DatabaseBackup\Models\DatabaseBackup;
use Modules\Setting\Models\Setting;
use Modules\User\Models\User\User;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DatabaseBackupCustom extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup-custom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new database backup';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('max_execution_time', 720); // 120 (seconds) = 2 Minutes
        $user = User::where('is_super_admin', 1)->first();
        if (!empty($user)) {
            $path = public_path() . '/database_backup';
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }

            $configs = config('database.connections.mysql');
            $filename = 'database_'. date('Y\-m\-j\_H\-i\-s').'.sql';
            $storage_path =  $path . '/'.$filename;
            $process = new Process('mysqldump -u '.$configs['username'].' -p'.$configs['password'].' '.$configs['database'].' > '.$storage_path);

            // --
            // Backup database
            try {
                // --
                // Save into DB
                $databaseBackup = new DatabaseBackup;
                $databaseBackup->user_id = $user->id;
                $databaseBackup->file_name = $filename;
                $databaseBackup->save();

                $process->setTimeout(2 * 3600);
                $process->setIdleTimeout(10 * 60);
                $process->run();

                $databaseBackup->status = 1;
                if ($databaseBackup->save()) {
                    $setting = Setting::first();
                    $setting->last_autobackup = time();
                    $setting->save();
                    return true;
                } else {
                    return false;
                }
            } catch (ProcessFailedException $exception) {
            }
        } else {
            return false;
        }
    }
}
