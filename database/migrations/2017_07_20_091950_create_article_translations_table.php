<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('article_translations', function(Blueprint $table) {
            $table->increments('id');
            $table->string('locale');
            $table->string('title');
            $table->text('content');
            $table->text('short');
            $table->text('image');
            $table->string('slug')->unique();
            $table->text('meta_description');
            $table->timestamps();
            $table->integer('article_id')->unsigned();

            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('article_translations');
    }
}
