<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMeetingsTable
 *
 * The Migrations is Defined for Meetings.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Meetings
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.meetings'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.meetings'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('generated_id');
                $table->integer('organizer_id')->unsigned();
                $table->foreign('organizer_id')
                    ->references('id')
                    ->on(config('core.acl.users_table'))
                    ->onDelete('cascade');
                $table->integer('project_id')->unsigned()->nullable();
                $table->integer('client_id')->nullable();
                $table->string('title');
                $table->mediumText('description')->nullable();
                $table->string('location')->nullable();
                $table->datetime('start_date');
                $table->datetime('end_date');
                $table->string('hour_time', 10)->nullable();
                $table->tinyInteger('status')
                    ->default(1)
                    ->comment('1=Open,2=In Progress,3=Cancel,4=Completed');
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
        Schema::dropIfExists(config('core.acl.meetings'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
