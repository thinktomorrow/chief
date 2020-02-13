<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

class HtmlField extends AbstractField implements Field
{
    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::HTML), $key);
    }
}
