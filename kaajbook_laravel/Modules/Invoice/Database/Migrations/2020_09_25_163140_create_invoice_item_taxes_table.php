<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateInvoiceItemTaxesTable
 *
 * The Migrations is Defined for Invoice Item Tax.
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
class CreateInvoiceItemTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.invoice_item_taxes_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.invoice_item_taxes_table'), function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('invoice_item_id')->unsigned();
                $table->foreign('invoice_item_id')->references('id')->on(config('core.acl.invoice_items_table'))->onDelete('cascade');
                $table->integer('tax_id')->unsigned();
                $table->foreign('tax_id')->references('id')->on(config('core.acl.taxes_table'))->onDelete('cascade');
                $table->double('item_tax', 13, 2)->default(0);
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
        Schema::dropIfExists(config('core.acl.invoice_item_taxes_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
