<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Fields\Types\FileField;
use Thinktomorrow\Chief\Fields\Types\ImageField;

class ImageFieldManager extends PageManager
{
    public function fields(): Fields
    {
        return new Fields([
            ImageField::make(MediaType::HERO)->validation([
                'required',
//                'mimetypes:image/png',
                'dimensions:min_width=100,min_height=100',
                'max:2',
                'min:1',
            ])
        ]);
    }
}
