<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Presets;

use Thinktomorrow\Chief\Fragments\Assistants\FragmentAssistant;
use Thinktomorrow\Chief\Managers\Assistants\RepeatFieldAssistant;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentsOwningAssistant;
use Thinktomorrow\Chief\Managers\Assistants\FileUploadAssistant;
use Thinktomorrow\Chief\Managers\Assistants\ManagerDefaults;
use Thinktomorrow\Chief\Managers\Assistants\SlimImageUploadAssistant;
use Thinktomorrow\Chief\Managers\Manager;

final class FragmentManager implements Manager
{
    use ManagerDefaults;
    use FragmentAssistant;
    use FragmentsOwningAssistant;
    use FileUploadAssistant;
    use SlimImageUploadAssistant;
    use RepeatFieldAssistant;

    private function fieldsModel($id)
    {
        return $this->fragmentRepository->find((int) $id);
    }
}
