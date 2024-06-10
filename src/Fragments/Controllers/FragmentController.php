<?php

namespace Thinktomorrow\Chief\Fragments\Controllers;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fragments\App\Actions\ReorderFragments;
use Thinktomorrow\Chief\Fragments\Events\FragmentsReordered;
use Thinktomorrow\Chief\Fragments\UI\Components\Fragments;

class FragmentController
{
    private ReorderFragments $reorderFragments;

    public function __construct(ReorderFragments $reorderFragments)
    {
        $this->reorderFragments = $reorderFragments;
    }

    public function reorder($contextId, Request $request)
    {
        /**
         * Sortable.js contains dummy indices such as 5wj, cfv and such. Here we make sure
         * that these values are excluded. Since a fragment id consist of at least 4 digits,
         * We can safely assume that an index with less than four characters is considered an invalid fragment id.
         */
        $indices = array_filter($request->input('indices', []), fn ($index) => strlen((string)$index) > 3);

        $this->reorderFragments->handle($contextId, $indices);

        event(new FragmentsReordered($contextId));

        return response()->json([
            'message' => 'models sorted for context ' . $contextId,
        ]);
    }

    // Refresh fragments index after sidebar edit
    public function refreshIndex($contextId)
    {
        return (new Fragments($contextId))->render()->render();
    }
}
