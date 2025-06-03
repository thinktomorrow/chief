<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Thinktomorrow\Chief\Fragments\Exceptions\MissingFragmentModelException;
use Thinktomorrow\Chief\Fragments\Models\ForwardFragmentProperties;
use Thinktomorrow\Chief\Fragments\Models\FragmentCollection;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Resource\FragmentResourceDefault;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\Vine\NodeDefaults;

abstract class BaseFragment extends Component implements Fragment
{
    use ForwardFragmentProperties;
    use FragmentResourceDefault;
    use NodeDefaults;
    use ReferableModelDefault;

    protected array $withViewData = [];

    public function __construct()
    {
        $this->children = new FragmentCollection;
    }

    protected ?FragmentModel $fragmentModel = null;

    public function render(): View
    {
        return view($this->viewPath(), $this->viewData());
    }

    public function renderInAdmin(): View
    {
        return view($this->adminViewPath(), $this->viewData());
    }

    public function toHtml(): string
    {
        return $this->render()->render();
    }

    protected function viewData(): array
    {
        $this->attributes = $this->attributes ?: $this->newAttributeBag();

        return array_merge([
            'attributes' => $this->attributes,
            'fragment' => $this,
            'rootFragment' => $this->getRootNode(),

            /** @deprecated use $fragment instead */
            'model' => $this,
        ], $this->withViewData);
    }

    public function with(array $viewData): static
    {
        $this->withViewData = array_merge($this->withViewData, $viewData);

        return $this;
    }

    protected function viewPath(): string
    {
        if (property_exists($this, 'viewPath') && isset($this->viewPath)) {
            return $this->viewPath;
        }

        if (strpos($this->viewKey(), '::')) {
            return $this->viewKey();
        }

        return config('chief.fragments.view_path', 'fragments').'.'.$this->viewKey();
    }

    protected function adminViewPath(): string
    {
        if (property_exists($this, 'adminViewPath') && isset($this->adminViewPath)) {

            return $this->adminViewPath;
        }

        return config('chief.fragments.admin_view_path', 'back.fragments').'.'.$this->viewKey();
    }

    protected function viewKey(): string
    {
        return FragmentKey::fromClass(static::class)->getKey();
    }

    public function hasFragmentModel(): bool
    {
        return isset($this->fragmentModel);
    }

    public function setFragmentModel(FragmentModel $fragmentModel): Fragment
    {
        $this->fragmentModel = $fragmentModel;

        return $this;
    }

    public function getFragmentModel(): FragmentModel
    {
        if (! isset($this->fragmentModel)) {
            throw new MissingFragmentModelException('Fragment model is not set. Make sure to set the fragment model before accessing it.');
        }

        return $this->fragmentModel;
    }

    public function modelReference(): ModelReference
    {
        return $this->getFragmentModel()->modelReference();
    }

    public function getFragmentId(): string
    {
        return $this->getFragmentModel()->id;
    }

    /**
     * Convenience method to get all child fragments.
     * Ideal for usage in the views
     */
    public function getFragments(): FragmentCollection
    {
        return $this->getChildNodes();
    }

    /**
     * @deprecated use getFragmentModel() instead
     */
    public function fragmentModel(): FragmentModel
    {
        return $this->getFragmentModel();
    }

    public function isOffline(): bool
    {
        return ! $this->isOnline();
    }

    public function isOnline(): bool
    {
        return $this->getFragmentModel()->isOnline();
    }

    public function isShared(): bool
    {
        return $this->getFragmentModel()->isShared();
    }

    public function getBookmark(): string
    {
        return $this->getFragmentModel()->getBookmark() ?? '';
    }
}
