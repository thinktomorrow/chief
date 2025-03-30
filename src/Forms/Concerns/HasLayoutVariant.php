<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Concerns;

use Thinktomorrow\Chief\Forms\Layouts\LayoutVariant;

trait HasLayoutVariant
{
    protected LayoutVariant $layoutVariant = LayoutVariant::none;

    public function layoutType(string $variant): static
    {
        $this->layoutVariant = LayoutVariant::from($variant);

        return $this;
    }

    public function getLayoutVariant(): LayoutVariant
    {
        return $this->layoutVariant;
    }
}
