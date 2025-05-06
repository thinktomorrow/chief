<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FallbackLocaleRequiredRule;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Resource\PageResourceDefault;

class ArticlePageResource implements PageResource
{
    use PageResourceDefault;
    use WithCustomFieldDefinitions;

    public static function modelClassName(): string
    {
        return ArticlePage::class;
    }

    private function defaultFields($model): iterable
    {
        yield Form::make('title_form')->items([
            Text::make('title')->locales()->required()->rules(['min:4']),
        ]);

        yield Text::make('custom')
            ->rules(['required'])
            ->validationAttribute('custom attribute')
            ->validationMessages(['custom.required' => 'custom error for :attribute']);
        yield Text::make('title_trans')->locales();
        yield Text::make('content_trans')->locales()->rules(FallbackLocaleRequiredRule::RULE);

        yield File::make('thumb');
        yield File::make('thumb_enhanced')->items([
            Text::make('caption'),
            Text::make('alt')->locales(['nl', 'en']),
        ]);
        yield File::make('thumb_trans')->locales(['nl', 'en'])->tag('edit');
        yield Image::make('thumb_image')->tag('edit');
        yield Image::make('thumb_image_trans')->locales(['nl', 'en'])->tag('edit');
        yield File::make(ArticlePage::FILEFIELD_DISK_KEY)->storageDisk('secondMediaDisk')->tag('edit');
        yield Image::make(ArticlePage::IMAGEFIELD_DISK_KEY)->storageDisk('secondMediaDisk')->tag('edit');
        yield File::make(ArticlePage::FILEFIELD_ASSETTYPE_KEY)->assetType('custom')->tag('edit');

        yield Text::make('title_sanitized')->prepForSaving(function ($value, array $input, $locale = null) {
            if ($value) {
                return $value;
            }
            if (isset($input['title']) && isset($input['title'][$locale])) {
                return Str::slug($input['title'][$locale]);
            }

            return null;
        });

        yield Text::make('title_sanitized_trans')->locales()->prepForSaving(function ($value, array $input, $locale = null) {
            if ($value) {
                return $value;
            }

            if (isset($input['title']) && isset($input['title'][$locale])) {
                return Str::slug($input['title'][$locale]);
            }

            return null;
        });

        yield Form::make('seo')->items([
            Text::make('seo_title'),
        ]);
    }
}
