<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Modules\Presets;

use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Assistants\CrudAssistant;
use Thinktomorrow\Chief\Managers\Assistants\ManagerDefaults;

class PagetitleModuleManager implements Manager
{
    use ManagerDefaults;
    use CrudAssistant;

    public function managedModelClass(): string
    {
        return PagetitleModule::class;
    }
}
