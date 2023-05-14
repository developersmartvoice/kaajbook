<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateInvoiceSettingTable
 *
 * The Migrations is Defined for Invoice Settings.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Invoice
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class CreateInvoiceSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.invoice_settings_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.invoice_settings_table'), function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('invoice_prefix')->default('INV-');
                $table->integer('due_after')->default(30);
                $table->string('invoice_logo')->nullable();
                $table->string('template')->default('invoice-1');
                $table->string('gst_number')->nullable();
                $table->boolean('show_gst_number')->default(0);
                $table->mediumText('client_note')->nullable();
                $table->mediumText('terms_conditions')->nullable();
                $table->boolean('auto_remind')->default(0);
                $table->string('auto_remind_after')->default('1,3')->nullable();
                $table->string('payment_prefix')->default('PAY-');
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.invoice_settings_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
