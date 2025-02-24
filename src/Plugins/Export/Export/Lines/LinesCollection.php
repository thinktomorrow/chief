<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export\Lines;

use Illuminate\Support\Collection;
use InvalidArgumentException;

class LinesCollection extends Collection
{
    public function current(): ?Line
    {
        return parent::current();
    }

    public function offsetGet($key): ?Line
    {
        return parent::offsetGet($key);
    }

    public function offsetSet($key, $value): void
    {
        if (! $value instanceof Line) {
            throw new InvalidArgumentException('value must be instance of '.Line::class);
        }

        parent::offsetSet($key, $value);
    }
}
