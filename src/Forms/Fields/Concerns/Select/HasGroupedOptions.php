<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns\Select;

trait HasGroupedOptions
{
    private bool $grouped = false;

    public function grouped(bool $grouped = true): static
    {
        $this->grouped = $grouped;

        return $this;
    }

    public function isGrouped(): bool
    {
        return $this->grouped;
    }
}
