<?php

namespace Thinktomorrow\Chief\ManagedModels\Repository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestableNode;

class EloquentIndexRepository implements IndexRepository
{
    private Registry $registry;
    private string $resourceKey;

    public function __construct(Registry $registry, string $resourceKey)
    {
        $this->registry = $registry;
        $this->resourceKey = $resourceKey;
    }

    public function getResults(): Collection
    {
        // TODO: this is the CrudAssistant replacement in time...
        return collect([]);
    }

    public function getNestableResults(): Collection
    {
        $modelClass = $this->registry->resource($this->resourceKey)::modelClassName();

        return $modelClass::with(['urls', 'assetRelation', 'assetRelation.media'])
//            ->orderBy('order')
            ->get();
    }

    public function getPaginatedResults(): LengthAwarePaginator
    {
        return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);
    }
}
