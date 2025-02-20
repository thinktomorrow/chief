<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Presets;

use Thinktomorrow\Chief\ManagedModels\States\State\StateAssistant;
use Thinktomorrow\Chief\Managers\Assistants\ArchiveAssistant;
use Thinktomorrow\Chief\Managers\Assistants\CrudAssistant;
use Thinktomorrow\Chief\Managers\Assistants\FormsAssistant;
use Thinktomorrow\Chief\Managers\Assistants\ManagerDefaults;
use Thinktomorrow\Chief\Managers\Assistants\SortAssistant;
use Thinktomorrow\Chief\Managers\Manager;

final class ModelManager implements Manager
{
    use ArchiveAssistant;
    use CrudAssistant;
    use FormsAssistant;
    use ManagerDefaults;
    use SortAssistant;
    use StateAssistant;
}
