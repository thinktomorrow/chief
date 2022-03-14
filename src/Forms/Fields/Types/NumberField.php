<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Types;

use Thinktomorrow\Chief\Forms\Fields\Field;

class NumberField extends AbstractField implements Field
{
    use AllowsRange;

    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::NUMBER), $key);
    }
}