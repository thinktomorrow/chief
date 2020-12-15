<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderColumns extends Migration
{
    public function up()
    {
        if(Schema::hasColumn('pages', 'order')) {
            return;
        }

        Schema::table('pages', function (Blueprint $table) {
            $table->unsignedSmallInteger('order')->default(0);
        });
    }

    public function down()
    {
        //
    }
}
