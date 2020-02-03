<?php declare(strict_types = 1);

namespace Thinktomorrow\Chief\Fields\Types;

class SelectField extends AbstractField implements Field
{
    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::SELECT), $key);
    }

    public function options(array $values)
    {
        $this->values['options'] = $values;

        return $this;
    }

    public function grouped(bool $grouped = true)
    {
        $this->values['grouped'] = $grouped;

        return $this;
    }

    public function isGrouped()
    {
        return isset($this->values['grouped']);
    }

    public function selected($values)
    {
        $this->values['selected'] = $values;

        return $this;
    }

    public function multiple($value = true)
    {
        $this->values['multiple'] = $value;

        return $this;
    }
}
