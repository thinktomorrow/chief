<?php

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;

class NestableQueries
{
    private NestableRepository $nestablePageRepository;

    /**
     * The depth up until we want to fetch ancestors / descendants
     */
    private int $depth = 10;

    public function __construct(NestableRepository $nestablePageRepository)
    {
        $this->nestablePageRepository = $nestablePageRepository;
    }

    public function buildNestedTree(Collection $models): NestedTree
    {
        return $this->nestablePageRepository->buildTree($models);
    }

    public function getAncestorIds(Model $model): array
    {
        $table = $model->getTable();

        $query = $model::query()
            ->from(DB::raw($table))
            ->where($table.'.id', $model->getKey());

        for($i = 1; $i < $this->depth+1; $i++) {
            $prevTableName = $i == 1 ? $table : $table . '_'.$i;
            $nextTableName = $table . '_'.($i + 1);

            if($i > 1) {
                $query->addSelect(DB::raw($prevTableName.'.id AS parent_id_' . $i));
            }

            $query->leftJoin(DB::raw($table . ' ' . $nextTableName), $prevTableName.'.parent_id', '=', $nextTableName.'.id');
        }

        return collect($query->get()->first())
            ->reject(fn($item) => !$item)
            ->values()
            ->toArray();
    }

    public function getDescendantIds(Model $model): array
    {
        $table = $model->getTable();

        $query = DB::table($table)
            ->where($table.'.parent_id', $model->getKey());

        for($i = 1; $i < $this->depth+1; $i++) {
            $prevTableName = $i == 1 ? $table : $table . '_'.$i;
            $nextTableName = $table . '_'.($i + 1);

            $query->addSelect(DB::raw('GROUP_CONCAT('.$prevTableName.'.id) AS id_' . $i));
            $query->leftJoin(DB::raw($table . ' ' . $nextTableName), $prevTableName.'.id', '=', $nextTableName.'.parent_id');
        }

        return collect($query->get()[0])
            ->map(fn($row) => explode(',', $row))
            ->flatten()
            ->reject(fn($item) => !$item)
            ->unique()
            ->values()
            ->toArray();
    }
}
