<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTable extends Migration
{
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->enum('type', ['collection', 'internal', 'custom', 'nolink'])->default('custom');
            $table->string('collection_type')->nullable();
            $table->string('menu_type')->default('main');
            $table->boolean('hidden_in_menu')->default(false);
            $table->unsignedInteger('page_id')->nullable();
            $table->integer('order')->default(0);
        });

        Schema::create('menu_item_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_item_id')->unsigned();
            $table->string('locale');
            $table->string('label')->nullable();
            $table->string('url')->nullable();

            $table->foreign('menu_item_id')->references('id')->on('menu_items')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invitations');
    }
}
