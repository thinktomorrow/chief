<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FallbackLocaleRequiredRule;
use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Resource\PageResourceDefault;

class ArticlePageResource implements PageResource
{
    use PageResourceDefault;

    public static function modelClassName(): string
    {
        return ArticlePage::class;
    }

    // Defines which atttribute of the model is the title.
    // This is currently only used for duplicating a page and copying its title.
    public function getTitleAttributeKey(): string
    {
        return 'custom';
    }

    public function fields($model): iterable
    {
        yield Text::make('title')->required()->rules(['min:4']);
        yield Text::make('custom')
            ->rules(['required'])
            ->validationAttribute('custom attribute')
            ->validationMessages(['custom.required' => 'custom error for :attribute']);
        yield Text::make('title_trans')->locales(['nl', 'en']);
        yield Text::make('content_trans')->locales(['nl', 'en'])->rules(FallbackLocaleRequiredRule::RULE);

        yield File::make('thumb');
        yield File::make('thumb_trans')->locales(['nl', 'en'])->tag('edit');
        yield File::make(ArticlePage::FILEFIELD_DISK_KEY)->storageDisk('secondMediaDisk')->tag('edit');
        yield Image::make('thumb_image')->tag('edit');
        yield Image::make('thumb_image_trans')->locales(['nl', 'en'])->tag('edit');
        yield Image::make(ArticlePage::IMAGEFIELD_DISK_KEY)->storageDisk('secondMediaDisk')->tag('edit');

        yield Text::make('title_sanitized')->prepare(function ($value, array $input) {
            if ($value) {
                return $value;
            }
            if (isset($input['title'])) {
                return Str::slug($input['title']);
            }

            return null;
        });

        yield Text::make('title_sanitized_trans')->locales()->prepare(function ($value, array $input, $locale = null) {
            if ($value) {
                return $value;
            }
            if (isset($input['title'])) {
                return Str::slug($input['title']) . '-' . $locale;
            }

            return null;
        });

        yield Form::make('seo')->items([
            Text::make('seo_title'),
        ]);
    }
}
