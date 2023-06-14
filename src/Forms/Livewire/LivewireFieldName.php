<?php

namespace Thinktomorrow\Chief\Forms\Livewire;

use Thinktomorrow\Chief\Forms\Fields\Common\FormKey;

class LivewireFieldName
{
    public static function get(string $name, ?string $locale = null): string
    {
        return "form.".static::getWithoutPrefix($name, $locale);
    }

    public static function getWithoutPrefix(string $name, ?string $locale = null): ?string
    {
        $name = FormKey::replaceBracketsByDots($name);

        return $name.(isset($locale) ?'.'.$locale : null);
    }
}
