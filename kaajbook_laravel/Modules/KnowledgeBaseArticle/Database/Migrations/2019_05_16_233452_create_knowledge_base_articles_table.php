<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateKnowledgeBaseArticlesTable
 *
 * The Migrations is Defined for Knowledgebase Articles.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\KnowledgeBaseArticle
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateKnowledgeBaseArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists(config('core.acl.knowledge_base_article_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Schema::create(
            config('core.acl.knowledge_base_article_table'),
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('category_id')->unsigned()->index();
                $table->foreign('category_id')
                    ->references('id')
                    ->on(config('core.acl.knowledge_base_category_table'))
                    ->onDelete('cascade');
                $table->integer('user_id')
                    ->unsigned()
                    ->nullable(false);
                $table->foreign('user_id')
                    ->references('id')
                    ->on(config('core.acl.users_table'))
                    ->onDelete('cascade');
                $table->string('article_title');
                $table->text('description');
                $table->string('file_name')->nullable();
                $table->softDeletes();
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
        Schema::dropIfExists(config('core.acl.knowledge_base_article_table'));
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
