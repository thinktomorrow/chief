<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Feature\Media\Fakes;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Media\MediaType;
use Thinktomorrow\Chief\Modules\ModuleManager;
use Thinktomorrow\Chief\Fields\Types\ImageField;

class ImageFieldModuleManager extends ModuleManager
{
    public function fields(): Fields
    {
        return new Fields([
            ImageField::make(MediaType::HERO)
        ]);
    }
}
