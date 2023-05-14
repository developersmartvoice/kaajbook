<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateLeaveAttachmentsTable
 *
 * The Migrations is Defined for Leave Attachment.
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
class CreateLeaveAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.leave_attachments_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.leave_attachments_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('leave_id')->unsigned()->index();
                $table->foreign('leave_id')
                    ->references('id')
                    ->on(config('core.acl.leaves_table'))
                    ->onDelete('cascade');
                $table->string('file_name');
                $table->string('file_hash');
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
        Schema::dropIfExists(config('core.acl.leave_attachments_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
