<?php

namespace Thinktomorrow\Chief\Fragments\App\Controllers;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;

class SelectNewFragmentController
{
    public function show(string $contextId, Request $request)
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

    private function getAllowedFragments(FragmentsOwner $owner): array
    {
        return collect($owner->allowedFragments())->map(function ($fragmentClass) {
            return app($fragmentClass);
        })->groupBy(function (Fragment $fragment) {
            return $fragment->getCategory();
        })->sortDesc()->all();
    }
}