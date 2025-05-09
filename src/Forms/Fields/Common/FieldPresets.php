<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Common;

use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Textarea;
use Thinktomorrow\Chief\Forms\Layouts\Form;

class FieldPresets
{
    public static function pagetitle(?Field $field = null): iterable
    {
        yield Form::make('pagetitle')
            ->title('Titel')
            ->displayAsCompactForm()
            ->items([
                $field ?? Text::make('title')
                    ->label('Titel')
                    ->tag('pagetitle')
                    ->locales()
                    ->previewView('chief-form::previews.fields.text-as-pagetitle')
                    ->required(),
            ]);
    }

    public static function seo(): iterable
    {
        yield Form::make('form_seo')
            ->title('Seo')
            ->position('aside')
            ->displayAsTransparentForm()
            ->items([
                Text::make('seo_title')
                    ->label('Titel')
                    ->locales()
                    ->characterCount(66)
                    ->tag(['seo', 'not-on-model-create', 'not-on-create']),
                Textarea::make('seo_content')
                    ->label('Omschrijving')
                    ->description('Korte omschrijving in zoekmachines.')
                    ->locales()
                    ->characterCount(160)
                    ->tag(['seo', 'not-on-model-create', 'not-on-create']),
                Image::make('seo_image')
                    ->label('Afbeelding')
                    ->locales()
                    ->tag(['seo', 'not-on-model-create', 'not-on-create']),
            ]);
    }
}
