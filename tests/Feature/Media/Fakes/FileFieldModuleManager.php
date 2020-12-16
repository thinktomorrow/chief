<?php declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Feature\Media\Fakes;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Modules\ModuleManager;
use Thinktomorrow\Chief\Fields\Types\FileField;

class FileFieldModuleManager extends ModuleManager
{
    public function fields(): Fields
    {
        return new Fields([
            FileField::make('fake-file')
        ]);
    }
}
