<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

use Thinktomorrow\Chief\ManagedModels\Fields\Field;

class CheckboxField extends AbstractField implements Field
{
    use AllowsOptions;
    use AllowsMultiple;

    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::CHECKBOX), $key);
    }
}
