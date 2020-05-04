<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDynamicValues extends Migration
{
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->json('values')->nullable()->after('current_state');
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->json('values')->nullable()->after('slug');
        });
    }

    public function down()
    {
        //
    }
}
