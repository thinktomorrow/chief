<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Repositories;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class ContextOwnerRepository
{
    private ContextRepository $contextRepository;

    public function __construct(ContextRepository $contextRepository)
    {
        $this->contextRepository = $contextRepository;
    }

    /**
     * @return Collection ContextOwner[]
     */
    public function getOwnersByFragment(string $fragmentId): Collection
    {
        $models = $this->contextRepository->getContextsByFragment($fragmentId);

        return $models
            ->map(fn ($model) => $this->ownerFactory($model->owner_type, $model->owner_id))
            ->unique();
    }

    public function findOwner(string $contextId): ContextOwner
    {
        $context = ContextModel::findOrFail($contextId);

        return $this->ownerFactory($context->owner_type, $context->owner_id);
    }

    private function ownerFactory(string $key, $id): ContextOwner
    {
        $key = Relation::getMorphedModel($key) ?? $key;

        return ModelReference::make($key, $id)->instance();
    }
}
