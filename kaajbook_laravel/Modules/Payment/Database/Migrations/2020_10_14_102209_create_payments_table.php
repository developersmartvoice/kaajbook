<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePaymentsTable
 *
 * The Migrations is Defined for Payment.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Payment
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.payments_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.payments_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id')->references('id')->on(config('core.acl.users_table'));
                $table->integer('client_id')->unsigned();
                $table->foreign('client_id')->references('id')->on(config('core.acl.users_table'));
                $table->string('receipt_number')->unique();
                $table->integer('invoice_id')->unsigned();
                $table->string('transaction_id')->nullable();
                $table->string('payment_method')->nullable();
                $table->dateTime('date');
                $table->double('amount', 8, 2)->default(0);
                $table->mediumText('notes')->nullable();
                $table->enum('status', ['in_progress', 'successful', 'failed'])->default('successful');
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.payments_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
