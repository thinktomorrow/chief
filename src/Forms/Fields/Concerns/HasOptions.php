<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

use Closure;

trait HasOptions
{
    protected array|Closure $options = [];

    public function options(array|Closure $options): static
    {
        $this->options = $this->sanitizeOptions($options);

        return $this;
    }

    public function getOptions(?string $locale = null): array
    {
        if (is_callable($this->options)) {
            return call_user_func_array($this->options, [$this, $locale]);
        }

        return $this->options;
    }

    /**
     * Convert non-associative array to associative one.
     * If you want to force an non-assoc. array, you can use a Closure.
     */
    private function sanitizeOptions(array|Closure $options): array|Closure
    {
        if (!is_array($options) || !array_is_list($options)) {
            return $options;
        }

        return array_combine($options, $options);
    }
}
