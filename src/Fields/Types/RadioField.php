<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Fields\Types;

class RadioField extends SelectField
{
    public static function make(string $key)
    {
        return new static(new FieldType(FieldType::RADIO), $key);
    }
}
