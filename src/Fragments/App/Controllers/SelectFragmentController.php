<?php

namespace Thinktomorrow\Chief\Fragments\App\Controllers;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentRepository;
use Thinktomorrow\Chief\Managers\Register\Registry;

class SelectFragmentController
{
    private FragmentRepository $fragmentRepository;
    private Registry $registry;

    public function __construct(FragmentRepository $fragmentRepository, Registry $registry)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->registry = $registry;
    }

    public function new(string $contextId, Request $request)
    {
        $context = ContextModel::find($contextId);
        $owner = $context->getOwner();

        return view('chief-fragments::components.fragment-select-new', [
            'fragments' => $this->getAllowedFragments($owner),
            'context' => $context,
            'owner' => $owner,
            'order' => $request->input('order', 0),
        ]);
    }

    public function existing(string $contextId, Request $request)
    {
        $context = ContextModel::find($contextId);
        $owner = $context->getOwner();
        $shareableFragments = $this->getShareableFragments($owner, $request);
        $order = $request->input('order', 0);

        // Select filter by owner
        $existingOwnersOptions = [];
        if (public_method_exists($owner, 'getRelatedOwners')) {
            foreach ($owner->getRelatedOwners() as $relatedOwner) {
                $existingOwnersOptions[$relatedOwner->modelReference()->get()] = $this->registry->findResourceByModel($relatedOwner::class)->getPageTitle($relatedOwner);
            }
        }

        // Select filter by fragment class
        $existingTypesOptions = [];
        foreach ($owner->allowedFragments() as $allowedFragmentClass) {
            $existingTypesOptions[$allowedFragmentClass] = app($allowedFragmentClass)->getLabel();
        }

        return view('chief-fragments::components.fragment-select-existing', [
            'sharedFragments' => $shareableFragments,
            'context' => $context,
            'owner' => $owner,
            'ownerManager' => $this->registry->findManagerByModel($owner::class),
            'order' => $order,
            'existingTypesOptions' => PairOptions::toMultiSelectPairs($existingTypesOptions),
            'existingOwnersOptions' => PairOptions::toMultiSelectPairs($existingOwnersOptions),
        ]);

    }

    private function getAllowedFragments(FragmentsOwner $owner): array
    {
        return collect($owner->allowedFragments())->map(function ($fragmentClass) {
            return app($fragmentClass);
        })->groupBy(function (Fragmentable $fragment) {
            return $fragment->getCategory();
        })->sortDesc()->all();
    }

    private function getShareableFragments(FragmentsOwner $owner, Request $request): array
    {
        return $this->fragmentRepository->getShareableFragments($owner, [
            'exclude_own' => true,
            'default_top_shared' => true,
            'owners' => array_filter($request->input('owners', []), fn ($val) => $val),
            'types' => array_filter($request->input('types', []), fn ($val) => $val),
        ])->all();
    }
}
