<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Types;

trait AllowsCharacterCount
{
    protected int $characterCount = 0;

    /**
     * @return TextField
     */
    public function characterCount(int $max): self
    {
        $this->characterCount = $max;

        return $this;
    }

    public function hasCharacterCount(): bool
    {
        return 0 < $this->characterCount;
    }

    public function getCharacterCount(): int
    {
        return $this->characterCount;
    }
}
