<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Assistants\ManagerDefaults;
use Thinktomorrow\Chief\Managers\Assistants\FragmentAssistant;
use Thinktomorrow\Chief\Fragments\Actions\CreateFragmentModel;
use Thinktomorrow\Chief\Managers\Assistants\FileUploadAssistant;
use Thinktomorrow\Chief\Managers\Assistants\SlimImageUploadAssistant;

final class StaticFragmentManager implements Manager
{
    use ManagerDefaults;
    use FragmentAssistant;
    use FileUploadAssistant;
    use SlimImageUploadAssistant;

    private function storeFragmentable(FragmentsOwner $owner, Fragmentable $fragmentable, Request $request): void
    {
        $fragmentable->setFragmentModel(
            app(CreateFragmentModel::class)->create($owner, $fragmentable, $request->order)
        );

        $fragmentable->fragmentModel()->saveFields($fragmentable->fields()->notTagged('edit'), $request->all(), $request->allFiles());
    }

    private function fragmentModel(Fragmentable $fragmentable)
    {
        return $fragmentable->fragmentModel();
    }

    private function fieldsModel($id)
    {
        return $this->fragmentRepository->find($id);
    }
}
