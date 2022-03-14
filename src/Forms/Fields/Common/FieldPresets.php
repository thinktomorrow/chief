<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Common;

use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Forms\Fields\Textarea;

class FieldPresets
{
    public static function seo(): iterable
    {
        yield Form::make('form_seo')->displayInWindow()->items([
            Text::make('seo_title')
                ->label('Paginatitel in zoekmachines')
                ->locales()
                ->characterCount(66)
                ->tag(['seo', 'not-on-create']),
            Textarea::make('seo_content')
                ->label('Omschrijving')
                ->description('Korte omschrijving in zoekmachines.')
                ->locales()
                ->characterCount(160)
                ->tag(['seo', 'not-on-create']),
            Image::make('seo_image')
                ->label('Seo afbeelding')
                ->locales()
                ->tag(['seo', 'not-on-create']),
        ]);


    }
}
