<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Common\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SortPeriodDateScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->orderBy('start_at');
    }
}
