<?php

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Model;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;

interface Nestable
{
    public function getParent(): ?Nestable;

    /**
     * List of the access path to this nestable model.
     * This array should contain all parents.
     *
     *
     * @return Nestable[]
     */
    public function getAncestors(): array;

    /**
     * @return Nestable[]
     */
    public function getChildren(): iterable;

    /**
     * Nested array of the complete child structure
     * belonging to this parent model.
     *
     */
    public function getDescendants(): NestedTree;

    /**
     * List of all the descendant ids.
     */
    public function getDescendantIds(): array;

    /**
     * @return Nestable[]
     */
    public function getSiblings(): iterable;
}
