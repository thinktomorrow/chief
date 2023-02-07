<?php

namespace Thinktomorrow\Chief\ManagedModels\Repository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

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
        $reflection = (new \ReflectionClass($modelClass));
        $eagerLoading = [];

        if ($reflection->implementsInterface(Visitable::class)) {
            $eagerLoading[] = 'urls';
        }

        if ($reflection->implementsInterface(HasAsset::class)) {
            $eagerLoading[] = 'assetRelation';
            $eagerLoading[] = 'assetRelation.media';
        }

        return $modelClass::with($eagerLoading)
            ->orderBy('order')
            ->get();
    }

    public function getPaginatedResults(): LengthAwarePaginator
    {
        return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);
    }
}
