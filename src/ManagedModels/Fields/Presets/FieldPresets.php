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
        yield InputField::make('seo_title')
            ->label('Pagina titel')
            ->locales()
            ->component('SEO')
            ->notOnCreate();
        yield TextField::make('seo_content')
            ->label('Korte inhoud')
            ->description('Een korte samenvatting van deze pagina.')
            ->locales()
            ->component('SEO')
            ->notOnCreate();
        yield InputField::make('seo_keywords')
            ->label('Sleutelwoorden')
            ->description('Woorden waarop je zelf zou zoeken om deze pagina te vinden.')
            ->locales()
            ->component('SEO')
            ->notOnCreate();
        yield ImageField::make('seo_image')
            ->label('Afbeelding')
            ->description('Deze afbeelding wordt getoond')
            ->locales()
            ->component('SEO')
            ->notOnCreate();
    }
}
