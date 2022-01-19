<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasModelValuePreparation
{
    protected ?\Closure $setModelValue = null;
    protected ?\Closure $prepareModelValue = null;

    public function setModelValue(\Closure $setModelValue): static
    {
        $this->setModelValue = $setModelValue;

        return $this;
    }

    public function hasSetModelValue(): bool
    {
        return ! is_null($this->setModelValue);
    }

    public function getSetModelValue(): ?\Closure
    {
        if (! $this->hasSetModelValue()) {
            return null;
        }

        return $this->setModelValue;
    }

    public function prepare(\Closure $prepareModelValue): static
    {
        $this->prepareModelValue = $prepareModelValue;

        return $this;
    }

    public function hasPrepareModelValue(): bool
    {
        return ! is_null($this->prepareModelValue);
    }

    public function getPrepareModelValue(): ?\Closure
    {
        if (! $this->hasPrepareModelValue()) {
            return null;
        }

        return $this->prepareModelValue;
    }
}
