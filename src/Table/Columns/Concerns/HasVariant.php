<?php

namespace Thinktomorrow\Chief\Table\Columns\Concerns;

trait HasVariant
{
    protected ?string $variant = null;

    /**
     * Variant of the element determines the UI styling of the element.
     * Depending on the type of element (text, button, badge, ...)
     * the variant has different renderings (color, border, ...).
     */
    public function variant(?string $variant): static
    {
        $this->variant = $variant;

        return $this;
    }

    public function getVariant(): ?string
    {
        return $this->variant;
    }
}
