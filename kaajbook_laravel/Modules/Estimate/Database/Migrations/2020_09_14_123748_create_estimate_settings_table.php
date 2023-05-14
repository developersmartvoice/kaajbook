<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateEstimateSettingsTable
 *
 * The Migrations is Defined for Estimate Settings.
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
class CreateEstimateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.estimate_settings_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.estimate_settings_table'), function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('estimate_prefix')->default('EST-');
                $table->integer('due_after')->default(7);
                $table->string('estimate_logo')->nullable();
                $table->string('template')->default('estimate-1');
                $table->mediumText('client_note')->nullable();
                $table->mediumText('terms_conditions')->nullable();
                $table->boolean('auto_remind')->default(0);
                $table->integer('auto_remind_before')->default(2)->nullable();
                $table->boolean('auto_convert_to_invoice_on_client_accept')->default(1);
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
        Schema::dropIfExists(config('core.acl.estimate_settings_table'));
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
