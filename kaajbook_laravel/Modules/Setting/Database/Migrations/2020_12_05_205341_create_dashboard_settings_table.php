<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateDashboardSettingsTable
 *
 * The Migrations is Defined for Dashboard Setting.
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
class CreateDashboardSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.dashboard_settings_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.dashboard_settings_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->boolean('is_projects_widget')->default(true);
                $table->boolean('is_tasks_widget')->default(true);
                $table->boolean('is_defects_widget')->default(true);
                $table->boolean('is_incidents_widget')->default(true);
                $table->boolean('is_estimates_widget')->default(true);
                $table->boolean('is_invoices_widget')->default(true);
                $table->boolean('is_payments_widget')->default(true);
                $table->boolean('is_leaves_widget')->default(true);
                $table->boolean('is_users_widget')->default(true);
                $table->boolean('is_clients_widget')->default(true);
                $table->boolean('is_teams_widget')->default(true);
                $table->boolean('is_articles_widget')->default(true);
                $table->boolean('is_projects_status_chart')->default(true);
                $table->boolean('is_tasks_status_chart')->default(true);
                $table->boolean('is_monthly_report_chart')->default(true);
                $table->boolean('is_projects_list')->default(true);
                $table->boolean('is_tasks_list')->default(true);
                $table->boolean('is_defects_list')->default(true);
                $table->boolean('is_incidents_list')->default(true);
                $table->boolean('is_estimates_list')->default(true);
                $table->boolean('is_invoices_list')->default(true);
                $table->boolean('is_todos_list')->default(true);
                $table->boolean('is_activities_list')->default(true);
                $table->boolean('is_meetings_list')->default(true);
                $table->boolean('is_announcement_list')->default(true);
                $table->timestamps();
            }
        );

        $dashboardSetting = new Modules\Setting\Models\DashboardSetting();
        $dashboardSetting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.dashboard_settings_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
