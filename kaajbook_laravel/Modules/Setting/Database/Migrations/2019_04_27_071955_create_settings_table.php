<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateSettingsTable
 *
 * The Migrations is Defined for Setting.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Setting
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateSettingsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		\DB::statement('SET FOREIGN_KEY_CHECKS = 0');
		Schema::dropIfExists(config('core.acl.users_settings_table'));
		\DB::statement('SET FOREIGN_KEY_CHECKS = 1');

		Schema::create(
			config('core.acl.users_settings_table'),
			function (Blueprint $table) {
				$table->increments('id')->unsigned();
				$table->string('company_name', 150)->nullable();
				$table->string('company_legal_name', 150)->nullable();
				$table->string('company_short_name', 50)->default('VipsPM');
				$table->string('contact_person', 150)->nullable();
				$table->text('company_address')->nullable();
				$table->bigInteger('company_country')->nullable();
				$table->string('company_city', 150)->nullable();
				$table->string('company_zipcode', 10)->nullable();
				$table->string('company_phone')->nullable();
				$table->string('company_email')->nullable();
				$table->string('company_website')->nullable();
				$table->string('company_from_email')->nullable();
				$table->boolean('post_mark')->default(false);
				$table->string('smtp_protocol', 50)->nullable();
				$table->string('smtp_host')->nullable();
				$table->string('smtp_user')->nullable();
				$table->string('smtp_password')->nullable();
				$table->string('smtp_port', 10)->nullable();
				$table->string('smtp_encryption', 10)->nullable();
				$table->string('mail_from_name')->nullable();
				$table->string('sparkpost_secret')->nullable();
				$table->string('mailgun_domain')->nullable();
				$table->string('mailgun_secret')->nullable();
				$table->string('mandrill_secret')->nullable();
				$table->boolean('is_announcement_notification')->default(true);
				$table->boolean('active_cronjob')->default(false);
				$table->boolean('automatic_backup')->default(false);
				$table->text('daily_report_emails')->nullable();
				$table->string('last_autobackup')->default(0);
				$table->string('cronjob_link')->nullable();
				$table->string('last_cronjob_run')->nullable();
				$table->string('reminder_alert_before_hours')->nullable()->default('6,12,24')->comment('For appointment and meeting reminder.');
				$table->string('reminder_alert_before_days')->nullable()->default('1,2,3')->comment('For project, task, defect and incident reminder.');
				$table->string('login_background')->nullable();
				$table->string('company_logo')->nullable();
				$table->string('company_sidebar_logo')->nullable();
				$table->text('sidebar_background_images')->nullable();
				$table->string('default_language', 50)->default('en');
				$table->string('default_locale', 100)->nullable();
				$table->string('timezone')->nullable();
				$table->integer('tables_pagination_limit')->default(10);
				$table->string('date_format', 50)->default('YYYY-MM-DD');
				$table->string('time_format', 50)->default('hh:mm A');
				$table->string('date_time_format', 50)->default('YYYY-MM-DD hh:mm A');
				$table->string('php_date_format', 50)->default('Y-m-d');
				$table->string('php_time_format', 50)->default('H:i');
				$table->string('php_date_time_format', 50)->default('Y-m-d H:i');
				$table->integer('currency_id')->unsigned()->nullable();
				$table->string('purchase_key')->nullable();
				$table->string('unique_coder')->nullable();
				$table->string('update_url')->nullable();
				$table->boolean('is_collapsed_menu')->default(true);
				$table->boolean('is_sidebar_background')->default(true);
				$table->string('theme_layout', 50)->default('light');
				$table->string('sidebar_bg_color', 100)->default('pomegranate-gr');
				$table->string('sidebar_transparent_bg_image', 100)->default('bg_glass_1');
				$table->string('sidebar_bg_custom_color', 50)->default('#000000');
				$table->string('sidebar_font_color', 50)->default('black');
				$table->string('sidebar_width', 50)->default('small');
				$table->integer('sidebar_bg_image')->default(0);
				$table->string('product_version', 10)->nullable();
				$table->boolean('is_demo')->default(false);
				$table->boolean('allowed_for_registration')->default(true);
				$table->timestamps();
			}
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		\DB::statement('SET FOREIGN_KEY_CHECKS = 0');
		Schema::dropIfExists(config('core.acl.users_settings_table'));
		\DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}
}
