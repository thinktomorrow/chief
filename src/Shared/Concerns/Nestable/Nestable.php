<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable;

use Thinktomorrow\Vine\Node;

interface Nestable extends Node
{
    public function getNodeLabel(): string;

    public function getBreadCrumbLabel(bool $withoutRoot = false): string;

    public function getBreadCrumbLabelWithoutRoot(): string;

    public function getParent(): ?self;

    /**
     * List of the access path to this nestable model.
     * This array should contain all parents.
     *
     *
     * @return self[]
     */
    public function getAncestors(): array;

    /**
     * @return self[]
     */
    public function getChildren(): iterable;

    /**
     * Nested array of the complete child structure
     * belonging to this parent model.
     */
    public function getDescendants(): NestableTree;

    /**
     * List of all the descendant ids.
     */
    public function getDescendantIds(): array;

    /**
     * @return self[]
     */
    public function getSiblings(): iterable;
}
