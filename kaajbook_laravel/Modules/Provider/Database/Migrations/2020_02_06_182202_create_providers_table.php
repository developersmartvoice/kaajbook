<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.providers_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.providers_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('firstname', 50);
                $table->string('lastname', 50);
                $table->string('email');
                $table->string('color', 50);
                $table->tinyInteger('status')
                    ->nullable()
                    ->default(1)
                    ->comment('1=Active,0=Inactive');
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
        Schema::dropIfExists(config('core.acl.providers_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
