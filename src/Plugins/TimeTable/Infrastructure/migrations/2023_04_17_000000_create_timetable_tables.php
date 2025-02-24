<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->unsignedTinyInteger('order')->default(0);
            $table->json('data')->nullable();
        });

        Schema::create('timetable_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('timetable_id')->nullable();
            $table->unsignedTinyInteger('weekday'); // iso8601 WeekDay
            $table->json('slots')->nullable();
            $table->json('data')->nullable();

            $table->foreign('timetable_id')->references('id')->on('timetables')->nullOnDelete();
        });

        Schema::create('timetable_dates', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->json('slots')->nullable();
            $table->json('data')->nullable();
        });

        Schema::create('timetable_date_pivot', function (Blueprint $table) {
            $table->unsignedBigInteger('timetable_id');
            $table->unsignedBigInteger('timetable_date_id');
            $table->unsignedTinyInteger('order')->default(0);

            $table->foreign('timetable_id')->references('id')->on('timetables')->onDelete('cascade');
            $table->foreign('timetable_date_id')->references('id')->on('timetable_dates')->onDelete('cascade');
            $table->primary(['timetable_id', 'timetable_date_id']);
        });
    }

    public function down()
    {
    }
};
