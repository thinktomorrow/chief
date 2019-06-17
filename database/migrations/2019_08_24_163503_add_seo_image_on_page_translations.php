<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeoImageOnPageTranslations extends Migration
{
    public function up()
    {
        Schema::table('page_translations', function (Blueprint $table) {
            $table->string('seo_image')->after('seo_keywords')->nullable();
        });
    }

    public function down()
    {
    }
}
