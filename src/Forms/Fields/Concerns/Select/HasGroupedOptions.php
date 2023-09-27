<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns\Select;

trait HasGroupedOptions
{
    /** @deprecated grouped is now auto determined by the given options */
    public function grouped(bool $grouped = true): static
    {
        return $this;
    }
}
