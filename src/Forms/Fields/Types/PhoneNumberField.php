<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Types;

use Thinktomorrow\Chief\Forms\Fields\Field;

class PhoneNumberField extends AbstractField implements Field
{
    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::PHONENUMBER), $key);
    }
}
