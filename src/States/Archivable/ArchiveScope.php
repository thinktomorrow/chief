<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\States\Archivable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Thinktomorrow\Chief\States\PageState;

class ArchiveScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('state','<>', PageState::ARCHIVED);
    }
}
