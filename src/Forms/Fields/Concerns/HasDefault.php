<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Closure;

trait HasDefault
{
    protected null|string|int|array|Closure $default = null;

    /**
     * Flag to allow to ignore any given default values. This is used to explicitly get
     * the real value, and not the fallback default. Used in preview windows.
     */
    protected bool $useDefault = true;

    public function default(null|string|int|array|Closure $default): static
    {
        $this->default = $default;

        return $this;
    }

    public function getDefault(?string $locale = null): null|string|int|array
    {
        if (!$this->useDefault) {
            return null;
        }

        return $this->getLocalizableProperty($this->default, $locale);
    }

    public function ignoreDefault(): static
    {
        $this->useDefault = false;

        return $this;
    }
}
