<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree;

use Thinktomorrow\Vine\Node;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable;

interface NestedNode extends Node
{
    public function getId(): string;
    public function getModel(): Nestable;
    public function getParentNodeId(): ?string;

    public function getLabel(): string;
    public function getBreadCrumbLabel(bool $withoutRoot = false): string;
    public function getBreadCrumbLabelWithoutRoot(): string;
}
