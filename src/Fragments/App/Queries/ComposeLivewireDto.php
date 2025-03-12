<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Queries;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\UI\Livewire\ContextDto;
use Thinktomorrow\Chief\Fragments\UI\Livewire\SharedFragmentDto;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class ComposeLivewireDto
{
    private ContextRepository $contextRepository;

    private Registry $registry;

    public function __construct(ContextRepository $contextRepository, Registry $registry)
    {
        $this->contextRepository = $contextRepository;
        $this->registry = $registry;
    }

    public function getContext(string $contextId): ContextDto
    {
        $context = $this->contextRepository->find($contextId);
        $owner = $context->owner;
        $ownerResource = $this->registry->findResourceByModel($owner::class);

        return ContextDto::fromContext(
            $context,
            $owner->modelReference(),
            $ownerResource->getPageTitle($owner),
            $this->registry->findManagerByModel($owner::class)->route('edit', $owner),
        );
    }

    /** @return Collection<ContextDto> */
    public function getContextsByOwner(ModelReference $modelReference): Collection
    {
        return $this->contextRepository->getByOwner($modelReference)
            ->map(function ($context) {

                $owner = $context->owner;
                $ownerResource = $this->registry->findResourceByModel($owner::class);

                return ContextDto::fromContext(
                    $context,
                    $owner->modelReference(),
                    $ownerResource->getPageTitle($owner),
                    $this->registry->findManagerByModel($owner::class)->route('edit', $owner),
                );
            });
    }

    /** @return Collection<SharedFragmentDto> */
    public function getSharedFragmentDtos(string $fragmentId): Collection
    {
        // TODO: optimize this: allow array of fragmentIds, get all results for given fragmentIds, memoize generic results and filter by fragmentId

        return $this->contextRepository->getContextsByFragment($fragmentId)
            ->map(function ($context) use ($fragmentId) {

                $owner = $context->owner;
                $ownerResource = $this->registry->findResourceByModel($owner::class);

                return SharedFragmentDto::fromContext(
                    $fragmentId,
                    $context,
                    $owner->modelReference(),
                    $ownerResource->getPageTitle($owner),
                    $this->registry->findManagerByModel($owner::class)->route('edit', $owner),
                );
            });
    }
}
