<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Viewable;

use Thinktomorrow\Chief\ManagedModels\ManagedModel;

trait Viewable
{
    private array $viewData = [];
    private ?string $ownerViewPath = null;

    public function renderView(): string
    {
        try {
            return view($this->viewPath(), $this->viewData())->render();
        } catch (NotFoundView $e) {
            if (config('chief.strict')) {
                throw $e;
            }
        }

        return '<!-- no view found for model ['.static::class.'] -->';
    }

    /**
     * This is the model's view identifier. This key is used to determine the full view
     * path of the model. By default this is based on the managedModelKey of the model.
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
            return static::managedModelKey();
        }

        if (config('chief.strict')) {
            throw new NotFoundViewKey('Missing view key. Please add a [viewKey] property to ' . static::class);
        }

        return '';
    }

    public function setViewData(array $viewData): void
    {
        $this->viewData = array_merge($this->viewData, $viewData);
    }

    public function setOwnerViewPath($owner): void
    {
        $ownerViewPath = ($owner instanceof ViewableContract)
            ? $owner->viewKey()
            : $owner;

        $this->ownerViewPath = $ownerViewPath;
    }

    /**
     * This is the full path reference for this model's view file. This is relative to your main view folder
     * e.g. key 'articles.show'. A sensible default is set and determined based on the viewKey value.
     * But you are free to override this and change it to another value to fit your own logic.
     *
     * @return string
     * @throws NotFoundView
     */
    private function viewPath(): string
    {
        if (property_exists($this, 'viewPath') && isset($this->viewPath)) {
            return $this->viewPath;
        }

        return ViewPath::make($this->viewKey(), $this->baseViewPath(), $this->ownerViewPath())->get();
    }

    /**
     * @return array
     */
    private function viewData(): array
    {
        return array_merge([
            'model' => $this,
        ],$this->viewData);
    }

    private function baseViewPath(): ?string
    {
        if (property_exists($this, 'baseViewPath') && isset($this->baseViewPath)) {
            return $this->baseViewPath;
        }

        return null;
    }

    private function ownerViewPath(): ?string
    {
        return $this->ownerViewPath;
    }
}
