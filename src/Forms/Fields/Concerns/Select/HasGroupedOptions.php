<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns\Select;

trait HasGroupedOptions
{
    private bool $optionsAreAssumedGrouped = false;

    public function hasOptionGroups(?string $locale = null): bool
    {
        if ($this->optionsAreAssumedGrouped) {
            return true;
        }

        return PairOptions::areOptionsGrouped($this->getOptions($locale));
    }

    /**
     * Indicate that the options are grouped. This is adviced when using closures because
     * this avoids calling the closure results to check the option results.
     */
    public function grouped(): static
    {
        $this->optionsAreAssumedGrouped = true;

        return $this;
    }
}
