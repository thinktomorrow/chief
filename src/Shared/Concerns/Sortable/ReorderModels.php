<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Sortable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReorderModels
{
    public function handleByModel(Model&Sortable $model, array $indices): void
    {
        $table = $model->getTable();
        $column = $model->sortableAttribute();
        $indexColumn = $model->getKeyName();
        $castIdToIntegers = $model->getKeyType() === 'int';

        static::batchUpdateColumn($table, $column, $indices, $indexColumn, $castIdToIntegers);
    }

    public function moveToParent(Model&Sortable $model, $itemId, $parentId = null, $order = 0)
    {
        if (! $itemId) {
            throw new \InvalidArgumentException('Missing argument [itemId] for moveToParent request.');
        }

        if (! $instance = $model::find($itemId)) {
            $modelClass = $model::class;
            throw new \InvalidArgumentException("Cannot move item to parent: Model of type [{$modelClass}] with id [{$itemId}] not found. In a table, you can set the proper model class via `Table::setReorderingModelClass()`.");
        }

        $instance->update([
            'parent_id' => $parentId,
            $model->sortableAttribute() => $order,
        ]);
    }

    public function handle(string $table, array $indices, string $column = 'order', string $indexColumn = 'id', bool $castIdToIntegers = true, ?string $extraWhere = null): void
    {
        static::batchUpdateColumn($table, $column, $indices, $indexColumn, $castIdToIntegers, $extraWhere);
    }

    /**
     * Taken from: https://github.com/laravel/ideas/issues/575
     */
    private static function batchUpdateColumn(string $table, string $column, array $indices, string $indexColumn = 'id', bool $castIdToIntegers = true, ?string $extraWhere = null): void
    {
        if (count($indices) < 1) {
            return;
        }

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
            $extraWhere = ' AND '.DB::raw($extraWhere)->getValue(DB::connection()->getQueryGrammar());
        }

        DB::update("UPDATE `{$table}` SET `{$column}` = CASE `".$indexColumn."` {$cases} END WHERE `".$indexColumn."` in ({$ids})".$extraWhere, $params);
    }
}
