<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFragmentsTable extends Migration
{
    public function up()
    {
        Schema::create('fragments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->json('values')->nullable();

            $table->string('owner_type');
            $table->unsignedBigInteger('owner_id');
            $table->unsignedSmallInteger('order')->default(0);

            $table->index(['owner_type', 'owner_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagesets');
    }
}
