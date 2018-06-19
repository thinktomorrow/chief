<?php
declare(strict_types = 1);
namespace Thinktomorrow\Chief\Common\TranslatableFields;

class InputField
{
    public static function make()
    {
        return Field::make(new FieldType(FieldType::INPUT));
    }


}