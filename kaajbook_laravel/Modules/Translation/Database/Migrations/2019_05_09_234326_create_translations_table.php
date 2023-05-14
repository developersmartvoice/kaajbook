<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.translations_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.translations_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('name');
                $table->string('language');
                $table->string('icon')->nullable();
                $table->tinyInteger('status')
                    ->default(1)
                    ->comment('1=Active, 0=Inactive');
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
        Schema::dropIfExists(config('core.acl.translations_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
