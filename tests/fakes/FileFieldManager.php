<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Fields\Types\FileField;

class FileFieldManager extends PageManager
{
    public function fields(): Fields
    {
        return new Fields([
            FileField::make(MediaType::HERO)
                ->locales(['nl','en'])
                ->validation([
                'required',
                'mimetypes:image/png,text/plain',
                'dimensions:min_width=100,min_height=100',
                'max:2',
                'min:1',
            ])
        ]);
    }
}
