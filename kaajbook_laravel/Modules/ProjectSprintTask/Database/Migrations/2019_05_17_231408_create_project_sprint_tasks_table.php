<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateProjectSprintTasksTable
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
class CreateProjectSprintTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.project_sprint_tasks_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.project_sprint_tasks_table'),
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('project_sprint_id')->unsigned()->nullable();
                $table->foreign('project_sprint_id')
                    ->references('id')
                    ->on(config('core.acl.project_sprints_table'))
                    ->onDelete('cascade');
                $table->integer('project_id')->nullable();
                $table->string('name');
                $table->text('description')->nullable();
                $table->tinyInteger('status')
                    ->default(1)
                    ->comment('Task:1=Open, 2=OnHold, 3=Closed, 4=Released|Story:1=Open, 2=InProgress, 3=Closed');
                $table->string('type');
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->string('estimated_hours')->nullable();
                $table->tinyInteger('assign_to')->nullable();
                $table->softDeletes();
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
        Schema::dropIfExists(config('core.acl.project_sprint_tasks_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
