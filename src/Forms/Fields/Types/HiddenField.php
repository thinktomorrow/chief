<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Types;

use Thinktomorrow\Chief\Forms\Fields\Field;

class HiddenField extends AbstractField implements Field
{
    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::HIDDEN), $key);
    }

    public function renderOnPage(array $viewData = []): string
    {
        return '';
    }
}
