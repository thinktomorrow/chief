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

        DB::table('contexts')->update(['locales' => json_encode(config('chief.locales'))]);

        // DO MIGRATION
        // TODO: migration expand to different contexts per locale
        // TODO: migrate active context ids to each url db record.
        Schema::table('chief_urls', function (Blueprint $table) {
            $table->unsignedBigInteger('context_id')->nullable()->after('id');
            $table->foreign('context_id')->references('id')->on('contexts')->nullOnDelete();
        });

        Schema::table('context_fragment_lookup', function (Blueprint $table) {
            $table->dropForeign('context_fragment_lookup_fragment_id_foreign');
        });

        Schema::table('context_fragment_lookup', function (Blueprint $table) {
            $table->char('fragment_id', 36)->change();
        });

        Schema::table('context_fragments', function (Blueprint $table) {
            $table->char('id', 36)->change();
        });

        Schema::table('context_fragment_lookup', function (Blueprint $table) {
            $table->foreign('fragment_id')->references('id')->on('context_fragments')->onDelete('cascade');
        });

        Schema::table('context_fragments', function (Blueprint $table) {
            $table->renameColumn('model_reference', 'key');
        });

        Schema::table('context_fragments', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
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
