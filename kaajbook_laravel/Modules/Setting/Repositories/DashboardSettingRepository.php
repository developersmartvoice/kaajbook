<?php

namespace Modules\Setting\Repositories;

use Auth;
use Modules\Setting\Models\DashboardSetting;

/**
 * Class DashboardSettingRepository
 *
 * Dashboard Setting update and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Setting
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class DashboardSettingRepository
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return object
	 */
	public function findAll()
	{
		return DashboardSetting::first();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request [Request for update dashboard setting]
	 *
	 * @return Boolean
	 */
	public function create($request)
	{
		$user = Auth::user();
		$dashboardSetting = DashboardSetting::first();
		$input = $request->all();

		if ($dashboardSetting->fill($input)->save()) {
			// --
			// Add activities
			createUserActivity(
				DashboardSetting::MODULE_NAME,
				$dashboardSetting->id,
				'PUT',
				$user->full_name,
				$request->ip()
			);
			return true;
		} else {
			return false;
		}
	}
}
