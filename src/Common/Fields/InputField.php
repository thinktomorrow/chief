<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Common\Fields;

class InputField extends Field
{
    public static function make(string $key)
    {
        return new static(new FieldType(FieldType::INPUT), $key);
    }
}
