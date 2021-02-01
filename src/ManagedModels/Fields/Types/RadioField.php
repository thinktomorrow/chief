<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

class RadioField extends AbstractField implements Field
{
    use AllowsOptions;

    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::RADIO), $key);
    }
}
