<?php

namespace Modules\Installer\Http\Controllers;

use App\Http\Controllers\Controller;
use Artisan;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Modules\Installer\Helpers\DatabaseManager;
use Modules\Installer\Helpers\InstalledFileManager;
use Modules\Setting\Models\Setting;
use Modules\User\Models\User\User;

/**
 * Class UpdateController
 *
 * Update functionality
 *
 * Update System
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
class UpdateController extends Controller
{
	private $tmp_backup_dir = null;
	private $update_array = [];

	use \Modules\Installer\Helpers\MigrationsHelper;

	/**
	 * Display the updater welcome page.
	 *
	 * @return \Illuminate\View\View
	 */
	public function welcome()
	{
		\View::addLocation(base_path().'/Modules/Installer/Views');
		\View::addNamespace('theme', base_path().'/Modules/Installer/Views');
		return \View::make('theme::update.welcome');
	}

	/**
	 * Display the updater overview page.
	 *
	 * @return \Illuminate\View\View
	 */
	public function overview()
	{
		$migrations = $this->getMigrations();
		$dbMigrations = $this->getExecutedMigrations();
		\View::addLocation(base_path().'/Modules/Installer/Views');
		\View::addNamespace('theme', base_path().'/Modules/Installer/Views');
		return \View::make('theme::update.overview', ['numberOfUpdatesPending' => count($migrations) - count($dbMigrations)]);
	}

	/**
	 * Migrate and seed the database.
	 *
	 * @return \Illuminate\View\View
	 */
	public function database()
	{
		$databaseManager = new DatabaseManager;
		return $databaseManager->migrateAndSeed(false);
	}

	/**
	 * Update installed file and display finished view.
	 *
	 * @param InstalledFileManager $fileManager [filemanager]
	 *
	 * @return View
	 */
	public function finish(InstalledFileManager $fileManager)
	{
		$fileManager->update();
		return redirect()->route('LaravelInstaller::welcome');
	}

	/**
	 * Check latest version available.
	 *
	 * @return string
	 */
	public function checkUpdateVersion()
	{
		return '';
		// $lastVersionInfo = $this->getLastVersion();

		// if(!empty($lastVersionInfo)) {
		// 	if ($lastVersionInfo['version'] > $this->getCurrentVersion()) {
		// 		return $lastVersionInfo['version'];
		// 	} else {
		// 		return '';
		// 	}
		// } else {
		// }
	}

	/**
	 * Return latest version info.
	 *
	 * @return string
	 */
	private function getLastVersion()
	{
		try {
			$content = file_get_contents(config('laraupdater.update_baseurl').'/laraupdater.json');
			$content = json_decode($content, true);
			return $content;
		}
		catch (\Exception $e) {
			return array();
		}
	}

	/**
	 * Return current version (as plain text).
	 *
	 * @return string
	 */
	public function getCurrentVersion()
	{
		$version = File::get(base_path().'/version.txt');
		return $version;
	}

	/**
	 * Check user allowed permissions.
	 *
	 * @return boolean
	 */
	private function checkPermission()
	{
		if (config('laraupdater.allow_users_id') !== null) {
			if (config('laraupdater.allow_users_id') === false) {
				return true;
			}

			//--
			// Check user
			if (in_array(Auth::user()->id, config('laraupdater.allow_users_id')) === true) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}

	/**
	 * Download and install system update.
	 *
	 * @return Json
	 */
	public function updateSystem()
	{
		ini_set('max_execution_time', 30000);
		ini_set('memory_limit', '8192M');
		if (!$this->checkPermission()) {
			return response()->json("Access is denied. You may not have the appropriate permissions to access the file.", 403);
		}

		// --
		// Check for last version.
		$lastVersionInfo = $this->getLastVersion();
		if ($lastVersionInfo['version'] <= $this->getCurrentVersion()) {
			return response()->json("Your System is already updated to last version", 200);
		}

		// --
		// Check purchase code/licence
		$setting = [];
		$isPurchased = false;
		$settings = Setting::first();
		if( !empty($settings['update_url']) && !empty($settings['purchase_key']) && !empty($settings['unique_coder']) ){
			// --
			// Get purchase code from API
			$ch = curl_init();
			$optArray = array(
				CURLOPT_URL => $settings['update_url'].'?purchase_key='.$settings['purchase_key'].'&'.'unique_coder='.$settings['unique_coder'],
				CURLOPT_RETURNTRANSFER => true
			);
			curl_setopt_array($ch, $optArray);
			$response = curl_exec($ch);
			$data = json_decode($response);

			if(count((array)$data) != 0) {
				if($data->purchase_buyer == $settings['unique_coder']) {
					$isPurchased = true;
				} else {
					$isPurchased = false;
				}
			} else {
				return response()->json(['error' => "Invalid purchase code, Please enter the valid purchase code",] ,400);
			}
		}

		if(!$isPurchased) {
		   return response()->json(['error' => "Sorry you did not purchase the item. Please enter accurate buyer name"], 400);
		}

		try {
			// --
			// Temp directory path.
			$this->tmp_backup_dir = base_path().'/backup_'.date('Ymd');
			// --
			// Download archive.
			$update_path = null;
			if (($update_path = $this->download($lastVersionInfo['archive'])) === false) {
				return response()->json(['error' => "Error during download."], 400);
			}

			Artisan::call('down'); 

			// --
			// Install update.
			$status = $this->install($lastVersionInfo['version'], $update_path, $lastVersionInfo['archive']);
			
			if ($status) {

				$this->setCurrentVersion($lastVersionInfo['version']);

				// Migration and seeding.
				$this->update_array['database'] = $this->database();

				Artisan::call('config:cache', [], []);

				Artisan::call('up');

				return response()->json($this->update_array);
			} else {
				return response()->json(['error' => 'Error during update.'], 400);
			}
		} catch (\Exception $e) {
			$this->restore();
			return response()->json(['error' => 'Error during update (!!check the update archive!!) -- Try to restore OLD status'], 400);
		}
	}

	/**
	 * Download update from $update_baseurl to $tmp_path (local folder).
	 * 
	 * @param string $update_name [update name]
	 *
	 * @return boolean
	 */
	private function download($update_name)
	{
		try {
			$filename_tmp = config('laraupdater.tmp_path').'/'.$update_name;
			if (!is_file($filename_tmp)) {
				$newUpdate = file_get_contents(config('laraupdater.update_baseurl').'/'.$update_name);
				$dlHandler = fopen($filename_tmp, 'w');
				if (!fwrite($dlHandler, $newUpdate)) {
					return false;
				}
			}
		} catch (\Exception $e) {
			return false;
		}

		return $filename_tmp;
	}

	/**
	 * Restore old version.
	 *
	 * @return boolean
	 */
	private function restore()
	{
		if (!isset($this->tmp_backup_dir)) {
			$this->tmp_backup_dir = base_path().'/backup_'.date('Ymd');
		}

		try {
			$backup_dir = $this->tmp_backup_dir;
			$backup_files = File::allFiles($backup_dir);

			foreach ($backup_files as $file) {
				$filename = (string)$file;
				$filename = substr($filename, (strlen($filename)-strlen($backup_dir)-1)*(-1));
				File::copy($backup_dir.'/'.$filename, base_path().'/'.$filename);
			}
		} catch (\Exception $e) {
			return false;
		}
		return true;
	}

	/**
	 * Install latest version.
	 * 
	 * @param string $lastVersion [last version]
	 * @param string $update_path [update path]
	 * @param string $archive     [archive]
	 *
	 * @return boolean
	 */
	private function install($lastVersion, $update_path, $archive)
	{
		try {
			$except_root = array('.env','.env.example','package.json','composer.json');
			$execute_commands = false;
			$upgrade_cmds_filename = 'upgrade.php';
			$upgrade_cmds_path = config('laraupdater.tmp_path').'/'.$upgrade_cmds_filename;

			$zipHandle = zip_open($update_path);
			$archive = substr($archive, 0, -4);
			while ($zip_item = zip_read($zipHandle)) {
				$filename = zip_entry_name($zip_item);
				$dirname = dirname($filename);

				// --
				// Exclude these cases (1/2)
				if (substr($filename, -1, 1) == '/' || dirname($filename) === $archive || substr($dirname, 0, 2) === '__') {
					continue;
				}

				// --
				// Exclude root folder (if exist)
				if (substr($dirname, 0, strlen($archive)) === $archive) {
					$dirname = substr($dirname, (strlen($dirname)-strlen($archive)-1)*(-1));
				}

				// --
				// Exclude these cases
				if ($dirname === '.') {
					continue;
				}

				// --
				// Continue all root file.
				if ($dirname === 'vipspm_angular_laravel') {
					$file = str_replace('vipspm_angular_laravel/', '', $filename);
					if (!in_array($file, $except_root)) {
						continue;
					} else {
						$dirname = str_replace('vipspm_angular_laravel', '', $dirname);
					}
				}

				$filename = str_replace('vipspm_angular_laravel/', '', $filename);
				$dirname = str_replace('vipspm_angular_laravel/', '', $dirname);

				// --
				// Skip specific directory
				if ($dirname == "storage") {
					continue;
				}
				if ($dirname == "bootstrap") {
					continue;
				}
				if ($dirname == "Core") {
					continue;
				}
			
				if ($dirname == "uploads") {
					continue;
				}
				if ($dirname == ".git") {
					continue;
				}
				if ($dirname == "vendor") {
					continue;
				}

				if (strpos($filename, '.htaccess') == true) {
					continue;
				}

				if (strpos($filename, 'git/') == true) {
					continue;
				}

				if (strstr($filename, 'vendor/') == true) {
					continue;
				}

				if (strstr($filename, 'bootstrap/') == true) {
					continue;
				}

				if (strstr($filename, 'storage/') == true) {
					continue;
				}

				$filename = $dirname.'/'.basename($filename);

				// --
				// Replace/create directory.
				if (!is_dir(base_path().'/'.$dirname)) {
					//Make new directory (if exist also in current version continue...)
					File::makeDirectory(base_path().'/'.$dirname, $mode = 0755, true, true);

					// --
					// Log directory.
					if (!isset($this->update_array['directory'])) {
						$this->update_array['directory'] = [];
					}
					array_push($this->update_array['directory'], $dirname);
				}

				// --
				// Replace/create files.
				if (!is_dir(base_path().'/'.$filename)) { 
					// --
					//Overwrite a file with its last version.
					$contents = zip_entry_read($zip_item, zip_entry_filesize($zip_item));
					$contents = str_replace("\r\n", "\n", $contents);

					if (strpos($filename, 'upgrade.php') !== false) {
						File::put($upgrade_cmds_path, $contents);
						$execute_commands = true;
					} else {
						if (File::exists(base_path().'/'.$filename)) {
							$this->backup($filename); // //backup current version.
						}

						if ($filename == '/.env') {
							// --
							// Current .env
							$current_env = File::get(base_path().$filename);
							$current_env_data = preg_split('/\r\n|\r|\n/', $current_env);
							foreach ($current_env_data as $key => $value) {
								$line = explode('=', $value);
								$line[0] != '' ? $lineData[] = $line[0] : '';
							}
							// --
							// New .env
							$new_env = preg_split('/\r\n|\r|\n/', $contents);
							foreach ($new_env as $key1 => $value1) {
								$new_line = explode('=', $value1);
								if ($new_line[0] != '') {
									if (!in_array($new_line[0], $lineData)) {
										$current_env .= "\n\n".$new_line[0]."=".@$new_line[1];
									}
								}
							}
							$contents = $current_env;
						}

						File::put(base_path().'/'.$filename, $contents);
						unset($contents);

						// --
						// Log files.
						if (!isset($this->update_array['files'])) {
							$this->update_array['files'] = [];
						}
						array_push($this->update_array['files'], $filename);
					}
				}
			}
			zip_close($zipHandle);

			// --
			// Check command execute.
			if ($execute_commands == true) {
				include $upgrade_cmds_path;
				//upgrade-VERSION.php contains the 'main()' method with a BOOL return to check its execution.
				// if(main())
				//     echo '<p class="success">&raquo; Commands successfully executed.</p>';
				// else
				//     echo '<p class="danger">&raquo; Error during commands execution.</p>';

				unlink($upgrade_cmds_path); // Unlink files.
				File::delete($upgrade_cmds_path); // clean TMP
			}

			File::delete($update_path); // clean TMP.
			File::deleteDirectory($this->tmp_backup_dir); // Remove backup temp folder.
		} catch (\Exception $e) {
			print_r($e->getMessage());
			return false;
		}
		return true;
	}

	/**
	 * Backup current version.
	 *
	 * @param string $filename [file name]
	 */
	private function backup($filename)
	{
		$backup_dir = $this->tmp_backup_dir;

		if (!is_dir($backup_dir)) {
			File::makeDirectory($backup_dir, $mode = 0755, true, true);
		}
		if (!is_dir($backup_dir.'/'.dirname($filename))) {
			File::makeDirectory($backup_dir.'/'.dirname($filename), $mode = 0755, true, true);
		}
		//To backup folder.
		File::copy(base_path().'/'.$filename, $backup_dir.'/'.$filename);
	}

	/**
	 * Set current version.
	 *
	 * @param string $last [Version]
	 */
	private function setCurrentVersion($last)
	{
		File::put(base_path().'/version.txt', $last);
	}

	/**
	 * Run db changes script.
	 *
	 * @return Json
	 */
	public function updateDB()
	{
		$databaseManager = new DatabaseManager;
		$res = $databaseManager->updateDB();
		pr($res);
		exit;
	}
}
