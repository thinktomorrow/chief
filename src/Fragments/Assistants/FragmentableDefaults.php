<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Assistants;

use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\ManagedModels\Assistants\ManagedModelDefaults;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

trait FragmentableDefaults
{
    use ManagedModelDefaults;
    use Viewable;

    private FragmentModel $fragmentModel;

    public function modelReference(): ModelReference
    {
        return ModelReference::fromStatic(static::class);
    }

    public function renderAdminFragment($owner, $loop, $viewData = []): string
    {
        return $this->renderFragment($owner, $loop, $viewData);
    }

    public function renderFragment($owner, $loop, $viewData = []): string
    {
        $this->setOwnerViewPath($owner);

        $this->setViewData(array_merge($viewData, [
            'owner' => $owner,
            'loop' => $loop,
            'model' => $this,
        ]));

        return $this->renderView();
    }

    public function adminView(): View
    {
        return view('chief::manager.windows.fragments.edit');
    }

    public function setFragmentModel(FragmentModel $fragmentModel): Fragmentable
    {
        $this->fragmentModel = $fragmentModel;

        return $this;
    }

    public function fragmentModel(): FragmentModel
    {
        if (! isset($this->fragmentModel)) {
            return new FragmentModel();
        }

        return $this->fragmentModel;
    }
}
