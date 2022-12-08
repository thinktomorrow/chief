<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns;

use Illuminate\Database\Eloquent\Builder;

interface ProvidesQuery
{
    public function query(Builder $builder, array $parameterBag): void;
}
