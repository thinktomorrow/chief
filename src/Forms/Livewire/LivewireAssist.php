<?php

namespace Thinktomorrow\Chief\Forms\Livewire;

use Thinktomorrow\Chief\Forms\Fields\Common\FormKey;

class LivewireAssist
{
    public static function formDataIdentifier(string $name, ?string $locale = null): string
    {
        return "form.".static::formDataIdentifierSegment($name, $locale);
    }

    public static function formDataIdentifierSegment(string $name, ?string $locale = null): ?string
    {
        $name = FormKey::replaceBracketsByDots($name);

        return $name.(isset($locale) ?'.'.$locale : null);
    }
}
