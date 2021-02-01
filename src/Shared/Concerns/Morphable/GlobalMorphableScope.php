<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Morphable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Thinktomorrow\Chief\Modules\Module;
use Illuminate\Database\Eloquent\Builder;
use Thinktomorrow\Chief\Legacy\Pages\Page;

class GlobalMorphableScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        try {

            /**
             * Generic Page and Module class should be ignored and not count as a valid morph type.
             * When querying from these base classes we should ignore the morph scopes.
             */
            if (in_array($model->morphKey(), [Page::class, Module::class])) {
                return;
            }

            $builder->where('morph_key', '=', $model->morphKey());
        } /**
         * If query is performed on a model that has no morph key,
         * it is fine to ignore the morph scope altogether.
         */
        catch (NotFoundMorphKey $e) {
        }
    }
}
