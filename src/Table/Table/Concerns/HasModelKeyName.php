<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

/**
 * The attribute of each result item that indicates the unique key of the result.
 * Default is 'id'.
 */
trait HasModelKeyName
{
    protected string $modelKeyName = 'id';

    public function modelKeyName(string $modelKeyName): static
    {
        $this->modelKeyName = $modelKeyName;

        return $this;
    }

    public function getModelKeyName(): string
    {
        return $this->modelKeyName;
    }
}
