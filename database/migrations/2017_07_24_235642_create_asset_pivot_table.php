<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_pivots', function (Blueprint $table){
           $table->integer('asset_id');
           $table->integer('entity_id');
           $table->string('entity_type');
           $table->string('type')->nullable();
        });

        Schema::table('assets', function (Blueprint $table){
           $table->dropColumn('model_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
