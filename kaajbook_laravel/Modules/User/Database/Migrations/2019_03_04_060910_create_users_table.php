<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
/**
 * Class CreateUsersTable
 *
 * The Migrations is Defined for Create Users Table.
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
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.users_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(config('core.acl.users_table'), function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('user_generated_id', 50);
            $table->string('firstname', 50);
            $table->string('lastname', 50);
            $table->string('username', 160);
            $table->string('email', 100);
            $table->string('password', 255);
            $table->boolean('is_super_admin')->default(0);
            $table->boolean('is_client')->default(0);
            $table->boolean('is_active')
                ->default(0)
                ->comment('1=Active, 0=Inactive');
            $table->enum('email_verified', [1, 0])
                ->default(0)
                ->comment('1=Verified, 0=Unverified');
            $table->string('email_verification_code', 60)->nullable();
            $table->boolean('online_status')
                ->default(0)
                ->comment('1=Online, 0=Offline');
            $table->string('avatar', 100)->nullable();
            $table->ipAddress('last_ip', 40)->nullable();
            $table->datetime('last_login')->nullable();
            $table->string('emp_id', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('country', 50)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('language', 40)
                ->default('en')
                ->comment('Used for app locale');
            $table->string('address', 64)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('skype', 100)->nullable();
            $table->date('joining_date')->nullable();
            $table->string('gender', 50)->nullable();
            $table->date('dob')->nullable();
            $table->string('maritial_status')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->text('permission')->nullable();
            $table->boolean('can_view_all_users')->default(0);
            $table->integer('primary_manager')->nullable();
            $table->integer('secondary_manager')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['username', 'deleted_at']);
            $table->unique(['email', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.users_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
