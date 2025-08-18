<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Plugins\Tags\App\Taggable\Taggable;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestableTree;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

trait TreeResourceDefault
{
    public function getTreeModelIds(): array
    {
        $modelClass = static::modelClassName();
        $modelObject = new $modelClass;

        return DB::table($modelObject->getTable())
            ->orderBy('order')
            ->select([$modelObject->getKeyName(), 'parent_id'])
            ->get()
            ->all();
    }

    public function getTreeModels(?array $ids = null, array $eagerLoading = ['urls', 'tags']): Collection
    {
        $modelClass = static::modelClassName();
        $reflection = (new \ReflectionClass($modelClass));
        $modelObject = new $modelClass;

        foreach ($eagerLoading as $relation) {
            if ($relation == 'urls' && ! $reflection->implementsInterface(Visitable::class)) {
                unset($eagerLoading[array_search('urls', $eagerLoading)]);
            }

            if ($relation == 'tags' && ! $reflection->implementsInterface(Taggable::class)) {
                unset($eagerLoading[array_search('tags', $eagerLoading)]);
            }
        }

        $models = $modelClass::withoutGlobalScopes()
            ->with($eagerLoading)
            ->orderBy('order')
            ->when($ids, fn ($query) => $query->whereIn($modelObject->getKeyName(), $ids))
            ->get();

        // Sort by parent
        return collect(NestableTree::fromIterable($models)->sort('order')->flatten());
    }
}
