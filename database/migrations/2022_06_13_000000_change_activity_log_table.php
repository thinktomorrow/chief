<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table(config('activitylog.table_name'), function (Blueprint $table) {
            $table->string('event')->nullable()->after('subject_type');
        });

        Schema::table(config('activitylog.table_name'), function (Blueprint $table) {
            $table->uuid('batch_uuid')->nullable()->after('properties');
        });
    }

    public function down() {}
};
