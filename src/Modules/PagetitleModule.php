<?php

namespace Thinktomorrow\Chief\Modules;

use Thinktomorrow\Chief\Common\TranslatableFields\HtmlField;

class PagetitleModule extends Module
{
    public static function defaultTranslatableFields(): array
    {
        return [
            'content' => HtmlField::make(),
        ];
    }
}
