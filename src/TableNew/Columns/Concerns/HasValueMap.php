<?php

namespace Thinktomorrow\Chief\TableNew\Columns\Concerns;

trait HasValueMap
{

    protected array $valueMap = [];

    /**
     * Value mapping e.g. published => online
     */
    public function valueMap(array $valueMap): static
    {
        $this->valueMap = $valueMap;

        return $this;
    }
}
