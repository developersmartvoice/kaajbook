<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentRecurrenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('core.acl.appointment_recurrence_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('appointment_id')->unsigned();
            $table->foreign('appointment_id')
                ->references('id')
                ->on(config('core.acl.appointment_table'))
                ->onDelete('cascade');
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->tinyInteger('status')
                ->nullable()
                ->default(1)
                ->comment('1=Reserved, 2=Confirmed, 3=Finished, 4=Canceled');
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
        Schema::dropIfExists(config('core.acl.appointment_recurrence_table'));
    }
}
