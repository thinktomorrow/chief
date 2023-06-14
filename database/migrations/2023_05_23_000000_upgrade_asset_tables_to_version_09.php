<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        // Only want to run these migrations for existing asset database schemas.
        if(Schema::hasTable('assets_pivot')) return;

        Schema::rename('asset_pivots', 'assets_pivot');

        Schema::table('assets', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });

        Schema::table('assets_pivot', function (Blueprint $table) {
            $table->json('data')->nullable();
        });

        Schema::table('assets_pivot', function (Blueprint $table) {
            $table->unsignedBigInteger('asset_id')->change();
            $table->char('entity_id', 60)->change();
        });

        $assetIds = \Illuminate\Support\Facades\DB::table('assets')
            ->select('id')
            ->get()
            ->pluck('id')
            ->toArray();

        // Delete ghost pivots
        DB::table('assets_pivot')->whereNotIn('asset_id', $assetIds)->delete();

        Schema::table('assets_pivot', function (Blueprint $table) {
            $table->foreign('asset_id')
                ->references('id')
                ->on('assets')
                ->cascadeOnDelete();
        });

        \Illuminate\Support\Facades\DB::table('media')
            ->where('model_type', 'Thinktomorrow\\AssetLibrary\\Asset')
            ->update(['model_type' => 'asset']);
    }

    public function down()
    {
    }
};
