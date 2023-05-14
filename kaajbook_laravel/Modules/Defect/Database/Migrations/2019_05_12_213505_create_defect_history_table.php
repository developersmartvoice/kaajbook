<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateDefectHistoryTable
 *
 * The Migrations is Defined for Defect History.
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
class CreateDefectHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.defects_history_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.defects_history_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('defect_id')->unsigned();
                $table->foreign('defect_id')
                    ->references('id')
                    ->on(config('core.acl.defects_table'))
                    ->onDelete('cascade');
                $table->text('description');
                $table->integer('created_by_id');
                $table->integer('commented_by_id')->nullable();
                $table->integer('solved_by_id')->nullable();
                $table->integer('closed_by_id')->nullable();
                $table->integer('updated_by_id')->nullable();
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
        Schema::dropIfExists(config('core.acl.defects_history_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
