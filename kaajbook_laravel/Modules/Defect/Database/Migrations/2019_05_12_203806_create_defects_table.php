<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateDefectsTable
 *
 * The Migrations is Defined for Defect.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Defect
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateDefectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.defects_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.defects_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('create_user_id');
                $table->string('generated_id');
                $table->integer('project_id');
                $table->string('project_version')->nullable();
                $table->tinyInteger('defect_type')
                    ->default(1)
                    ->comment('1=Defects,2=Enhancement');
                $table->string('defect_name');
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->string('estimated_hours')->nullable();
                $table->string('actual_hours')->nullable();
                $table->mediumText('description')->nullable();
                $table->tinyInteger('status')
                    ->default(1)
                    ->comment('1=Assigned,2=Closed,3=In Progress,4=Open,5=Solved,6=Re-open,7=Deferred');
                $table->integer('assigned_group_id')->nullable();
                $table->string('assign_member')->nullable();
                $table->tinyInteger('severity')
                    ->default(1)
                    ->comment('1=Low,2=Medium,3=High,4=Very High,5=Urgent');
                $table->text('notes')->nullable();
                $table->string('attachment')->nullable();
                $table->string('attachment_hash')->nullable();
                $table->integer('orders')->nullable();
                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists(config('core.acl.defects_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
