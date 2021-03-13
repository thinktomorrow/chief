<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

use Thinktomorrow\Chief\ManagedModels\Fields\Field;

class InputField extends AbstractField implements Field
{
    use AllowsCharacterCount;

    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::INPUT), $key);
    }
}
