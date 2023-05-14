<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateEstimatesTable
 *
 * The Migrations is Defined for Estimate.
 *
 * PHP version 7.1.3
 *
 * @category  Sales
 * @package   Modules\Estimate
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2020 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @2.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 2.0
 */
class CreateEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.estimates_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.estimates_table'), function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id')->references('id')->on(config('core.acl.users_table'))->onDelete('cascade');
                $table->integer('client_id')->unsigned();
                $table->foreign('client_id')->references('id')->on(config('core.acl.users_table'))->onDelete('cascade');
                $table->integer('invoice_id')->nullable();
                $table->string('estimate_number')->unique();
                $table->date('estimate_date');
                $table->date('valid_till');
                $table->double('sub_total', 13, 2)->default(0);
                $table->double('total_tax', 13, 2)->default(0);
                $table->enum('discount_type', ['percent', 'fixed'])->nullable();
                $table->double('discount', 13, 2)->default(0);
                $table->double('total_discount', 13, 2)->default(0);
                $table->double('adjustment', 13, 2)->default(0);
                $table->double('total_amount', 13, 2)->default(0);
                $table->string('reference')->nullable();
                $table->mediumText('note')->nullable();
                $table->enum('status', ['draft', 'sent', 'expired', 'declined', 'accepted'])->default('draft');
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
        Schema::dropIfExists(config('core.acl.estimates_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
