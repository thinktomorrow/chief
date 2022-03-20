<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Thinktomorrow\Chief\Forms\Fields\File;

class ArticlePageResourceWithFileValidation extends ArticlePageResource
{
    public function fields($model): iterable
    {
        yield File::make('thumb_trans')
                ->label('thumb trans')
                ->locales(['nl', 'en'])
                ->rules([
                    'required',
                    'mimetypes:image/png,text/plain',
                    'dimensions:min_width=100,min_height=100',
                    'max:2',
                    'min:1',
                ]);
    }
}
