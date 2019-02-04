<?php
declare(strict_types = 1);
namespace Thinktomorrow\Chief\Fields\Types;

class HtmlField extends Field
{
    public static function make(string $key)
    {
        return new static(new FieldType(FieldType::HTML), $key);
    }
}
