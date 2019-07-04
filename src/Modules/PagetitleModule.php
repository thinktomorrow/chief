<?php

namespace Thinktomorrow\Chief\Modules;

use Thinktomorrow\Chief\Fields\Types\HtmlField;

class PagetitleModule extends Module
{
    protected static $registrationKey = 'pagetitle';

    public static function defaultTranslatableFields(): array
    {
        return [
            'content' => HtmlField::make('content'),
        ];
    }
}
