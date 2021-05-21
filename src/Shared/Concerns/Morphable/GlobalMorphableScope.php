<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Morphable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class GlobalMorphableScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        try {
            $builder->where('morph_key', '=', $model->morphKey());
        } /**
         * If query is performed on a model that has no morph key,
         * it is fine to ignore the morph scope altogether.
         */
        catch (NotFoundMorphKey $e) {

        }
    }
}
