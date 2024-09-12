<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\AssetLibrary\HasAsset;
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

    public function getTreeModelsByIds(array $ids): Collection
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

        //        if ($reflection->implementsInterface(HasAsset::class)) {
        //            $eagerLoading[] = 'assetRelation';
        //            $eagerLoading[] = 'assetRelation.media';
        //        }

        return $modelClass::withoutGlobalScopes()
            ->with($eagerLoading)
            ->orderBy('order')
            ->whereIn('id', $ids)
            ->get();
    }
}
