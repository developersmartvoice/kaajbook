<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTimerLogsTable
 *
 * The Migrations is Defined for Timer Log.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Timesheet
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.1@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.1
 */
class CreateTimerLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.timer_logs_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.timer_logs_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->integer('project_id')->nullable();
                $table->integer('reference_id');
                $table->boolean('is_task_timer')->default(false);
                $table->boolean('is_defect_timer')->default(false);
                $table->boolean('is_incident_timer')->default(false);
                $table->dateTime('start_time');
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.timer_logs_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
