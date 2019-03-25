<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeoKeywordsOnPageTranslations extends Migration
{
    public function up()
    {
        Schema::table('page_translations', function (Blueprint $table) {
            $table->string('seo_keywords')->after('seo_description')->nullable();
        });
    }

    public function down()
    {
    }
}
