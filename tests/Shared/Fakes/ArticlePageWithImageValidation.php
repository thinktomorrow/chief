<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\ImageField;

class ArticlePageWithImageValidation extends ArticlePage
{
    public function fields(): Fields
    {
        return Fields::make([
            ImageField::make('thumb_image_trans')->label('thumb image trans')->locales(['nl', 'en'])->validation([
                'required',
                'mimetypes:image/png,text/plain',
                'dimensions:min_width=100,min_height=100',
                'max:2',
                'min:1',
            ]),
        ]);
    }
}
