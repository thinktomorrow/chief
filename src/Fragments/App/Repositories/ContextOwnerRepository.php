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
     * @return Collection ContextOwner[]
     */
    public function getOwnersByFragment(string $fragmentId): Collection
    {
        $models = $this->contextRepository->getContextsByFragment($fragmentId);

        return $models
            ->map(fn ($model) => $model->owner)
            ->unique();
    }

    public function findOwner(string $contextId): ContextOwner
    {
        $context = ContextModel::findOrFail($contextId);

        return $context->owner;
    }
}
