<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateProjectAttachmentsTable
 *
 * The Migrations is Defined for Project Attachment.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\ProjectAttachment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateProjectAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.project_attachments_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.project_attachments_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('project_id')->unsigned();
                $table->foreign('project_id')
                    ->references('id')
                    ->on(config('core.acl.projects_table'))
                    ->onDelete('cascade');
                $table->string('file_name');
                $table->string('file_hash');
                $table->string('file_extension');
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
        Schema::dropIfExists(config('core.acl.project_attachments_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
