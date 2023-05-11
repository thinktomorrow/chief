<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable;
use Thinktomorrow\Vine\Node;

interface NestedNode extends Node
{
    public function getId(): string;
    public function getModel(): Nestable;
    public function getParentNodeId(): ?string;

    public function getLabel(): string;
    public function getBreadCrumbLabel(bool $withoutRoot = false): string;
    public function getBreadCrumbLabelWithoutRoot(): string;
}
