<?php

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\Events\FragmentsReordered;
use Thinktomorrow\Chief\Shared\Helpers\SortModels;

class ReorderFragments
{
    private SortModels $sortModels;

    public function __construct(SortModels $sortModels)
    {
        $this->sortModels = $sortModels;
    }

    public function handle(string $contextId, array $indices): void
    {
        if(count($indices) < 1) {
            return;
        }

        $this->sortModels->handle('context_fragment_lookup', $indices, 'order', 'fragment_id', false, 'context_id = "' . $contextId . '"');

        event(new FragmentsReordered($contextId));
    }
}
