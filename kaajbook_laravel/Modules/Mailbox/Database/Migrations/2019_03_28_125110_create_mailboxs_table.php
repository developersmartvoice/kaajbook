<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
/**
 * Class CreateMailboxsTable
 *
 * The Migrations is Defined for Create Mailboxs.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Mailboxs
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateMailboxsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.email_inbox_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.email_inbox_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id')
                    ->references('id')
                    ->on(config('core.acl.users_table'))
                    ->onDelete('cascade');
                $table->string('to');
                $table->string('from');
                $table->string('subject');
                $table->mediumText('message_body')->nullable();
                $table->string('type')
                    ->default('inbox')
                    ->comment('Inbox, Sent, Draft, Trash');
                $table->string('view_status')
                    ->default(2)
                    ->comment('1=Read 2=Unread');
                $table->string('favourites')
                    ->default(0)
                    ->comment('0=No, 1=Yes');
                $table->string('notify_me')
                    ->default(1)
                    ->comment('1=On, 0=Off');
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
        Schema::dropIfExists(config('core.acl.email_inbox_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
