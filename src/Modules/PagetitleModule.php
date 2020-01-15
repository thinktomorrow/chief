<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Modules;

use Thinktomorrow\Chief\Fields\Types\HtmlField;

class PagetitleModule extends Module
{
    protected static $managedModelKey = 'pagetitle';

    public static function defaultTranslatableFields(): array
    {
        return [
            'content' => HtmlField::make('content'),
        ];
    }
}
