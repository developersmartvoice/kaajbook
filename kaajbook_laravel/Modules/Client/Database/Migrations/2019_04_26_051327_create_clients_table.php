<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateClientsTable
 *
 * The Migrations is Defined for Clients.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Clients
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.clients_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.clients_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('company_name')->nullable();
                $table->string('company_email')->nullable();
                $table->integer('user_id')
                    ->unsigned()
                    ->nullable(false);
                $table->foreign('user_id')
                    ->references('id')
                    ->on(config('core.acl.users_table'))
                    ->onDelete('cascade');
                $table->string('company_phone', 20)->nullable();
                $table->string('company_mobile', 20)->nullable();
                $table->string('company_zipcode', 10)->nullable();
                $table->string('company_city', 50)->nullable();
                $table->string('company_country', 50)->nullable();
                $table->string('company_fax')->nullable();
                $table->text('company_address')->nullable();
                $table->string('website')->nullable();
                $table->string('skype_id')->nullable();
                $table->string('facebook')->nullable();
                $table->string('twitter')->nullable();
                $table->string('linkedin')->nullable();
                $table->string('hosting_company')->nullable();
                $table->string('host_name')->nullable();
                $table->string('host_username', 100)->nullable();
                $table->string('host_password')->nullable();
                $table->string('host_port', 10)->nullable();
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
        Schema::dropIfExists(config('core.acl.clients_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
