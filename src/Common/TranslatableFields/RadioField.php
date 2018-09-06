<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Common\TranslatableFields;

class RadioField extends SelectField
{
    public static function make()
    {
        return new static(new FieldType(FieldType::RADIO));
    }
}
