<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Queries;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Context\ContextDto;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Fragment\SharedFragmentDto;
use Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems\TabItem;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\UI\Livewire\MenuDto;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasAllowedSites;

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
    public function getContextsByOwner(ContextOwner|ModelReference $modelReference): Collection
    {
        $owner = $modelReference instanceof ContextOwner ? $modelReference : $modelReference->instance();

        $collection = $this->contextRepository->getByOwner($owner->modelReference())
            ->map(function ($context) use ($owner) {
                $ownerResource = $this->registry->findResourceByModel($owner::class);

                return ContextDto::fromContext(
                    $context,
                    $owner->modelReference(),
                    $ownerResource->getPageTitle($owner),
                    $this->registry->findManagerByModel($owner::class)->route('edit', $owner),
                );
            });

        $modelLocales = $owner instanceof HasAllowedSites ? $owner->getAllowedSites() : ChiefSites::locales();

        $this->setUnassignedActiveSitesToPrimaryContext($collection, $modelLocales);

        return $collection;

    }

    public function getMenu(string $type, string $menuId): MenuDto
    {
        return $this->getMenus($type)->first(fn ($menu) => $menu->getId() === $menuId);
    }

    public function getMenus(string $type): Collection
    {
        $collection = Menu::where('type', $type)->get()
            ->map(fn ($menu) => MenuDto::fromModel($menu));

        $this->setUnassignedActiveSitesToPrimaryContext($collection, ChiefSites::locales());

        return $collection;
    }

    private function setUnassignedActiveSitesToPrimaryContext(Collection $items, array $availableSites): void
    {
        $activeSites = $availableSites;

        $items->each(function (TabItem $item) use (&$activeSites) {
            $activeSites = array_diff($activeSites, $item->getActiveSites());
        });

        $items->first()?->addActiveSites($activeSites);
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
    public function getSharedFragmentDtos(string $fragmentId, ContextOwner&ReferableModel $owner): Collection
    {
        // TODO: optimize this: allow array of fragmentIds, get all results for given fragmentIds, memoize generic results and filter by fragmentId

        return $this->contextRepository->getContextsByFragment($fragmentId)
            ->map(function ($context) use ($fragmentId, $owner) {

                $contextOwner = ($context->owner_type == $owner->modelReference()->shortClassName() && $context->owner_id == $owner->modelReference()->id())
                    ? $owner
                    : $context->owner;

                $ownerResource = $this->registry->findResourceByModel($contextOwner::class);

                return SharedFragmentDto::fromContext(
                    $fragmentId,
                    $context,
                    $contextOwner->modelReference(),
                    $ownerResource->getPageTitle($contextOwner),
                    $this->registry->findManagerByModel($contextOwner::class)->route('edit', $contextOwner),
                );
            });
    }
}
