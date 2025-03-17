<?php

namespace Thinktomorrow\Chief\Forms\Fields\FieldName;

class LivewireFieldName
{
    public static function get(string $name, ?string $locale = null, ?string $index = null): string
    {
        if ($locale) {
            throw new \Exception('Dont pass locale as second parameter. Let the name be constructed as is');
        }

        return 'form.'.static::getWithoutPrefix($name, $locale, $index);
    }

    public static function getWithoutPrefix(string $name, ?string $locale = null, ?string $index = null): ?string
    {
        if ($locale) {
            throw new \Exception('Dont pass locale as second parameter. Let the name be constructed as is');
        }

        $name = FieldNameHelpers::replaceBracketsByDots($name);

        return ($index ? $index.'.' : null).$name;
        //        return ($index ? $index . '.' : null) . $name . (isset($locale) ? '.' . $locale : null);
    }
}
