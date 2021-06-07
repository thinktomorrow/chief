<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Presets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fragments\Database;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\Actions\CreateFragmentModel;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentAssistant;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentsOwningAssistant;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
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

    private function fieldsModel($id)
    {
        return $this->fragmentRepository->find((int) $id);
    }
}
