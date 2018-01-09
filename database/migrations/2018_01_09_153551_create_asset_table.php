<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        Schema::create('asset_pivots', function (Blueprint $table) {
            $table->integer('asset_id');
            $table->integer('entity_id');
            $table->string('entity_type');
            $table->string('locale')->nullable();
            $table->string('type')->nullable();
            $table->integer('order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_pivots');
        Schema::dropIfExists('assets');
    }
}
