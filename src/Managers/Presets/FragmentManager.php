<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Presets;

use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentAssistant;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentsOwningAssistant;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Managers\Assistants\FileUploadAssistant;
use Thinktomorrow\Chief\Managers\Assistants\ManagerDefaults;
use Thinktomorrow\Chief\Managers\Assistants\RepeatFieldAssistant;
use Thinktomorrow\Chief\Managers\Assistants\SlimImageUploadAssistant;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\Resource;

final class FragmentManager implements Manager
{
    use ManagerDefaults;
    use FragmentAssistant;
    use FragmentsOwningAssistant;
    use FileUploadAssistant;
    use SlimImageUploadAssistant;
    use RepeatFieldAssistant;

//    private Resource $resource;
    private FragmentRepository $fragmentRepository;
    private FieldValidator $fieldValidator;
    private Registry $registry;

    public function __construct(Resource $resource, FragmentRepository $fragmentRepository, FieldValidator $fieldValidator, Registry $registry)
    {
        $this->resource = $resource;
        $this->fragmentRepository = $fragmentRepository;
        $this->fieldValidator = $fieldValidator;
        $this->registry = $registry;
    }


    private function fieldsModel($id)
    {
        return $this->fragmentRepository->find((int) $id);
    }
}
