<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contexts', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('owner_type');
            $table->char('owner_id', 36); // account for integer ids as well as uuids
            $table->timestamps();
        });

        Schema::create('context_fragments', function(Blueprint $table){
            $table->unsignedBigInteger('id');
            $table->string('model_reference');
            $table->json('data')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
        });

        Schema::create('context_fragment_lookup', function (Blueprint $table) {
            $table->unsignedBigInteger('context_id');
            $table->unsignedBigInteger('fragment_id');
            $table->unsignedSmallInteger('order')->default(0);
            $table->foreign('context_id')->references('id')->on('contexts')->onDelete('cascade');
            $table->foreign('fragment_id')->references('id')->on('context_fragments')->onDelete('cascade');

            $table->primary(['context_id', 'fragment_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('context_fragment_lookup');
        Schema::dropIfExists('context_fragments');
        Schema::dropIfExists('contexts');
    }
};
