<?php

namespace Thinktomorrow\Chief\Fragments\App\Actions;

use Thinktomorrow\Chief\Fragments\Events\FragmentsReordered;
use Thinktomorrow\Chief\Shared\Concerns\Sortable\ReorderModels;

class ReorderFragments
{
    private ReorderModels $reorderModels;

    public function __construct(ReorderModels $reorderModels)
    {
        $this->reorderModels = $reorderModels;
    }

    public function handle(string $contextId, array $indices, ?string $parentId = null): void
    {
        if (count($indices) < 1) {
            return;
        }

        $where = 'context_id = "'.$contextId.'"';
        $where .= $parentId ? ' AND parent_id = "'.$parentId.'"' : '';

        $this->reorderModels->handle('context_fragment_tree', $indices, 'order', 'child_id', false, $where);

        event(new FragmentsReordered($contextId));
    }
}
