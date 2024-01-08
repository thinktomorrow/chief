<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SortModels
{

    public function handle(string $table, array $indices, string $column = 'order', string $indexColumn = 'id', bool $castIdToIntegers = true, string $extraWhere = null): void
    {
        static::batchUpdateColumn($table, $column, $indices, $indexColumn, $castIdToIntegers, $extraWhere);
    }

    public function handleByModel(Model $model, array $indices, string $column = 'order', string $indexColumn = 'id', bool $castIdToIntegers = true): void
    {
        $table = $model->getTable();

        static::batchUpdateColumn($table, $column, $indices, $indexColumn, $castIdToIntegers);
    }

    /**
     * Taken from: https://github.com/laravel/ideas/issues/575
     *
     * @return void
     */
    private static function batchUpdateColumn(string $table, string $column, array $indices, string $indexColumn = 'id', bool $castIdToIntegers = true, string $extraWhere = null): void
    {
        $cases = [];
        $ids = [];
        $params = [];

        foreach ($indices as $index => $modelId) {
            $id = $castIdToIntegers ? (int)$modelId : $modelId;
            $ids[] = "'{$id}'";

            $cases[] = "WHEN '{$id}' then ?";
            $params[] = $index;
        }

        $ids = implode(',', $ids);
        $cases = implode(' ', $cases);

        if ($extraWhere) {
            $extraWhere = ' AND ' . DB::raw($extraWhere)->getValue(DB::connection()->getQueryGrammar());
        }

        DB::update("UPDATE `{$table}` SET `{$column}` = CASE `" . $indexColumn . "` {$cases} END WHERE `" . $indexColumn . "` in ({$ids})" . $extraWhere, $params);
    }
}
