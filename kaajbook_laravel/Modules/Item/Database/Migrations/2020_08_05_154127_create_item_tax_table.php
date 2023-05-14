<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateItemTaxTable
 *
 * The Migrations is Defined for Item Tax.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Item
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class CreateItemTaxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.item_tax_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.item_tax_table'), function (Blueprint $table) {
                $table->integer('item_id')->unsigned()->index();
                $table->foreign('item_id')
                    ->references('id')
                    ->on(config('core.acl.items_table'))
                    ->onDelete('cascade');
                $table->integer('tax_id')->unsigned()->index();
                $table->foreign('tax_id')
                    ->references('id')
                    ->on(config('core.acl.taxes_table'))
                    ->onDelete('cascade');
                $table->primary(['tax_id', 'item_id']);
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
        Schema::dropIfExists(config('core.acl.item_tax_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
