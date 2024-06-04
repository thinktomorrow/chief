<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Fragments\App\ActiveContext\FragmentCollection;
use Thinktomorrow\Chief\Fragments\Exceptions\MissingFragmentModelException;
use Thinktomorrow\Chief\Fragments\Models\ForwardFragmentProperties;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Resource\ResourceKeyFormat;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Vine\NodeDefaults;

abstract class BaseFragment extends \Illuminate\View\Component implements Fragment
{
    use \Thinktomorrow\Chief\Resource\FragmentResourceDefault;
    use \Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
    use ForwardFragmentProperties;
    use NodeDefaults;

    public function __construct()
    {
        $this->children = new FragmentCollection();
    }

    protected ?FragmentModel $fragmentModel = null;

    public function render(): View
    {
        $this->attributes = $this->attributes ?: $this->newAttributeBag();

        return view($this->viewPath(), [
            'attributes' => $this->attributes,
            'fragment' => $this,
            'section' => $this->getRootNode(),

            /** @deprecated use $fragment instead */
            'model' => $this,
        ]);
    }

    public function toHtml(): string
    {
        return $this->render()->render();
    }

    protected function viewPath(): string
    {
        if (property_exists($this, 'viewPath') && isset($this->viewPath)) {
            return $this->viewPath;
        }

        return config('chief.fragment_viewpath', 'fragments') . $this->viewKey();
    }

    protected function viewKey(): string
    {
        $key = (new ResourceKeyFormat(static::class))->getKey();

        return Str::of($key)->remove('_fragment')->trim()->__toString();
    }

    // TODO: adjust for new component rendering... (remove parameters, return view instead of string)
    public function renderAdminFragment($owner, $loop, $viewData = [])
    {
        return $this->render()->render();
    }

    // TODO: adjust for new component rendering... (remove parameters, return view instead of string)
    public function renderFragment($owner, $loop, $viewData = []): string
    {
        return $this->render()->render();
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

    /**
     * Convenience method to get all child fragments.
     * Ideal for usage in the views
     */
    public function getFragments(): FragmentCollection
    {
        return $this->getChildNodes();
    }

    public function dynamicLocaleFallback(): ?string
    {
        return null;
    }
}
