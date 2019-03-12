<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesetsTable extends Migration
{
    public function up()
    {
        Schema::create('pagesets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->unique();
            $table->string('action');
            $table->string('parameters');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagesets');
    }
}
