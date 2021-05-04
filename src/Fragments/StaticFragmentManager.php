<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fragments\Actions\CreateFragmentModel;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentAssistant;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentsOwningAssistant;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\Managers\Assistants\FileUploadAssistant;
use Thinktomorrow\Chief\Managers\Assistants\ManagerDefaults;
use Thinktomorrow\Chief\Managers\Assistants\SlimImageUploadAssistant;
use Thinktomorrow\Chief\Managers\Manager;

final class StaticFragmentManager implements Manager
{
    use ManagerDefaults;
    use FragmentAssistant;
    use FragmentsOwningAssistant;
    use FileUploadAssistant;
    use SlimImageUploadAssistant;

    private function storeFragmentable(Model $owner, Fragmentable $fragmentable, Request $request): void
    {
        $fragmentable->setFragmentModel(app(CreateFragmentModel::class)->create($owner, $fragmentable, $request->order));

        $fragmentable->fragmentModel()->saveFields(Fields::make($fragmentable->fields())->notTagged('edit'), $request->all(), $request->allFiles());
    }

    private function fragmentModel(Fragmentable $fragmentable): Database\FragmentModel
    {
        return $fragmentable->fragmentModel();
    }

    /**
     * @return Fragmentable
     */
    private function fieldsModel($id)
    {
        return $this->fragmentRepository->find($id);
    }
}
