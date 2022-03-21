<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Assistants;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Resource\FragmentResourceDefault;
use Thinktomorrow\Chief\Resource\ResourceKeyFormat;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableStaticModelDefault;

trait FragmentableDefaults
{
    use FragmentResourceDefault;
    use ReferableStaticModelDefault;
    use Viewable;

    private FragmentModel $fragmentModel;

    public function modelReference(): ModelReference
    {
        return ModelReference::fromStatic(static::class);
    }

    public function viewKey(): string
    {
        $key = (new ResourceKeyFormat(static::class))->getKey();

        return Str::of($key)->remove('_fragment')->trim()->__toString();
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
