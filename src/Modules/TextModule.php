<?php

namespace Thinktomorrow\Chief\Modules;

use Thinktomorrow\Chief\Common\TranslatableFields\HtmlField;

class TextModule extends Module
{
    public static function defaultTranslatableFields(): array
    {
        return [
            'content' => HtmlField::make(),
        ];
    }
}
