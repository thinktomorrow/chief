<?php

namespace Thinktomorrow\Chief\Modules;

use Thinktomorrow\Chief\Fields\Types\HtmlField;

class TextModule extends Module
{
    protected static $registrationKey = 'text';

    public static function defaultTranslatableFields(): array
    {
        return [
            HtmlField::make('content'),
        ];
    }
}
