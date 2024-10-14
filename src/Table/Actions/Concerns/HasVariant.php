<?php

namespace Thinktomorrow\Chief\Table\Actions\Concerns;

trait HasVariant
{
    protected string $variant = 'secondary';

    private function setVariant(string $variant): static
    {
        $this->variant = $variant;

        return $this;
    }

    public function primary(): static
    {
        return $this->setVariant('primary');
    }

    public function secondary(): static
    {
        return $this->setVariant('secondary');
    }

    public function tertiary(): static
    {
        return $this->setVariant('tertiary');
    }

    public function isPrimary(): bool
    {
        return $this->variant === 'primary';
    }

    public function isSecondary(): bool
    {
        return $this->variant === 'secondary';
    }

    public function isTertiary(): bool
    {
        return $this->variant === 'tertiary';
    }
}
