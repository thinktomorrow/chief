<?php

namespace Thinktomorrow\Chief\Fragments\Controllers;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Repositories\ContextOwnerRepository;

class SelectNewFragmentController
{
    private ContextOwnerRepository $contextOwnerRepository;

    public function __construct(ContextOwnerRepository $contextOwnerRepository)
    {
        $this->contextOwnerRepository = $contextOwnerRepository;
    }

    public function show(string $contextId, Request $request)
    {
        $context = ContextModel::find($contextId);
        $owner = $this->contextOwnerRepository->findOwner($contextId);

        return view('chief-fragments::components.fragment-select-new', [
            'fragments' => $this->getAllowedFragments($owner),
            'context' => $context,
            'order' => $request->input('order', 0),
        ]);
    }

    private function getAllowedFragments(ContextOwner $owner): array
    {
        return collect($owner->allowedFragments())->map(function ($fragmentClass) {
            return app($fragmentClass);
        })->groupBy(function (Fragment $fragment) {
            return $fragment->getCategory();
        })->sortDesc()->all();
    }
}
