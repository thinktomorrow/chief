<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
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

    public function showOnline(): bool
    {
        if ($this->getNodeEntry() instanceof StatefulContract) {
            return $this->getNodeEntry()->inOnlineState();
        }

        return (bool)$this->getNodeEntry('show_online');
    }

//    public function getUrlSlug(?string $locale = null): ?string
//    {
//        $locale ?: app()->getLocale();
//
//        if (! $urlRecord = $this->getModel()->urls->first(fn ($urlRecord) => $urlRecord->locale == $locale)) {
//            return null;
//        }
//
//        return $urlRecord->slug;
//    }

    public function getLabel(): string
    {
        return $this->getModel()->getPageTitleForSelect($this->getModel());
    }

//    public function getChildren(): Collection
//    {
//        return \collect($this->getChildNodes()->all());
//    }
//
//    public function getOnlineChildren(): Collection
//    {
//        return $this->getChildren()
//            ->reject(fn (NestedNode $childNode) => ! $childNode->showOnline())
//        ;
//    }

//    public function getDirectChildrenIds(): array
//    {
//        return $this->getChildren()->map(fn(Nestable $child) => $child->getId())->toArray();
//    }
//
//    public function getAllChildrenIds(): array
//    {
//        return $this->pluckChildNodes('getId');
//    }

//    public function getBreadCrumbs(): array
//    {
//        return $this->getAncestorNodes()->all();
//    }

    public function getBreadCrumbLabelWithoutRoot(): string
    {
        return $this->getBreadcrumbLabel(true);
    }

    public function getBreadCrumbLabel(bool $withoutRoot = false): string
    {
        $label = $this->getLabel();

        if (!$this->isRootNode()) {
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
