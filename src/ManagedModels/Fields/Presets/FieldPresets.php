<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Presets;

use Thinktomorrow\Chief\ManagedModels\Fields\Types\ImageField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\TextField;

class FieldPresets
{
    public static function seo(): iterable
    {
        yield InputField::make('seo_title')->locales()->component('SEO')->notOnCreate();
        yield TextField::make('seo_content')->locales()->component('SEO')->notOnCreate();
        yield ImageField::make('seo_image')->locales()->component('SEO')->notOnCreate();
    }
}
