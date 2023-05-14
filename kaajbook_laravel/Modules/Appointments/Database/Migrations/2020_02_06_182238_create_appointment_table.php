<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.appointment_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.appointment_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('generated_id');
                $table->integer('user_id')
                    ->unsigned()
                    ->index()
                    ->comment('Used for requester');
                $table->foreign('user_id')
                    ->references('id')
                    ->on(config('core.acl.users_table'))
                    ->onDelete('cascade');
                $table->string('title')->nullable();
                $table->integer('client_id')->nullable();
                $table->integer('provider_id')
                    ->unsigned()
                    ->index();
                $table->foreign('provider_id')
                    ->references('id')
                    ->on(config('core.acl.providers_table'))
                    ->onDelete('cascade');
                $table->string('attendees')->nullable();
                $table->dateTime('start_date_time');
                $table->dateTime('end_date_time');
                $table->string('location')->nullable();
                $table->mediumText('note')->nullable();
                $table->boolean('recurrence')->default(0);
                $table->integer('recurrence_occurrences')->nullable();
                $table->string('recurrence_pattern')->nullable();
                $table->tinyInteger('status')
                    ->nullable()
                    ->default(1)
                    ->comment('1=Reserved,2=Confirmed,3=Finished,4=Canceled');
                $table->integer('send_confirmation')->default(0);
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
        Schema::dropIfExists(config('core.acl.appointment_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
