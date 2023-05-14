<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
/**
 * Class CreateUserRoleDepartmentTable
 *
 * The Migrations is Defined for User Role Department.
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
class CreateUserRoleDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.user_role_department'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.user_role_department'),
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
                $table->integer('user_id')->unsigned()->index();
                $table->foreign('user_id')
                    ->references('id')
                    ->on(config('core.acl.users_table'))
                    ->onDelete('cascade');
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
        Schema::dropIfExists(config('core.acl.user_role_department'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
