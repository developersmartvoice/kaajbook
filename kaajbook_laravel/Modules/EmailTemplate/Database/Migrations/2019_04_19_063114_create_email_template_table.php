<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateEmailTemplateTable
 *
 * The Migrations is Defined for Email Template.
 *
 * PHP version 7.1.3
 *
 * @category  Administration
 * @package   Modules\EmailTemplate
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateEmailTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.email_template_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.email_template_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('email_group_id')->unsigned()->nullable();
                $table->foreign('email_group_id')
                    ->references('id')
                    ->on(config('core.acl.email_group_table'))
                    ->onDelete('set null');
                $table->string('template_name');
                $table->string('template_subject')->nullable();
                $table->text('template_body')->nullable();
                $table->string('type');
                $table->softDeletes();
                $table->timestamps();
                $table->unique(['template_name', 'deleted_at']);
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
        Schema::dropIfExists(config('core.acl.email_template_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
