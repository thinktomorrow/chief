<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

use Thinktomorrow\Chief\ManagedModels\Fields\Field;

class HiddenField extends AbstractField implements Field
{
    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::HIDDEN), $key);
    }

    public function renderWindow(array $viewData = []): string
    {
        return '';
    }

}
