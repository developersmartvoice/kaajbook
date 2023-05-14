<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateNotificationsTable
 *
 * The Migrations is Defined for Notifications.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Notification
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2022 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.2@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.2
 */
class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('core.acl.notifications_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('route');
            $table->integer('route_related_id')->nullable();
            $table->string('title');
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('core.acl.notifications_table'));
    }
}
