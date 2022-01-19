<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Closure;

trait HasPrepend
{
    protected null|string|int|array|Closure $prepend = null;

    public function prepend(null|string|int|array|Closure $prepend): static
    {
        $this->prepend = $prepend;

        return $this;
    }

    public function hasPrepend(): bool
    {
        return ! is_null($this->prepend);
    }

    public function getPrepend(?string $locale = null): null|string|int|array
    {
        return $this->getLocalizableProperty($this->prepend, $locale);
    }
}
