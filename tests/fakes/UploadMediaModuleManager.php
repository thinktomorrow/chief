<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Modules\ModuleManager;
use Thinktomorrow\Chief\Fields\Types\MediaField;

class UploadMediaModuleManager extends ModuleManager
{
    public function fields(): Fields
    {
        return new Fields([
            MediaField::make(MediaType::HERO)
        ]);
    }
}
