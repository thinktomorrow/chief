<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns\Select;

use Closure;

trait HasOptions
{
    protected array|Closure $options = [];

    private bool $sanitizeOptions = true;

    public function rawOptions(array|Closure $options): static
    {
        return $this->options($options, false);
    }

    public function options(array|Closure $options, bool $sanitize = true): static
    {
        $this->sanitizeOptions = $sanitize;

        $this->options = $options;

        return $this;
    }

    public function getOptions(?string $locale = null): array
    {
        $options = $this->options;

        if (is_callable($options)) {
            $options = call_user_func_array($options, $this->getOptionsCallableParameters($locale));
        }

        if ($this->sanitizeOptions) {
            $options = PairOptions::toPairs($options);
        }

        return $options;
    }

    private function getOptionsCallableParameters(?string $locale = null): array
    {
        return [$this, $this->getModel(), $locale];
    }
}
