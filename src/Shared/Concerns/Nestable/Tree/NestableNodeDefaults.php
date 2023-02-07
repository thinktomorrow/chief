<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable;

/**
 * Model logic for handling nesting based on the parent_id construct.
 */
trait NestableNodeDefaults
{
    public function getId(): string
    {
        return $this->getNodeId();
    }

    public function getModel(): Nestable
    {
        return $this->getNodeEntry();
    }

    public function getLabel(): string
    {
        return $this->getModel()->getPageTitleForSelect($this->getModel());
    }

    public function getBreadCrumbLabelWithoutRoot(): string
    {
        return $this->getBreadcrumbLabel(true);
    }

    public function getBreadCrumbLabel(bool $withoutRoot = false): string
    {
        $label = $this->getLabel();

        if (! $this->isRootNode()) {
            $label = array_reduce(array_reverse($this->getAncestorNodes()->all()), function ($carry, NestedNode $node) use ($withoutRoot) {
                if ($node->isRootNode()) {
                    return $withoutRoot ? $carry : $node->getLabel() . ': ' . $carry;
                }

                return $node->getLabel() . ' > ' . $carry;
            }, $this->getLabel());
        }

        return $label;
    }
}
