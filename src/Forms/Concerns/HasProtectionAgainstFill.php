<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Concerns;

trait HasProtectionAgainstFill
{
    protected bool $protectAgainstFill = false;

    public function protectAgainstFill(bool $protectAgainstFill = true): static
    {
        $this->protectAgainstFill = $protectAgainstFill;

        return $this;
    }

    public function isProtectedAgainstFill(): bool
    {
        return $this->protectAgainstFill;
    }
}
