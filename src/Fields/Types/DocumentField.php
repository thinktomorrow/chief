<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Fields\Types;

class DocumentField extends Field
{
    public static function make(string $key)
    {
        return new static(new FieldType(FieldType::DOCUMENT), $key);
    }

    public function multiple($flag = true)
    {
        $this->values['multiple'] = $flag;

        return $this;
    }
}
