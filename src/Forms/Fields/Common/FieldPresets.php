<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Common;

use Thinktomorrow\Chief\Forms\Fields\Types\ImageField;
use Thinktomorrow\Chief\Forms\Fields\Types\InputField;
use Thinktomorrow\Chief\Forms\Fields\Types\TextField;

class FieldPresets
{
    public static function seo(): iterable
    {
        yield InputField::make('seo_title')
                        ->label('Paginatitel in zoekmachines')
                        ->locales()
                        ->characterCount(66)
                        ->tag('seo')
                        ->notOnCreate();
        yield TextField::make('seo_content')
                        ->label('Omschrijving')
                        ->description('Korte omschrijving in zoekmachines.')
                        ->locales()
                        ->characterCount(160)
                        ->tag('seo')
                        ->notOnCreate();
        yield ImageField::make('seo_image')
                        ->label('Seo afbeelding')
                        ->locales()
                        ->tag('seo')
                        ->notOnCreate();
    }
}
