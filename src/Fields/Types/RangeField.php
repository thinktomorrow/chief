<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

class RangeField extends AbstractField implements Field
{
    use AllowsRange;

    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::RANGE), $key);
    }
}
