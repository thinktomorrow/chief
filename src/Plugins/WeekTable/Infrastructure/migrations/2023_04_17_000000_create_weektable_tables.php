<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('weektables', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedTinyInteger('order')->default(0);
            $table->json('data')->nullable();
        });

        Schema::create('weektable_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('weektable_id')->nullable();
            $table->unsignedTinyInteger('day');
            $table->json('slots')->nullable();
            $table->json('data')->nullable();

            $table->foreign('weektable_id')->references('id')->on('weektables')->nullOnDelete();
        });

        Schema::create('weektable_dates', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->json('slots')->nullable();
            $table->json('data')->nullable();
        });

        Schema::create('weektable_date_pivot', function (Blueprint $table) {
            $table->unsignedBigInteger('weektable_id');
            $table->unsignedBigInteger('weektable_date_id');
            $table->unsignedTinyInteger('order')->default(0);

            $table->foreign('weektable_id')->references('id')->on('weektables')->onDelete('cascade');
            $table->foreign('weektable_date_id')->references('id')->on('weektable_dates')->onDelete('cascade');
            $table->primary(['weektable_id','weektable_date_id']);
        });
    }

    public function down()
    {
    }
};
