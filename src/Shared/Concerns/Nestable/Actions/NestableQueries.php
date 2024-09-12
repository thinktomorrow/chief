<?php

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestableTree;

class NestableQueries
{
    /**
     * The depth up until we want to fetch ancestors / descendants
     */
    private int $depth = 10;

    public function buildNestedTree(Collection $models): NestableTree
    {
        return NestableTree::fromIterable($models);
    }

    public function getAncestorIds(Model $model): array
    {
        if (! $model->exists) {
            return [];
        }

        $table = $model->getTable();

        $query = $model::query()
            ->from(DB::raw($table)->getValue(DB::connection()->getQueryGrammar()))
            ->where($table . '.id', $model->getKey());

        for ($i = 1; $i < $this->depth + 1; $i++) {
            $prevTableName = $i == 1 ? $table : $table . '_' . $i;
            $nextTableName = $table . '_' . ($i + 1);

            if ($i > 1) {
                $query->addSelect(DB::raw($prevTableName . '.id AS parent_id_' . $i));
            }

            $query->leftJoin(
                DB::raw($table . ' AS ' . $nextTableName),
                //                DB::raw($nextTableName)->getValue(DB::connection()->getQueryGrammar()),
                $prevTableName . '.parent_id',
                '=',
                $nextTableName . '.id'
            );
        }

        return collect($query->get()->first())
            ->reject(fn ($item) => ! $item)
            ->values()
            ->toArray();
    }

    public function getDescendantIds(Model $model): array
    {
        if (! $model->exists) {
            return [];
        }

        $table = $model->getTable();

        $query = DB::table($table)
            ->where($table . '.parent_id', $model->getKey());

        for ($i = 1; $i < $this->depth + 1; $i++) {
            $prevTableName = $i == 1 ? $table : $table . '_' . $i;
            $nextTableName = $table . '_' . ($i + 1);

            //            $query->addSelect(DB::raw('GROUP_CONCAT(' . $prevTableName . '.id) AS id_' . $i)->getValue(DB::connection()->getQueryGrammar()));
            $query->addSelect(DB::raw('GROUP_CONCAT(' . $prevTableName . '.id) AS id_' . $i));
            $query->leftJoin(
                DB::raw($table . ' AS ' . $nextTableName),
                //                DB::raw($nextTableName)->getValue(DB::connection()->getQueryGrammar()),
                $prevTableName . '.id',
                '=',
                $nextTableName . '.parent_id'
            );
        }

        return collect($query->get()[0])
            ->map(fn ($row) => explode(',', $row))
            ->flatten()
            ->reject(fn ($item) => ! $item)
            ->unique()
            ->values()
            ->toArray();
    }
}
