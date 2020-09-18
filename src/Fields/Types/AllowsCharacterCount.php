<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

trait AllowsCharacterCount
{
    /** @var int */
    protected $characterCount = 0;

    public function characterCount(int $max)
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
