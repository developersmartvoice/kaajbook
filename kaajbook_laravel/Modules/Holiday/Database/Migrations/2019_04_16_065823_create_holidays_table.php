<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
/**
 * Class CreateHolidaysTable
 *
 * The Migrations is Defined for Holidays.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\Holidays
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.holiday_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.holiday_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('event_name');
                $table->mediumText('description')->nullable();
                $table->date('date');
                $table->string('location')->nullable();
                $table->string('color')->default('#1ab394');
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
        Schema::dropIfExists(config('core.acl.holiday_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
