<?php

namespace Modules\User\Models\User\Relationship;

/**
 * Trait UserRelationship
 *
 * The Relationship is Defined For User.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\User
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
trait UserRelationship
{
	/**
	 * Has-Many relations with mails.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\hasMany
	 */
	public function mails()
	{
		return $this->hasMany(config('core.acl.mailbox'));
	}

	/**
	 * Many-to-Many relations with departments.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function departments()
	{
		return $this->belongsToMany(
			config('core.acl.department'),
			config('core.acl.user_role_department')
		);
	}

	/**
	 * Many-to-Many relations with roles.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function roles()
	{
		return $this->belongsToMany(
			config('core.acl.role'),
			config('core.acl.user_role_department')
		);
	}

	/**
	 * Has-Many relations with ToDo.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\hasMany
	 */
	public function toDo()
	{
		return $this->hasMany(config('core.acl.todo_model'));
	}

	/**
	 * Has-Many relations with announcement.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\hasMany
	 */
	public function announcement()
	{
		return $this->hasMany(config('core.acl.announcement'));
	}

	/**
	 * Many-to-Many relations with meeting.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function meetings()
	{
		return $this->belongsToMany(
			config('core.acl.meeting_model'),
			config('core.acl.meeting_members')
		);
	}

	/**
	 * Has-Many relations with user activity.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\hasMany
	 */
	public function userActivity()
	{
		return $this->hasMany(config('core.acl.user_activity'));
	}

	/**
	 * Has-One relations with clients.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\hasOne
	 */
	public function clients()
	{
		return $this->hasOne(config('core.acl.clients_model'));
	}

	/**
	 * Has-One relations with settings.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\hasOne
	 */
	public function settings()
	{
		return $this->hasOne(config('core.acl.user_setting_model'), 'user_id');
	}

	/**
	 * Many-to-Many relations with projects.
	 *
	 * @param Boolean $assign [boolean]
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function projects($assign = false)
	{
		$projects = $this->belongsToMany(
			config('core.acl.project_model'),
			config('core.acl.project_user_table'),
			'user_id',
			'project_id'
		)
		->withPivot('view', 'edit', 'delete');

		if ($assign) {
			$projects->wherePivot('edit', 1);
		}

		return $projects;
	}

	/**
	 * Many-to-Many relations with tasks.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function tasks()
	{
		return $this->belongsToMany(
			config('core.acl.task_model'),
			config('core.acl.task_user_table')
		)->withPivot('task_id');
	}

	/**
	 * Many-to-Many relations with defects.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function defects()
	{
		return $this->belongsToMany(
			config('core.acl.defects_model'),
			config('core.acl.defects_user_table'),
			'user_id',
			'defect_id'
		);
	}

	/**
	 * Many-to-Many relations with incidents.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function incidents()
	{
		return $this->belongsToMany(
			config('core.acl.incidents_model'),
			config('core.acl.incident_user_table'),
			'user_id',
			'incident_id'
		);
	}

	/**
	 * Has-Many relations with timesheet.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\hasMany
	 */
	public function timesheet()
	{
		return $this->hasMany(
			config('core.acl.timesheet_model'),
			'created_user_id'
		);
	}

	/**
	 * Has-Many relations with ToDo.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\hasMany
	 */
	public function slacks()
	{
		return $this->hasOne(config('core.acl.slack_model'));
	}
}
