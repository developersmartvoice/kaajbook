<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMenuTable
 *
 * The Migrations is Defined for Menu.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Menu
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.menu_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.menu_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('parent_menu_id');
                $table->string('module')->nullable();
                $table->string('label');
                $table->string('text');
                $table->string('link');
                $table->string('icon');
                $table->integer('order');
                $table->tinyInteger('subscription')
                    ->default(1)
                    ->comment('1=Subscription, 0=Unsubscription');
                $table->tinyInteger('status')
                    ->default(1)
                    ->comment('1=Active, 0=Inactive');
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
        Schema::dropIfExists(config('core.acl.menu_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
