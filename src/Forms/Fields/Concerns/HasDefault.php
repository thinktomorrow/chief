<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Closure;

trait HasDefault
{
    protected null|string|int|array|Closure $default = null;

    public function default(null|string|int|array|Closure $default): static
    {
        $this->default = $default;

        return $this;
    }

    public function getDefault(?string $locale = null): null|string|int|array
    {
        return $this->getLocalizableProperty($this->default, $locale);
    }
}
