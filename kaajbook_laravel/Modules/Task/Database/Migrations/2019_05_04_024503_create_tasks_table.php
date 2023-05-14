<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTasksTable
 *
 * The Migrations is Defined for Task.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Task
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.task_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.task_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('parent_task_id')->default(0);
                $table->string('generated_id');
                $table->string('name');
                $table->integer('project_id')->unsigned()->index();
                $table->foreign('project_id')
                    ->references('id')
                    ->on(config('core.acl.projects_table'))
                    ->onDelete('cascade');
                $table->text('project_version')->nullable();
                $table->date('planned_start_date')->nullable();
                $table->date('planned_end_date')->nullable();
                $table->datetime('task_start_date')->nullable();
                $table->datetime('task_end_date')->nullable();
                $table->string('estimated_hours')->nullable();
                $table->string('actual_hours')->nullable();
                $table->tinyInteger('status')
                    ->default(1)
                    ->comment('1=Open,2=In Progress,3=On Hold,4=Waiting For Some one,5=Cancel,6=Completed');
                $table->integer('priority')
                    ->comment('1=Urgent,2=Very High,3=High,4=Medium,5=Low');
                $table->integer('progress')->default(0);
                $table->integer('created_by');
                $table->tinyInteger('assign_to')->nullable();
                $table->mediumText('description')->nullable();
                $table->text('notes')->nullable();
                $table->integer('order')->nullable();
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
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.task_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
