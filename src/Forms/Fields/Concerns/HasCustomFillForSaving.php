<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasCustomFillForSaving
{
    protected ?\Closure $fillForSaving = null;

    public function fillForSaving(\Closure $fillForSaving): static
    {
        $this->fillForSaving = $fillForSaving;

        return $this;
    }

    public function hasFillForSaving(): bool
    {
        return ! is_null($this->fillForSaving);
    }

    public function getFillForSaving(): ?\Closure
    {
        if (! $this->hasFillForSaving()) {
            return null;
        }

        return $this->fillForSaving;
    }
}
