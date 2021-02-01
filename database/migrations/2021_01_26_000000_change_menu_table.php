<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMenuTable extends Migration
{
    public function up()
    {
        Schema::table('menu_items', function(Blueprint $table){
            $table->json('values')->nullable();
            $table->string('owner_type')->nullable();
        });

        Schema::table('menu_items', function(Blueprint $table){
            $table->renameColumn('page_id', 'owner_id');
        });
    }
}
