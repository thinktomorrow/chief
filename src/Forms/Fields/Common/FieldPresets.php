<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Common;

use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Textarea;
use Thinktomorrow\Chief\Forms\Form;

class FieldPresets
{
    public static function pagetitle(?Field $field = null): iterable
    {
        yield Form::make('pagetitle')->editInSidebar(
            'chief-form::templates.pagetitle.form-in-pagetitle',
            'chief-form::templates.pagetitle.form-in-inline-container',
        )->items([
            $field ?? Text::make('title')->label('Titel')->locales()->required(),
        ]);
    }

    public static function seo(): iterable
    {
        yield Form::make('form_seo')->title('Seo')->position('aside')->editInSidebar()->items([
            Text::make('seo_title')
                ->label('Titel')
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
                ->label('Afbeelding')
                ->locales()
                ->tag(['seo', 'not-on-create']),
        ]);
    }
}
