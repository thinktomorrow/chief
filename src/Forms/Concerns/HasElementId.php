<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Concerns;

trait HasElementId
{
    protected string $elementId;

    public function elementId(string $elementId): static
    {
        $this->elementId = $elementId;

        return $this;
    }

    public function getElementId(?string $locale = null): string
    {
        return $this->elementId.($locale ? '_'.$locale : '');
    }
}
