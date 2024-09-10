<?php

namespace Thinktomorrow\Chief\TableNew\Columns\Concerns;

use Closure;

trait HasMultipleValues
{
    private ?Closure $evaluateEachValue = null;

    public function eachValue(Closure $valueCallable): static
    {
        $this->evaluateEachValue = $valueCallable;

        return $this;
    }

    protected function handleEachValue(array $value): array
    {
        if ($this->evaluateEachValue) {
            return collect($value)->map($this->evaluateEachValue)->all();
        }

        return $value;
    }
}
