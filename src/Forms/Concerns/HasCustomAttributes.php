<?php

namespace Thinktomorrow\Chief\Forms\Concerns;

trait HasCustomAttributes
{
    protected array $customAttributes = [];

    public function customAttributes(array $customAttributes): static
    {
        $this->customAttributes = $customAttributes;

        return $this;
    }

    public function getCustomAttributes(): array
    {
        return $this->customAttributes;
    }
}
