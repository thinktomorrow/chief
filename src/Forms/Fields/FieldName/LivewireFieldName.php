<?php

namespace Thinktomorrow\Chief\Forms\Fields\FieldName;

class LivewireFieldName
{
    public static function get(string $name, ?string $prefix = 'form'): string
    {
        $name = FieldNameHelpers::replaceBracketsByDots($name);

        return $prefix ? $prefix.'.'.$name : $name;
    }
}
