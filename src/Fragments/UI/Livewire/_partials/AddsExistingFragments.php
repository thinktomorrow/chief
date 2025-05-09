<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\_partials;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions;
use Thinktomorrow\Chief\Fragments\App\Queries\GetShareableFragments;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextOwnerRepository;
use Thinktomorrow\Chief\Managers\Register\Registry;

trait AddsExistingFragments
{
    public array $filters = [];

    /** @return Collection<\Thinktomorrow\Chief\Fragments\Fragment> */
    public function getShareableFragments(): Collection
    {
        $builder = app(GetShareableFragments::class)
            ->excludeAlreadySelected();

        // Default is always filtered by allowed fragments
        if (! isset($this->filters['types'])) {
            $builder->filterByTypes($this->getAllowedFragments()->map(fn ($fragment) => $fragment::class)->toArray());
        }

        if (count($this->filters) > 0) {
            foreach ($this->filters as $filter => $value) {
                $builder->{'filterBy'.ucfirst($filter)}($value);
            }
        }

        return $builder->get($this->context->id);
    }

    public function updatedFilters(): void
    {
        // Remove falsy values like ['filters' => [null]]
        foreach ($this->filters as $key => $value) {
            if (! $value || (is_array($value) && count($value) === 1 && ! $value[0])) {
                unset($this->filters[$key]);
            }
        }
    }

    /**
     * If filters is used, we know that admin is interacting with the
     * existing tab, so let's make sure that LW remembers this state.
     */
    public function showExisting(): bool
    {
        return count($this->filters) > 0;
    }

    public function getOwnerFilterValues(): array
    {
        $values = [];
        $owners = app(ContextOwnerRepository::class)->getAllOwners();

        foreach ($owners as $owner) {
            $values[$owner->modelReference()->get()] = app(Registry::class)->findResourceByModel($owner::class)->getPageTitle($owner);
        }

        return PairOptions::toMultiSelectPairs($values);
    }

    public function getTypeFilterValues(): array
    {
        $values = $this->getAllowedFragments()->mapWithKeys(function ($allowedFragment) {
            return [$allowedFragment::class => $allowedFragment->getLabel()];
        })->toArray();

        return PairOptions::toMultiSelectPairs($values);
    }
}
