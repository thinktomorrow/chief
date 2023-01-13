<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Vine\Node;

interface NestedNode extends Node
{
    public function getId(): string;
    public function getParentNodeId(): ?string;
    public function getModel(): Model;

    public function showOnline(): bool;
    public function getUrlSlug(?string $locale = null): ?string;
    public function getLabel(?string $locale = null): string;

    /** @return NestedNode[] */
    public function getBreadCrumbs(): array;

    public function getBreadCrumbLabel(?string $locale = null, bool $withoutRoot = false): string;
    public function getBreadCrumbLabelWithoutRoot(?string $locale = null): string;
}
