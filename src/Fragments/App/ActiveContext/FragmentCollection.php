<?php

namespace Thinktomorrow\Chief\Fragments\App\ActiveContext;

use Thinktomorrow\Vine\Node;
use Thinktomorrow\Vine\NodeCollection;

class FragmentCollection extends NodeCollection
{
    public function toFragments(): array
    {
        $rootNodes = $this->all();
dd($rootNodes);
        // TODO: convert all nodes to their entry.
        // TODO: make sure all children nodeCollection are a FragmentCollection so we can iterate over this call.

        return array_map(fn (Node $node) => $node->getNodeEntry(), $rootNodes);
    }
}
