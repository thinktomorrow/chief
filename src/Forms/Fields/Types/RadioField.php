<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Types;

use Thinktomorrow\Chief\Forms\Fields\Field;

class RadioField extends AbstractField implements Field
{
    use AllowsOptions;

    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::RADIO), $key);
    }
}
