<?php

namespace Modules\Helper\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Modules\Helper\Helpers\AdminHelper;
use Modules\Helper\Repositories\HelperRepository;
use Modules\Setting\Models\Setting;

/**
 * Class HelperController
 *
 * Helper functionas.
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
class HelperController extends Controller
{
	protected $helperRepo;

	/**
	 * Instantiate a new repository instance.
	 *
	 * @param HelperRepository $helper [Object]
	 *
	 * @return void
	 */
	public function __construct(HelperRepository $helper)
	{
		$this->helperRepo = $helper;
	}

	/**
	 * Get countries.
	 *
	 * @return Response
	 */
	public function getCountries()
	{
		return $this->helperRepo->getCountries();
	}

	/**
	 * Get locals.
	 *
	 * @return Response
	 */
	public function getLocales()
	{
		return $this->helperRepo->getLocales();
	}

	/**
	 * Get languages.
	 *
	 * @return Response
	 */
	public function getLanguages()
	{
		return $this->helperRepo->getLanguages();
	}

	/**
	 * Get time zones.
	 *
	 * @return Response
	 */
	public function getTimezones()
	{
		return $this->helperRepo->getTimezones();
	}

	/**
	 * Get days.
	 *
	 * @return Response
	 */
	public function getDays()
	{
		return $this->helperRepo->getDays();
	}

	/**
	 * Get currencies.
	 *
	 * @return Response
	 */
	public function getCurrencies()
	{
		return $this->helperRepo->getCurrencies();
	}

	/**
     * Get data for dashboard.
     *
     * @param Request $request [Request for get dashboard data]
     *
     * @return Response
     */
    public function getDashboardData(Request $request)
    {
        return $this->helperRepo->getDashboardData($request);
    }

	/**
	 * Cronjob for task report, backupdatabase and annoucement overdue.
	 *
	 * @return Response
	 */
	public function cronjob()
	{
		return Artisan::call('schedule:run');
		exit;

		$setting = Setting::first();
		if (!empty($setting) && isset($setting->active_cronjob)) {

			Artisan::call('queue:work', ["--tries"=> 3]);

			$isAllowed = false;
			if (!isset($setting->last_cronjob_run)) {
				$isAllowed = true;
			}

			if (isset($setting->last_cronjob_run) && time() > ($setting->last_cronjob_run + 300)) {
				$isAllowed = true;
			}

			if ($isAllowed) {
				// --
				// For daily evening task mail.
				Artisan::call('work:day');

				// --
				// Backup database
				if (isset($setting->automatic_backup) && time() > ($setting->last_autobackup + 7 * 24 * 60 * 60)) {
					Artisan::call('db:backup-custom');
				}

				// --
				// Annoucement overdue mail
				$this->helperRepo->removeOverdueAnnoucement();

				// --
				// Save settings
				$setting->last_cronjob_run = time();
				$setting->save();
			}
			return response()->json('Cronjob has been run successfully.');
		}
		return response()->json('There are no any setting found for cronjob.', 422);
	}

	/**
     * Reset default database.
     *
     * @return Void
     */
    public function resetDatabase()
    {
        $response = $this->helperRepo->resetDatabase();   
    }
}
