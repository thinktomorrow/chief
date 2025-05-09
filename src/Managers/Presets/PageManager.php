<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Presets;

use Thinktomorrow\Chief\Managers\Assistants\ArchiveAssistant;
use Thinktomorrow\Chief\Managers\Assistants\CrudAssistant;
use Thinktomorrow\Chief\Managers\Assistants\DuplicateAssistant;
use Thinktomorrow\Chief\Managers\Assistants\ManagerDefaults;
use Thinktomorrow\Chief\Managers\Assistants\SortAssistant;
use Thinktomorrow\Chief\Managers\Manager;

final class PageManager implements Manager
{
    use ArchiveAssistant;
    use CrudAssistant;
    use DuplicateAssistant;
    use ManagerDefaults;
    use SortAssistant;
}
