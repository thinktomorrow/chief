<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Fields\Types\MediaField;

class UploadMediaManager extends PageManager
{
    public function fields(): Fields
    {
        return new Fields([
            MediaField::make(MediaType::HERO)
                ->validation('image_required|chief_dimensions:min_width=100,min_height=200'),
            MediaField::make(MediaType::DOCUMENT)
        ]);
    }
}
