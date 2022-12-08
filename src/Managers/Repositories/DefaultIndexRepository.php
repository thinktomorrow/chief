<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;
use Thinktomorrow\Chief\Shared\Concerns\ProvidesQuery;

class DefaultIndexRepository implements IndexRepository
{
    private Builder $builder;

    public function __construct(Builder $builder)
    {
        // Builder
        // Defaults
        // with[]
        $this->builder = $builder;
    }

    public function adjustQuery(iterable $adjusters, array $parameterBag): static
    {
        foreach ($adjusters as $adjuster) {
            if ($adjuster instanceof ProvidesQuery) {
                $adjuster->query($this->builder, $parameterBag);
            } elseif (is_callable($adjuster)) {
                call_user_func_array($adjuster, [$this->builder, $parameterBag]);
            } else {
                throw new \InvalidArgumentException('An adjuster should be callable or implement the ProvidesQuery interface. Please check the passed adjusters array.');
            }
        }

        return $this;
    }

    public function getTree(): NestedTree
    {
        // TODO: Implement getTree() method.
    }

    public function getRows(?int $perPage = null): LengthAwarePaginator
    {
        return $this->builder->paginate($perPage);
    }
}
