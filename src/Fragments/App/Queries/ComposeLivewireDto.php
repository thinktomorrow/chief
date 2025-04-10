<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Queries;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\UI\Livewire\ContextDto;
use Thinktomorrow\Chief\Fragments\UI\Livewire\SharedFragmentDto;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasSiteLocales;

class ComposeLivewireDto
{
    private ContextRepository $contextRepository;

    private Registry $registry;

    public function __construct(ContextRepository $contextRepository, Registry $registry)
    {
        $this->contextRepository = $contextRepository;
        $this->registry = $registry;
    }

    public function getContext(ModelReference $modelReference, string $contextId): ContextDto
    {
        // We fetch from the entire context stack so our site references are correct
        return $this->getContextsByOwner($modelReference)
            ->first(fn ($context) => $context->id === $contextId);
    }

    /** @return Collection<ContextDto> */
    public function getContextsByOwner(ModelReference $modelReference): Collection
    {
        $collection = $this->contextRepository->getByOwner($modelReference)
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

        $model = $modelReference->instance();
        $modelLocales = $model instanceof HasSiteLocales ? $model->getSiteLocales() : ChiefSites::locales();

        $this->setUnassignedActiveSitesToPrimaryContext($collection, $modelLocales);

        return $collection;

    }

    private function setUnassignedActiveSitesToPrimaryContext(Collection $contexts, array $availableSites): void
    {
        $activeSites = $availableSites;

        $contexts->each(function (ContextDto $context) use (&$activeSites) {
            $activeSites = array_diff($activeSites, $context->activeSites);
        });

        $contexts->first()->addActiveSites($activeSites);
    }

    public function composeEmptyContext(ModelReference $modelReference): ContextDto
    {
        $owner = $modelReference->instance();
        $ownerResource = $this->registry->findResourceByModel($owner::class);

        return ContextDto::empty(
            $owner->modelReference(),
            $ownerResource->getPageTitle($owner),
            $this->registry->findManagerByModel($owner::class)->route('edit', $owner),
        );
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
