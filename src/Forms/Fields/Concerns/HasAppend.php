<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Closure;

trait HasAppend
{
    protected null|string|int|array|Closure $append = null;

    public function append(null|string|int|array|Closure $append): static
    {
        $this->append = $append;

        return $this;
    }

    public function hasAppend(): bool
    {
        return ! is_null($this->append);
    }

    public function getAppend(?string $locale = null): null|string|int|array
    {
        return $this->getLocalizableProperty($this->append, $locale);
    }
}
