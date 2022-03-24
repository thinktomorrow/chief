<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUrlStatus extends Migration
{
    public function up()
    {
        Schema::table('chief_urls', function (Blueprint $table) {
            $table->string('status', 20)->default(\Thinktomorrow\Chief\Site\Urls\UrlStatus::online->value);
            $table->string('internal_label')->nullable();
        });

        // events: pagestate change
            // publish,
            // archive
        // deletion...

    }
}
