<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

use Thinktomorrow\Chief\Forms\Concerns\HasFields;
use Thinktomorrow\Chief\Forms\Layouts\Concerns\HasFormDisplay;

class Form extends Component
{
    use HasFields;
    use HasFormDisplay;

    protected string $view = 'chief-form::layouts.form';

    public function __construct(string $key)
    {
        parent::__construct($key);

        $this->position('main');
        $this->tag($key);
    }

    protected function wireableMethods(array $components): array
    {
        return array_merge(parent::wireableMethods($components), [
            ...(isset($this->formDisplay) ? ['setFormDisplay' => $this->formDisplay] : []),
        ]);
    }

    //    public function getpreviewView(): string
    //    {
    //        return $this->previewView;
    //    }
    //
    //    public function editInSidebar(?string $previewView = null, ?string $windowContainerView = null): static
    //    {
    //        parent::editInSidebar($previewView);
    //
    //        if ($windowContainerView) {
    //            $this->windowContainerView = $windowContainerView;
    //        }
    //
    //        return $this;
    //    }

    //    public function fillModel(Model $model): self
    //    {
    //        if (! $this instanceof HasComponents) {
    //            return [];
    //        }
    //
    //        $converted = [];
    //
    //        foreach ($this->getComponents() as $component) {
    //            $component->model($model);
    //            $converted[] = $component->toLivewire();
    //        }
    //
    //        return $converted;
    //    }

    /**
     * Default form composition with model prefills and action urls.
     * TODO: unify this for fragment routes as well.
     */
    //    public function fill(Manager $manager, Model $model): self
    //    {
    //        $this->fillFields($manager, $model);
    //
    //        return $this->fillModel($model)
    //            ->action($manager->route('form-update', $model, $this->getId()), 'PUT')
    //            ->windowAction($manager->route('form-edit', $model, $this->getId()))
    //            ->refreshUrl($manager->route('form-show', $model, $this->getId()));
    //    }

    /**
     * Allow any of the fields to consume the active
     * manager and model for their setup.
     *
     * @return $this
     */
    //    public function fillFields(Manager $manager, Model $model): self
    //    {
    //        $this->recursiveEach(function ($component) use ($manager, $model) {
    //            if ($component instanceof Field) {
    //                $component->fill($manager, $model);
    //            }
    //        });
    //
    //        return $this;
    //    }

    //    private function recursiveEach(callable $logic, array $children = [], $level = 0): self
    //    {
    //        // recursively fill in all components with the model reference
    //        if (count($children) > 0) {
    //            foreach ($children as $childComponent) {
    //                call_user_func($logic, $childComponent);
    //
    //                // Temp fix for nested components while also allowing readonly / layout elements on the page.
    //                if (method_exists($childComponent, 'getComponents')) {
    //                    $this->recursiveEach($logic, $childComponent->getComponents(), ++$level);
    //                }
    //            }
    //        }
    //
    //        if ($level == 0) {
    //            call_user_func($logic, $this);
    //
    //            $this->recursiveEach($logic, $this->getComponents(), ++$level);
    //        }
    //
    //        return $this;
    //    }
}
