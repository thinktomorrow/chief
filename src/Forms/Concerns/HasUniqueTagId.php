<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Concerns;

trait HasUniqueTagId
{
    protected string $uniqueTagId;

    public function uniqueTagId(string $uniqueTagId): static
    {
        $this->uniqueTagId = $uniqueTagId;

        return $this;
    }

    public function getUniqueTagId(): string
    {
        return $this->uniqueTagId;
    }
}
