<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasValuePreparation
{
    protected ?\Closure $prepareValue = null;

    /**
     * Prepare value for rendering in the view
     */
    public function prepareValue(\Closure $prepareValue): static
    {
        $this->prepareValue = $prepareValue;

        return $this;
    }

    public function hasPrepareValue(): bool
    {
        return ! is_null($this->prepareValue);
    }

    public function getPrepareValue(): ?\Closure
    {
        if (! $this->hasPrepareValue()) {
            return null;
        }

        return $this->prepareValue;
    }
}
