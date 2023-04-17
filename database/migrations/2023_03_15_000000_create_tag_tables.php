<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chief_taggroups', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->unsignedTinyInteger('order')->default(0);
            $table->json('data')->nullable();
        });

        Schema::create('chief_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taggroup_id')->nullable();
            $table->string('label');
            $table->string('color')->nullable();
            $table->unsignedTinyInteger('order')->default(0);
            $table->json('data')->nullable();

            $table->foreign('taggroup_id')->references('id')->on('chief_taggroups')->nullOnDelete();
        });

        Schema::create('chief_tags_pivot', function (Blueprint $table) {
            $table->unsignedBigInteger('tag_id');
            $table->string('owner_type');
            $table->string('owner_id'); // Account for uuid????
            $table->unsignedTinyInteger('order')->default(0);

            $table->foreign('tag_id')->references('id')->on('chief_tags')->onDelete('cascade');
            $table->primary(['tag_id','owner_type', 'owner_id']);
        });
    }

    public function down()
    {
    }
};
