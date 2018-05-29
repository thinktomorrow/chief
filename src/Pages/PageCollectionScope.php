<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Pages;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PageCollectionScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('collection', '=', $model->collection);
    }
}