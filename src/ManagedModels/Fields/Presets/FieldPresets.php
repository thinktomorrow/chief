<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Presets;

use Thinktomorrow\Chief\ManagedModels\Fields\FieldGroup;
use Thinktomorrow\Chief\ManagedModels\Fields\FieldWindow;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\ImageField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\TextField;

class FieldPresets
{
    public static function seo(): iterable
    {
        yield FieldWindow::open('SEO');
            yield FieldGroup::open();
                yield InputField::make('seo_title')
                        ->label('Paginatitel zoals deze in zoekmachines wordt getoond.')
                        ->locales()
                        ->characterCount(66)
                        ->notOnCreate();
                yield TextField::make('seo_content')
                        ->label('Korte inhoud')
                        ->description('Pagina omschrijving in zoekmachines.')
                        ->locales()
                        ->characterCount(160)
                        ->notOnCreate();
                yield ImageField::make('seo_image')
                        ->label('Seo afbeelding')
                        ->locales()
                        ->notOnCreate();
            yield FieldGroup::close();
        yield FieldWindow::close();
    }
}
