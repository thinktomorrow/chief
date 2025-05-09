<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasValueFallback
{
    /**
     * This is a flag to set fallback strategy when retrieving the value.
     * Note that on frontend this setting has no effect and fallback
     * strategy on front is in effect, regardless of this flag.
     */
    protected bool $useValueFallback = false;

    public function useValueFallback(bool $useValueFallback = true): static
    {
        $this->useValueFallback = $useValueFallback;

        return $this;
    }
}
