<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        if(Schema::hasColumn('assets','data')) return;

        Schema::table('assets', function (Blueprint $table) {
            $table->string('asset_type')->nullable();
            $table->json('data')->nullable();
        });

        \Illuminate\Support\Facades\DB::table('assets')->update(['asset_type' => 'local']);

        Schema::table('assets', function (Blueprint $table) {
            $table->string('asset_type')->nullable(false)->change(); // Remove nullable
        });
    }

    public function down()
    {
    }
};
