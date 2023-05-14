<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateLeavesTable
 *
 * The Migrations is Defined for Leave.
 *
 * PHP version 7.1.3
 *
 * @category  HRM
 * @package   Modules\Leave
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.leaves_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.leaves_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id')
                    ->references('id')
                    ->on(config('core.acl.users_table'))
                    ->onDelete('cascade');
                $table->integer('leave_type_id')->unsigned();
                $table->foreign('leave_type_id')
                    ->references('id')
                    ->on(config('core.acl.leave_types_table'))
                    ->onDelete('cascade');
                $table->string('duration')->comment('full, half');
                $table->string('duration_type')->nullable()->comment('first_half, second_half');
                $table->double('leave_day', 5, 2)->default(1)->comment('1 or 0.5');
                $table->date('leave_date');
                $table->text('reason')->nullable();
                $table->text('reject_reason')->nullable();
                $table->tinyInteger('status')
                    ->default(1)
                    ->comment('1=Submitted, 2=Approved, 3=Rejected, 4=Cancel, 5=Taken');
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.leaves_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
