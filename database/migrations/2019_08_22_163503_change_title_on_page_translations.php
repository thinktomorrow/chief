<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTitleOnPageTranslations extends Migration
{
    public function up()
    {
        Schema::table('page_translations', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
        });
    }

    public function down()
    {
    }
}
