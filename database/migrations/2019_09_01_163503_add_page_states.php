<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPageStates extends Migration
{
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('state')->after('morph_key')->default(\Thinktomorrow\Chief\States\PageState::DRAFT);
        });
    }

    public function down()
    {
        //
    }
}
