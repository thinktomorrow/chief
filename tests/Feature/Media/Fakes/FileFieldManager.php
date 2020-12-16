<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Feature\Media\Fakes;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Fields\Types\FileField;

class FileFieldManager extends PageManager
{
    public function fields(): Fields
    {
        return new Fields([
            FileField::make('fake-file'),
        ]);
    }
}
