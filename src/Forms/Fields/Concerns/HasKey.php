<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasKey
{
    protected string $key;

    public function key(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
