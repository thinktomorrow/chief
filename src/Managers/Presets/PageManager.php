<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Presets;

use Thinktomorrow\Chief\Fragments\Assistants\FragmentsOwningAssistant;
use Thinktomorrow\Chief\ManagedModels\States\State\StateAssistant;
use Thinktomorrow\Chief\Managers\Assistants\ArchiveAssistant;
use Thinktomorrow\Chief\Managers\Assistants\CrudAssistant;
use Thinktomorrow\Chief\Managers\Assistants\DuplicateAssistant;
use Thinktomorrow\Chief\Managers\Assistants\FormsAssistant;
use Thinktomorrow\Chief\Managers\Assistants\LinkAssistant;
use Thinktomorrow\Chief\Managers\Assistants\ManagerDefaults;
use Thinktomorrow\Chief\Managers\Assistants\PreviewAssistant;
use Thinktomorrow\Chief\Managers\Assistants\RepeatFieldAssistant;
use Thinktomorrow\Chief\Managers\Assistants\SortAssistant;
use Thinktomorrow\Chief\Managers\Manager;

final class PageManager implements Manager
{
    use ArchiveAssistant;
    use CrudAssistant;
    use DuplicateAssistant;
    use FormsAssistant;
    use FragmentsOwningAssistant;
    use LinkAssistant;
    use ManagerDefaults;
    use PreviewAssistant;
    use RepeatFieldAssistant;
    use SortAssistant;
    use StateAssistant;
}
