<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Modules;

use Thinktomorrow\Chief\Fields\Types\HtmlField;

class TextModule extends Module
{
    protected static $managedModelKey = 'text';

    public static function defaultTranslatableFields(): array
    {
        return [
            HtmlField::make('content'),
        ];
    }
}
