<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relations', function (Blueprint $table){
            $table->string("parent_type");
            $table->unsignedBigInteger("parent_id");
            $table->string("child_type");
            $table->unsignedBigInteger("child_id");

            $table->tinyInteger('sort')->default(0);

            $table->index(["parent_type", "parent_id"], 'parent_index');
            $table->index(["child_type", "child_id"], 'child_index');

            $table->primary(["parent_type", "parent_id", "child_type", "child_id"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relations');
    }
}
