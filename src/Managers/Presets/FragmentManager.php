<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Presets;

use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Assistants\ManagerDefaults;
use Thinktomorrow\Chief\Managers\Assistants\ArchiveAssistant;
use Thinktomorrow\Chief\Managers\Assistants\PublishAssistant;
use Thinktomorrow\Chief\Managers\Assistants\FragmentAssistant;
use Thinktomorrow\Chief\Managers\Assistants\FileUploadAssistant;
use Thinktomorrow\Chief\Managers\Assistants\SlimImageUploadAssistant;
use Thinktomorrow\Chief\Managers\Assistants\RedactorFileUploadAssistant;

final class FragmentManager implements Manager
{
    use ManagerDefaults;
    use FragmentAssistant;

    use SlimImageUploadAssistant;
    use FileUploadAssistant;
    use RedactorFileUploadAssistant;
}
