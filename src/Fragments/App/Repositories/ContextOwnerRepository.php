<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Repositories;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;

class ContextOwnerRepository
{
    private ContextRepository $contextRepository;

    public function __construct(ContextRepository $contextRepository)
    {
        $this->contextRepository = $contextRepository;
    }

    /**
     * @return Collection<ContextOwner>
     */
    public function getOwnersByFragment(string $fragmentId): Collection
    {
        $models = $this->contextRepository->getContextsByFragment($fragmentId);

        return $models
            ->map(fn ($model) => $model->owner)
            ->unique();
    }

    /**
     * Get all owners of contexts in the system.
     *
     * @return Collection<ContextOwner>
     */
    public function getAllOwners(): Collection
    {
        return ContextModel::with('owner')
            ->get()
            ->map(fn ($model) => $model->owner)
            ->reject(fn ($owner) => ! $owner instanceof ContextOwner)  // Don't allow Fragments
            ->unique(fn ($owner) => $owner->modelReference()->get())
            ->values();
    }

    public function findOwner(string $contextId): ContextOwner
    {
        $context = ContextModel::findOrFail($contextId);

        return $context->owner;
    }
}
