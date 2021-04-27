<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Fragments\Database\ContextModel;

class SortModels
{
    public function handle(Model $model, array $indices, string $column = 'order'): void
    {
        $table = $model->getTable();

        static::batchUpdateColumn($table, $column, $indices);
    }

    public function handleFragments(Model $owner, array $indices): void
    {
        $contextId = ContextModel::ownedBy($owner)->id;

        static::batchUpdateColumn('context_fragment_lookup',            'order',            $indices,            'fragment_id',            false,            'context_id = "' . $contextId.'"');
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
            $id = $castIdToIntegers ? (int) $modelId : $modelId;
            $ids[] = "'{$id}'";

            $cases[] = "WHEN '{$id}' then ?";
            $params[] = $index;
        }

        $ids = implode(',', $ids);
        $cases = implode(' ', $cases);

        if ($extraWhere) {
            $extraWhere = ' AND ' . DB::raw($extraWhere);
        }

        DB::update("UPDATE `{$table}` SET `{$column}` = CASE `".$indexColumn."` {$cases} END WHERE `".$indexColumn."` in ({$ids})".$extraWhere, $params);
    }
}
