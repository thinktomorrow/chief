<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Feature\Media\Fakes;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Fields\Types\ImageField;

class OptionalImageFieldManager extends PageManager
{
    public function fields(): Fields
    {
        return new Fields([
            ImageField::make(MediaType::HERO)->validation([
                'mimetypes:image/png,text/plain',
                'dimensions:min_width=100,min_height=100',
                'max:2',
                'min:1',
            ])
        ]);
    }
}
