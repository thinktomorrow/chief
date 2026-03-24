<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const ASSETS_PIVOT_INDEX = 'assets_pivot_entity_lookup_index';

    private const CHIEF_URLS_INDEX = 'chief_urls_redirect_model_lookup_index';

    public function up()
    {
        if (Schema::hasTable('assets_pivot') && ! $this->indexExists('assets_pivot', self::ASSETS_PIVOT_INDEX)) {
            Schema::table('assets_pivot', function (Blueprint $table) {
                $table->index(['entity_type', 'entity_id', 'order', 'asset_id'], self::ASSETS_PIVOT_INDEX);
            });
        }

        if (Schema::hasTable('chief_urls') && ! $this->indexExists('chief_urls', self::CHIEF_URLS_INDEX)) {
            Schema::table('chief_urls', function (Blueprint $table) {
                $table->index(['redirect_id', 'model_type', 'model_id'], self::CHIEF_URLS_INDEX);
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('assets_pivot') && $this->indexExists('assets_pivot', self::ASSETS_PIVOT_INDEX)) {
            Schema::table('assets_pivot', function (Blueprint $table) {
                $table->dropIndex(self::ASSETS_PIVOT_INDEX);
            });
        }

        if (Schema::hasTable('chief_urls') && $this->indexExists('chief_urls', self::CHIEF_URLS_INDEX)) {
            Schema::table('chief_urls', function (Blueprint $table) {
                $table->dropIndex(self::CHIEF_URLS_INDEX);
            });
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        if (DB::getDriverName() !== 'mysql') {
            return false;
        }

        return DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $table)
            ->where('index_name', $index)
            ->exists();
    }
};
