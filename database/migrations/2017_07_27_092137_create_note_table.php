<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('published')->default(0);
            $table->enum('type', ['general', 'delivery', 'payment']);
            $table->boolean('cookies');
            $table->enum('level', ['info', 'warning', 'error']);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
        });

        Schema::create('note_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('note_id')->unsigned();
            $table->string('locale');
            $table->string('content');

            $table->unique(['note_id', 'locale']);
            $table->foreign('note_id')->references('id')->on('notes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('note_translations');
        Schema::dropIfExists('notes');
    }
}
