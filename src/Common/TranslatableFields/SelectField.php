<?php
declare(strict_types = 1);

namespace Thinktomorrow\Chief\Common\TranslatableFields;

class SelectField extends Field
{
    public static function make()
    {
        return new static(new FieldType(FieldType::SELECT));
    }

    public function options(array $values)
    {
        $this->values['options'] = $values;

        return $this;
    }

    public function selected($values)
    {
        $this->values['selected'] = $values;

        return $this;
    }
}
