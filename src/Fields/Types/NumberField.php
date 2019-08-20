<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Fields\Types;

class NumberField extends Field
{
    public static function make(string $key)
    {
        return new static(new FieldType(FieldType::NUMBER), $key);
    }

    public function steps(int $steps = 1)
    {
        $this->values['steps'] = $steps;

        return $this;
    }

    public function max(int $max)
    {
        $this->values['max'] = $max;

        return $this;
    }

    public function min(int $min)
    {
        $this->values['min'] = $min;

        return $this;
    }
}
