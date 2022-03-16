<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Forms\Concerns\HasPosition;
use Thinktomorrow\Chief\Forms\Concerns\HasElementId;
use Thinktomorrow\Chief\Forms\Concerns\HasLayout;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasModel;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Layouts\Component;
use Thinktomorrow\Chief\Managers\Manager;

class Form extends Component
{
    use HasModel;
    use HasFields;
    use HasElementId;
    use HasLayout;
    use HasPosition;

    protected string $action;
    protected string $actionMethod;

    protected string $windowAction;
    protected ?string $refreshUrl = null;

    protected string $view = 'chief-form::templates.form';
    protected string $windowView = 'chief-form::templates.form-in-window';

    /**
     * Form component has a fixed window container and a customizable view inside of it.
     *
     * The optional custom view via editInSidebar(<custom view>) will always be rendered
     * inside this fixed container view. This way the outer edit logic remains intact.
     */
    protected string $windowContainerView = 'chief-form::templates.form-in-window-container';

    public function __construct(string $id)
    {
        parent::__construct($id);

        $this->elementId($id.'_'.Str::random());
    }

    public function getView(): string
    {
        return $this->editInSidebar
            ? $this->windowContainerView
            : $this->view;
    }

    public function getWindowView(): string
    {
        return $this->windowView;
    }

    public function editInSidebar(?string $windowView = null, ?string $windowContainerView = null): static
    {
        parent::editInSidebar($windowView);

        if ($windowContainerView) {
            $this->windowContainerView = $windowContainerView;
        }

        return $this;
    }

    public function action(string $action, string $method = 'POST'): static
    {
        $this->action = $action;
        $this->actionMethod = $method;

        return $this;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getActionMethod(): string
    {
        return $this->actionMethod;
    }

    public function windowAction(string $windowAction): static
    {
        $this->windowAction = $windowAction;

        return $this;
    }

    public function getWindowAction(): string
    {
        return $this->windowAction;
    }

    public function refreshUrl(string $refreshUrl): static
    {
        $this->refreshUrl = $refreshUrl;

        return $this;
    }

    public function getRefreshUrl(): ?string
    {
        return $this->refreshUrl;
    }

    public function fillModel(Model $model): self
    {
        return $this->recursiveEach(function ($component) use ($model) {
            if ($component instanceof Field || $component instanceof Form) {
                $component->model($model);
            }
        });
    }

    /**
     * Default form composition with model prefills and action urls.
     * TODO: unify this for fragment routes as well.
     */
    public function fill(Manager $manager, Model $model): self
    {
        $this->fillFields($manager, $model);

        return $this->fillModel($model)
            ->action($manager->route('form-update', $model, $this->getId()), 'PUT')
            ->windowAction($manager->route('form-edit', $model, $this->getId()))
            ->refreshUrl($manager->route('form-show', $model, $this->getId()))
        ;
    }

    /**
     * Allow any of the fields to consume the active
     * manager and model for their setup.
     *
     * @return $this
     */
    public function fillFields(Manager $manager, Model $model): self
    {
        $this->recursiveEach(function ($component) use ($manager, $model) {
            if ($component instanceof Field) {
                $component->fill($manager, $model);
            }
        });

        return $this;
    }

    private function recursiveEach(callable $logic, array $children = [], $level = 0): self
    {
        // recursively fill in all components with the model reference
        if (count($children) > 0) {
            foreach ($children as $childComponent) {
                call_user_func($logic, $childComponent);

                $this->recursiveEach($logic, $childComponent->getComponents(), ++$level);
            }
        }

        if (0 == $level) {
            call_user_func($logic, $this);

            $this->recursiveEach($logic, $this->getComponents(), ++$level);
        }

        return $this;
    }
}
