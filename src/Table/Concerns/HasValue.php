<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Concerns;

trait HasValue
{
    protected string|null|int $value = null;

    public function getValue(): string|null|int
    {
        return $this->value;
    }

    public function value(string|null|int $value): static
    {
        $this->value = $value;

        return $this;
    }
}
