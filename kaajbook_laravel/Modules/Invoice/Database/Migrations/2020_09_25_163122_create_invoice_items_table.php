<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateInvoiceItemsTable
 *
 * The Migrations is Defined for Invoice Item.
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
class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.invoice_items_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.invoice_items_table'), function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('invoice_id')->unsigned();
                $table->foreign('invoice_id')->references('id')->on(config('core.acl.invoices_table'))->onDelete('cascade');
                $table->string('item_name');
                $table->mediumText('item_description')->nullable();
                $table->string('item_unit')->nullable();
                $table->double('unit_price', 13, 2);
                $table->integer('quantity');
                $table->double('total_item_amount', 13, 2)->default(0);
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
        Schema::dropIfExists(config('core.acl.invoice_items_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
