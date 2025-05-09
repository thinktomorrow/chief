<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Closure;

trait HasPrependAppend
{
    protected null|string|int|array|Closure $append = null;

    protected null|string|int|array|Closure $prepend = null;

    public function append(null|string|int|array|Closure $append): static
    {
        $this->append = $append;

        return $this;
    }

    public function prepend(null|string|int|array|Closure $prepend): static
    {
        $this->prepend = $prepend;

        return $this;
    }

    public function hasAppend(): bool
    {
        return ! is_null($this->append);
    }

    public function hasPrepend(): bool
    {
        return ! is_null($this->prepend);
    }

    public function getAppend(?string $locale = null): null|string|int|array
    {
        return $this->getLocalizableProperty($this->append, $locale);
    }

    public function getPrepend(?string $locale = null): null|string|int|array
    {
        return $this->getLocalizableProperty($this->prepend, $locale);
    }
}
