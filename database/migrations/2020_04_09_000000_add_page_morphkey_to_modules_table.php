<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPageMorphkeyToModulesTable extends Migration
{
    public function up()
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->string('page_morph_key')->default('singles');

            if (App::environment() != 'testing') {
                $table->dropForeign('modules_page_id_foreign');
            }

            $table->index(["page_morph_key", "page_id"], 'page_index');
        });
    }

    public function down()
    {
        //
    }
}
