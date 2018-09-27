<?php

namespace Thinktomorrow\Chief\Modules;

use Thinktomorrow\Chief\Common\Fields\HtmlField;

class TextModule extends Module
{
    public static function defaultTranslatableFields(): array
    {
        return [
            HtmlField::make('content'),
        ];
    }
}
