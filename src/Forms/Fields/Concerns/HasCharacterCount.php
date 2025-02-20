<?php

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasCharacterCount
{
    protected int $characterCount = 0;

    public function characterCount(int $characterCount): static
    {
        $this->characterCount = $characterCount;

        return $this;
    }

    public function hasCharacterCount(): bool
    {
        return $this->characterCount > 0;
    }

    public function getCharacterCount(): int
    {
        return $this->characterCount;
    }
}
