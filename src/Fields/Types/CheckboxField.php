<?php declare(strict_types = 1);

namespace Thinktomorrow\Chief\Fields\Types;

class CheckboxField extends AbstractField implements Field
{
    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::CHECKBOX), $key);
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
