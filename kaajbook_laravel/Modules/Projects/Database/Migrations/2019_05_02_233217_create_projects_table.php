<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateProjectsTable
 *
 * The Migrations is Defined for Project.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Projects
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.projects_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.projects_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('user_id');
                $table->string('generated_id');
                $table->string('project_name');
                $table->string('project_version');
                $table->integer('client_id')->nullable();
                $table->integer('progress')->default(0);
                $table->boolean('project_hours')
                    ->default(0)
                    ->comment('0=Progress Bar,1=Task Hours');
                $table->date('start_date');
                $table->date('end_date');
                $table->tinyInteger('billing_type')
                    ->nullable()
                    ->comment('1=Fixed Rate,2=Hourly Rate');
                $table->double('price_rate', 15, 8)->nullable();
                $table->string('estimated_hours')->nullable();
                $table->string('actual_hours')->nullable();
                $table->tinyInteger('status')
                    ->nullable()
                    ->default()
                    ->comment('1=Open,2=InProgress,3=OnHold,4=Cancel,5=Completed');
                $table->string('demo_url')->nullable();
                $table->mediumText('description')->nullable();
                $table->string('project_logo')->nullable();
                $table->tinyInteger('assign_to')->nullable();
                $table->string('assign_members');
                $table->text('notes')->nullable();
                $table->boolean('is_overdue')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->unique(['project_name', 'deleted_at']);
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
        Schema::dropIfExists(config('core.acl.projects_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
