<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

class TextField extends AbstractField implements Field
{
    private $characterCount = false;

    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::TEXT), $key);
    }

    public function withCharacterCount()
    {
        $this->characterCount = true;

        return $this;
    }

    public function hasCharacterCount()
    {
        return $this->characterCount;
    }
}
