<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Concerns;

trait HasLocalizableProperties
{
    /**
     * Facilitates the retrieval of localizable properties
     * such as prepend, append, placeholder, ...
     */
    protected function getLocalizableProperty(mixed $value, ?string $locale = null): mixed
    {
        if (is_callable($value)) {
            return call_user_func_array($value, [$locale]);
        }

        if ($locale && is_array($value) && array_key_exists($locale, $value)) {
            return $value[$locale];
        }

        return $value;
    }
}
