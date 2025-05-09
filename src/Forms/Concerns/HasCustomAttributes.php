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

    public function getCustomAttributesAsString(): ?string
    {
        return collect($this->customAttributes)->map(function ($value, $key) {
            return $key.'="'.$value.'"';
        })->implode(' ');
    }
}
