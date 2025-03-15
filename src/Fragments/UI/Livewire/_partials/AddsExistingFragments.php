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

        if (count($this->filters) > 0) {
            foreach ($this->filters as $filter => $value) {
                $builder->{'filterBy'.ucfirst($filter)}($value);
            }
        }

        return $builder->get($this->contextId);
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
        $values = $this->getAllowedFragments()->flatten()->mapWithKeys(function ($allowedFragment) {
            return [$allowedFragment::class => $allowedFragment->getLabel()];
        })->toArray();

        return PairOptions::toMultiSelectPairs($values);
    }

    // $context = ContextModel::find($contextId);
    // $owner = $context->getOwner();
    // $shareableFragments = $this->getShareableFragments($contextId, $request);
    // $order = $request->input('order', 0);
    //
    //    // Select filter by owner
    // $ownerOptions = [];
    //
    // if (public_method_exists($owner, 'getRelatedOwners')) {
    // foreach ($owner->getRelatedOwners() as $relatedOwner) {
    // $ownerOptions[$relatedOwner->modelReference()->get()] = $this->registry->findResourceByModel($relatedOwner::class)->getPageTitle($relatedOwner);
    // }
    // }
    //
    // // Select filter by fragment class
    // $typeOptions = [];
    // foreach ($owner->allowedFragments() as $allowedFragmentClass) {
    //    $typeOptions[$allowedFragmentClass] = app($allowedFragmentClass)->getLabel();
    // }
    //
    // return view('chief-fragments::components.fragment-select-existing', [
    //    'sharedFragments' => $shareableFragments,
    //    'context' => $context,
    //    'owner' => $owner,
    //    'ownerManager' => $this->registry->findManagerByModel($owner::class),
    //    'order' => $order,
    //    'existingTypesOptions' => PairOptions::toMultiSelectPairs($typeOptions),
    //    'existingOwnersOptions' => PairOptions::toMultiSelectPairs($ownerOptions),
    // ]);
}
