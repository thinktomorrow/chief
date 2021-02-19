<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\ImageField;

class ArticlePageWithImageValidation extends ArticlePage
{
    public function fields(): Fields
    {
        return new Fields([
            ImageField::make('thumb_image_trans')->translatable(['nl', 'en'])->validation([
                'required',
                'mimetypes:image/png,text/plain',
                'dimensions:min_width=100,min_height=100',
                'max:2',
                'min:1',
            ]),
        ]);
    }
}
