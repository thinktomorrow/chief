<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Queries;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\UI\Livewire\SharedFragmentDto;
use Thinktomorrow\Chief\Managers\Register\Registry;

class GetOwners
{
    private ContextRepository $contextRepository;

    private Registry $registry;

    public function __construct(ContextRepository $contextRepository, Registry $registry)
    {
        $this->contextRepository = $contextRepository;
        $this->registry = $registry;
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

    /**
     * Get the count of the different owners. This count does not reflect
     * the amount of contexts since each owner can own multiple contexts.
     */
    public function getCount(string $fragmentId): int
    {
        return $this->contextRepository->getContextsByFragment($fragmentId)
            ->groupBy(fn ($row) => $row->owner_type.'_'.$row->owner_id)
            ->count();
    }
}
