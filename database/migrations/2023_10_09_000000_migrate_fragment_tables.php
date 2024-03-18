<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('contexts', function (Blueprint $table) {
            $table->json('locales')->after('owner_id')->nullable();
        });

        // DO MIGRATION
        // TODO: migration expand to different contexts per locale
        DB::table('contexts')->update(['locales' => json_encode(config('chief.locales'))]);

        Schema::table('chief_urls', function (Blueprint $table) {
            $table->foreign('context_id')->nullable();
        });

        Schema::table('context_fragments', function (Blueprint $table) {
            $table->char('id', 36)->change();
        });

        Schema::table('context_fragments', function (Blueprint $table) {
            $table->renameColumn('model_reference', 'key');
        });

        DB::table('context_fragments')->get()->each(function ($row) {
            DB::table('context_fragments')->where('id', $row->id)->update(
                ['key' => substr($row->key, 0, strpos($row->key, '@'))]
            );
        });
    }

    public function down()
    {
    }
};
