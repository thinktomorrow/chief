<?php

namespace Thinktomorrow\Chief\Concerns\Viewable;

use Thinktomorrow\Chief\Concerns\Morphable\MorphableContract;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Relations\ActsAsParent;
use Thinktomorrow\Chief\Sets\Set;
use Thinktomorrow\Chief\Management\ManagedModel;

trait Viewable
{
    protected $viewParent;

    public function renderView(): string
    {
        $renderedView = $this->renderRawView();

        // In case of snippets, we inject these after the initial view rendering
        if (method_exists($this, 'shouldParseWithSnippets') && $this->shouldParseWithSnippets($renderedView)) {
            $renderedView = $this->parseWithSnippets($renderedView);
        }

        return $renderedView;
    }

    private function renderRawView(): string
    {
        try {
            return view($this->viewPath(), $this->viewData())->render();
        } catch (NotFoundView $e) {
            if (config('thinktomorrow.chief.strict')) {
                throw $e;
            }
        }

        // If no view has been created for this model, we try once again to fetch the content value if any. This will silently fail
        // if no content value is present. We consider the 'content' attribute to be a default for our copy.
        return isset($this->content) ? (string) $this->content : '';
    }

    public function setViewParent(ActsAsParent $parent): ViewableContract
    {
        $this->viewParent = $parent;

        return $this;
    }

    /**
     * This is the model's view identifier. This key is used to determine the full view
     * path of the model. By default this is based on the morphKey value of the model.
     *
     * @return string
     * @throws NotFoundViewKey
     */
    public function viewKey(): string
    {
        if (property_exists($this, 'viewKey') && isset($this->viewKey)) {
            return $this->viewKey;
        }

        if ($this instanceof ManagedModel) {
            return $this->managedModelKey();
        }

        if (config('thinktomorrow.chief.strict')) {
            throw new NotFoundViewKey('Missing view key. Please add a [viewKey] property to ' . get_class($this));
        };

        return '';
    }

    /**
     * This is the full path reference for this model's view file. This is relative to your main view folder
     * e.g. key 'articles.show'. A sensible default is set and determined based on the viewKey value.
     * But you are free to override this and change it to another value to fit your own logic.
     *
     * @return string
     * @throws NotFoundView
     */
    protected function viewPath(): string
    {
        return ViewPath::make($this, $this->viewParent, $this->baseViewPath ?? null)->get();
    }

    /**
     * @return array
     */
    private function viewData(): array
    {
        $viewData = [
            'model'  => $this,
            'parent' => $this->viewParent,
        ];

        /** @deprecated since 0.3 in favor of generic 'model' variable */
        if ($this instanceof Page) {
            $viewData['page'] = $this;
        }
        if ($this instanceof Module) {
            $viewData['module'] = $this;
        }
        if ($this instanceof Set) {
            $viewData['collection'] = $this;
            $viewData['pages'] = $this;
        }

        return $viewData;
    }
}
