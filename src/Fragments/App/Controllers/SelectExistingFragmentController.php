<?php

namespace Thinktomorrow\Chief\Fragments\App\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions;
use Thinktomorrow\Chief\Fragments\App\Queries\GetShareableFragments;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextModel;
use Thinktomorrow\Chief\Managers\Register\Registry;

class SelectExistingFragmentController
{
    private Registry $registry;
    private GetShareableFragments $getShareableFragments;

    public function __construct(GetShareableFragments $getShareableFragments, Registry $registry)
    {
        $this->registry = $registry;
        $this->getShareableFragments = $getShareableFragments;
    }

    public function show(string $contextId, Request $request)
    {
        $context = ContextModel::find($contextId);
        $owner = $context->getOwner();
        $shareableFragments = $this->getShareableFragments($contextId, $request);
        $order = $request->input('order', 0);

        // Select filter by owner
        $ownerOptions = [];

        if (public_method_exists($owner, 'getRelatedOwners')) {
            foreach ($owner->getRelatedOwners() as $relatedOwner) {
                $ownerOptions[$relatedOwner->modelReference()->get()] = $this->registry->findResourceByModel($relatedOwner::class)->getPageTitle($relatedOwner);
            }
        }

        // Select filter by fragment class
        $typeOptions = [];
        foreach ($owner->allowedFragments() as $allowedFragmentClass) {
            $typeOptions[$allowedFragmentClass] = app($allowedFragmentClass)->getLabel();
        }

        return view('chief-fragments::components.fragment-select-existing', [
            'sharedFragments' => $shareableFragments,
            'context' => $context,
            'owner' => $owner,
            'ownerManager' => $this->registry->findManagerByModel($owner::class),
            'order' => $order,
            'existingTypesOptions' => PairOptions::toMultiSelectPairs($typeOptions),
            'existingOwnersOptions' => PairOptions::toMultiSelectPairs($ownerOptions),
        ]);

    }

    private function getShareableFragments(string $contextId, Request $request): Collection
    {
        return $this->getShareableFragments
            ->filterByTypes($request->input('types', []))
            ->filterByOwners($request->input('owners', []))
            ->excludeAlreadySelected()
            ->get($contextId);
    }
}
