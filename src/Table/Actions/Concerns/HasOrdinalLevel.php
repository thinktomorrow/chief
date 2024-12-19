<?php

namespace Thinktomorrow\Chief\Table\Actions\Concerns;

trait HasOrdinalLevel
{
    protected string $ordinalLevel = 'secondary';

    private function ordinalLevel(string $ordinalLevel): static
    {
        $this->ordinalLevel = $ordinalLevel;

        return $this;
    }

    public function primary(): static
    {
        return $this->ordinalLevel('primary');
    }

    public function secondary(): static
    {
        return $this->ordinalLevel('secondary');
    }

    public function tertiary(): static
    {
        return $this->ordinalLevel('tertiary');
    }

    public function isPrimary(): bool
    {
        return $this->ordinalLevel === 'primary';
    }

    public function isSecondary(): bool
    {
        return $this->ordinalLevel === 'secondary';
    }

    public function isTertiary(): bool
    {
        return $this->ordinalLevel === 'tertiary';
    }
}
