<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Presets;

use Thinktomorrow\Chief\Managers\Assistants\ArchiveAssistant;
use Thinktomorrow\Chief\Managers\Assistants\CrudAssistant;
use Thinktomorrow\Chief\Managers\Assistants\FieldsComponentAssistant;
use Thinktomorrow\Chief\Managers\Assistants\FileUploadAssistant;
use Thinktomorrow\Chief\Managers\Assistants\ManagerDefaults;
use Thinktomorrow\Chief\Managers\Assistants\PublishAssistant;
use Thinktomorrow\Chief\Managers\Assistants\RedactorFileUploadAssistant;
use Thinktomorrow\Chief\Managers\Assistants\SlimImageUploadAssistant;
use Thinktomorrow\Chief\Managers\Assistants\SortAssistant;
use Thinktomorrow\Chief\Managers\Assistants\StatusAssistant;
use Thinktomorrow\Chief\Managers\Manager;

final class ModelManager implements Manager
{
    use ManagerDefaults;
    use CrudAssistant;
    use StatusAssistant;
    use FieldsComponentAssistant;

    use ArchiveAssistant;
    use PublishAssistant;
    use SortAssistant;

    use SlimImageUploadAssistant;
    use FileUploadAssistant;
    use RedactorFileUploadAssistant;
}
