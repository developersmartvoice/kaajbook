<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
/**
 * Class CreateDepartmentRoleMenuTable
 *
 * The Migrations is Defined for User Department Role Menu.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\User
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateDepartmentRoleMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.department_role_menu_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        
        Schema::create(
            config('core.acl.department_role_menu_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('department_id')->unsigned()->index();
                $table->foreign('department_id')
                    ->references('id')
                    ->on(config('core.acl.departments_table'))
                    ->onDelete('cascade');
                $table->integer('role_id')->unsigned()->index();
                $table->foreign('role_id')
                    ->references('id')
                    ->on(config('core.acl.roles_table'))
                    ->onDelete('cascade');
                $table->integer('menu_id')->unsigned()->index();
                $table->foreign('menu_id')
                    ->references('id')
                    ->on(config('core.acl.menu_table'))
                    ->onDelete('cascade');
                $table->integer('view');
                $table->integer('created');
                $table->integer('edited');
                $table->integer('deleted');
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
        Schema::dropIfExists(config('core.acl.department_role_menu_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
