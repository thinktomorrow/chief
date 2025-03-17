<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Concerns;

use Thinktomorrow\Chief\Forms\Layouts\LayoutType;

trait HasLayoutType
{
    protected LayoutType $layoutType = LayoutType::none;

    public function layoutType(string $type): static
    {
        $this->layoutType = LayoutType::from($type);

        return $this;
    }

    public function getLayoutType(): LayoutType
    {
        return $this->layoutType;
    }
}
