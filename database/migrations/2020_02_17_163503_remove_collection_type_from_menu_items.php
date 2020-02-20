<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCollectionTypeFromMenuItems extends Migration
{
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('collection_type');
        });

        if (App::environment() != 'testing') {
            DB::statement("ALTER TABLE menu_items MODIFY COLUMN 'type' ENUM('internal', 'custom', 'nolink') NOT NULL");
        }
    }

    public function down()
    {
        //
    }
}
