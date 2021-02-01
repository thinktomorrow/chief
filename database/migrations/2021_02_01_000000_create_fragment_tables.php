<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFragmentTables extends Migration
{
    public function up()
    {
        Schema::create('context', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('owner_type');
            $table->unsignedBigInteger('owner_id');
            $table->timestamps();
        });

        Schema::create('context_fragments', function(Blueprint $table){
            $table->char('id', 36)->primary();
            $table->unsignedBigInteger('context_id');
            $table->unsignedSmallInteger('order')->default(0);
            $table->string('model_reference');
            $table->json('data')->nullable();
            $table->timestamps();

            $table->foreign('context_id')->references('id')->on('context')->onDelete('cascade');
        });
    }
}
