<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMorphKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->renameColumn('collection', 'morph_key');
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->renameColumn('collection', 'morph_key');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->string('morph_key', 255)->change();
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->string('morph_key', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagesets');
    }
}
