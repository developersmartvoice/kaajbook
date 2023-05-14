<?php

namespace Modules\Setting\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DashboardSetting
 *
 * The Model is Defined for Dashboard Setting.
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
class DashboardSetting extends Model
{
	const MODULE_NAME = 'DashboardSetting';

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'is_projects_widget',
		'is_tasks_widget',
		'is_defects_widget',
		'is_incidents_widget',
		'is_estimates_widget',
		'is_invoices_widget',
		'is_payments_widget',
		'is_leaves_widget',
		'is_users_widget',
		'is_clients_widget',
		'is_teams_widget',
		'is_articles_widget',
		'is_projects_status_chart',
		'is_tasks_status_chart',
		'is_monthly_report_chart',
		'is_projects_list',
		'is_tasks_list',
		'is_defects_list',
		'is_incidents_list',
		'is_estimates_list',
		'is_invoices_list',
		'is_todos_list',
		'is_activities_list',
		'is_meetings_list',
		'is_announcement_list'
	];

	/**
	 * The attributes that are not mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [];

	/**
	 * Instantiate a new model instance.
	 *
	 * @param Array $attributes [object]
	 *
	 * @return void
	 */
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
		
		$this->table = config('core.acl.dashboard_settings_table');
	}
}
