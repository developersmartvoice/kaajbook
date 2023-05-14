<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateProjectSprintTaskMembersTable
 *
 * The Migrations is Defined for Project Sprint Task.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\ProjectSprintTask
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateProjectSprintTaskMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.project_sprint_task_members_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.project_sprint_task_members_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('task_id')->unsigned();
                $table->foreign('task_id')
                    ->references('id')
                    ->on(config('core.acl.project_sprint_tasks_table'))
                    ->onDelete('cascade');
                $table->integer('user_id')
                    ->unsigned()
                    ->nullable(false);
                $table->foreign('user_id')
                    ->references('id')
                    ->on(config('core.acl.users_table'))
                    ->onDelete('cascade');
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
        Schema::dropIfExists(config('core.acl.project_sprint_task_members_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
