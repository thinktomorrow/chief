<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Plugins\Tags\App\Taggable\Taggable;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

trait TreeResourceDefault
{
    public function getTreeModelIds(): array
    {
        $modelClass = static::modelClassName();

        return DB::table((new $modelClass)->getTable())
            ->orderBy('order')
            ->select(['id', 'parent_id'])
            ->get()
            ->all();
    }

    public function getTreeModels(?array $ids = null): Collection
    {
        $modelClass = static::modelClassName();
        $reflection = (new \ReflectionClass($modelClass));
        $eagerLoading = [];

        if ($reflection->implementsInterface(Visitable::class)) {
            $eagerLoading[] = 'urls';
        }

        if ($reflection->implementsInterface(Taggable::class)) {
            $eagerLoading[] = 'tags';
        }

        return $modelClass::withoutGlobalScopes()
            ->with($eagerLoading)
            ->orderBy('order')
            ->when($ids, fn ($query) => $query->whereIn('id', $ids))
            ->get();
    }
}
