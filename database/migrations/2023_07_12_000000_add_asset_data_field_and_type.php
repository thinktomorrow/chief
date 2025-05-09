<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('assets', 'data')) {
            return;
        }

        Schema::table('assets', function (Blueprint $table) {
            $table->string('asset_type')->nullable();
            $table->json('data')->nullable();
        });

        DB::table('assets')->update(['asset_type' => 'default']);

        Schema::table('assets', function (Blueprint $table) {
            $table->string('asset_type')->nullable(false)->change(); // Remove nullable
        });
    }

    public function down() {}
};
