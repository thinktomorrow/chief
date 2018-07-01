<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Common\Collections;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class GlobalCollectionScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('collection', '=', $model->collectionKey());
    }
}
