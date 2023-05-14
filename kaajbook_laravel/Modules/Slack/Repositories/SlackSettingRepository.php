<?php

namespace Modules\Slack\Repositories;

use Auth;
use Modules\Slack\Models\SlackSetting;

/**
 * Class SlackSettingRepository
 *
 * Slack Setting create, update and view.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Slack
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class SlackSettingRepository
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return object
	 */
	public function findAll()
	{
		return SlackSetting::first();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request [Request for create/update slack setting]
	 *
	 * @return Boolean
	 */
	public function create($request)
	{
		$user = Auth::user();
		$slackSetting = SlackSetting::first();
		$input = $request->all();
		if ($slackSetting->fill($input)->save()) {
			// --
			// Add activities
			createUserActivity(
				SlackSetting::MODULE_NAME,
				$slackSetting->id,
				'PUT',
				$user->full_name,
				$request->ip()
			);
			return true;
		}
		return false;
	}
}
