<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChiefUrls extends Migration
{
    public function up()
    {
        Schema::create('chief_urls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale')->nullable();
            $table->string('slug');
            $table->string('model_type');
            $table->integer('model_id')->unsigned();
            $table->timestamps();

            $table->unique(['locale', 'slug']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('chief_urls');
    }
}
