<?php
declare(strict_types = 1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree;

use Thinktomorrow\Vine\DefaultNode;

final class NestableNode extends DefaultNode implements NestedNode
{
    use NestableNodeDefaults;

    public function getParentNodeId(): ?string
    {
        return $this->getModel()->parent_id;
    }
}
