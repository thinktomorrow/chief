<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Closure;

trait HasPlaceholder
{
    protected null|string|int|array|Closure $placeholder = null;

    public function placeholder(null|string|int|array|Closure $placeholder): static
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getPlaceholder(?string $locale = null): null|string|int|array
    {
        return $this->getLocalizableProperty($this->placeholder, $locale);
    }
}
