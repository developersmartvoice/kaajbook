<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateDefectAttachmentsTable
 *
 * The Migrations is Defined for Defect Attachment.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\DefectAttachment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateDefectAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.defect_attachments_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.defect_attachments_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('defect_id')->unsigned()->index();
                $table->foreign('defect_id')
                    ->references('id')
                    ->on(config('core.acl.defects_table'))
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
        Schema::dropIfExists(config('core.acl.defect_attachments_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
