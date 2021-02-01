<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Application;

use Illuminate\Support\Facades\DB;

class SortModels
{
    public function handle(string $modelClass, array $indices, string $column = 'order'): void
    {
        $table = (new $modelClass())->getTable();

        static::batchUpdateColumn($table, $column, $indices);
    }

    /** Taken from: https://github.com/laravel/ideas/issues/575 */
    private static function batchUpdateColumn(string $table, string $column, array $indices)
    {
        $cases = [];
        $ids = [];
        $params = [];

        foreach ($indices as $index => $modelId) {
            $id = (int) $modelId;
            $cases[] = "WHEN {$id} then ?";
            $params[] = $index;
            $ids[] = $id;
        }

        $ids = implode(',', $ids);
        $cases = implode(' ', $cases);

        DB::update("UPDATE `{$table}` SET `{$column}` = CASE `id` {$cases} END WHERE `id` in ({$ids})", $params);
    }
}
