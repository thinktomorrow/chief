<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasCustomPrepForSaving
{
    protected ?\Closure $prepForSaving = null;

    public function prepForSaving(\Closure $prepareModelValue): static
    {
        $this->prepForSaving = $prepareModelValue;

        return $this;
    }

    public function hasPrepForSaving(): bool
    {
        return ! is_null($this->prepForSaving);
    }

    public function getPrepForSaving(): ?\Closure
    {
        if (! $this->hasPrepForSaving()) {
            return null;
        }

        return $this->prepForSaving;
    }
}
