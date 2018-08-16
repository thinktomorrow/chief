<?php
declare(strict_types = 1);
namespace Thinktomorrow\Chief\Common\TranslatableFields;

class DateField extends Field
{
    public static function make()
    {
        return new static(new FieldType(FieldType::DATE));
    }
}
