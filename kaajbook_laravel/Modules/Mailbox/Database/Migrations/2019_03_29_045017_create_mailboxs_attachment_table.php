<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
/**
 * Class CreateMailboxsAttachmentTable
 *
 * The Migrations is Defined for MailboxsAttachment.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\MailboxsAttachment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateMailboxsAttachmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.inbox_attachment_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.inbox_attachment_table'),
            function (
                Blueprint $table
            ) {
                $table->increments('id')->unsigned();
                $table->integer('mailbox_id')
                    ->unsigned()
                    ->nullable();
                $table->foreign('mailbox_id')
                    ->references('id')
                    ->on(config('core.acl.email_inbox_table'))
                    ->onDelete('set null');
                $table->string('file_name');
                $table->string('file_extension');
                $table->bigInteger('file_size');
                $table->string('file_hash');
                $table->string('mime_type');
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
        Schema::dropIfExists(config('core.acl.inbox_attachment_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
