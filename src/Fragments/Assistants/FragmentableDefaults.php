<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Assistants;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Fragments\Domain\Exceptions\MissingFragmentModelException;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Fragment;
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

    private ?FragmentModel $fragmentModel = null;

    public function viewKey(): string
    {
        $key = (new ResourceKeyFormat(static::class))->getKey();

        return Str::of($key)->remove('_fragment')->trim()->__toString();
    }

    public function renderAdminFragment($owner, $loop, $viewData = []): string
    {
        return $this->renderFragment($owner, $loop, $viewData);
    }

    public function render(): View
    {
        $view = 'view';

        return view($view, array_merge($this->data(), [
            'component' => $this,
            'fragment' => $this,

            /** @deprecated use $fragment instead */
            'model' => $this,
        ]));
    }

    public function renderFragment($owner, $loop, $viewData = []): string
    {
        //$this->setOwnerViewPath($owner);

        $this->setViewData(array_merge($viewData, [
            'fragment' => $this,

            /** @deprecated use $fragment instead */
            'model' => $this,
        ]));

        return $this->renderView();
    }

    public function hasFragmentModel(): bool
    {
        return isset($this->fragmentModel);
    }

    public function setFragmentModel(FragmentModel $fragmentModel): Fragment
    {
        $this->fragmentModel = $fragmentModel;

        $this->fragmentModel->setDynamicLocaleFallback($this->dynamicLocaleFallback());

        return $this;
    }

    public function fragmentModel(): FragmentModel
    {
        if (! isset($this->fragmentModel)) {
            throw new MissingFragmentModelException('Fragment model is not set. Make sure to set the fragment model before accessing it.');
        }

        return $this->fragmentModel;
    }

    public function modelReference(): ModelReference
    {
        return $this->fragmentModel()->modelReference();
    }

    public function getFragmentId(): string
    {
        return $this->fragmentModel()->id;
    }

    public function dynamicLocaleFallback(): ?string
    {
        return null;
    }
}
