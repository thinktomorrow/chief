<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Managers\DiscoverTraitMethods;
use Thinktomorrow\Chief\Managers\Exceptions\NotAllowedManagerAction;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Resource\Resource;

trait ManagerDefaults
{
    private Resource $resource;

    private FragmentRepository $fragmentRepository;

    private FieldValidator $fieldValidator;

    private Registry $registry;

    public function __construct(PageResource $resource, FragmentRepository $fragmentRepository, FieldValidator $fieldValidator, Registry $registry)
    {
        $this->resource = $resource;
        $this->fragmentRepository = $fragmentRepository;
        $this->fieldValidator = $fieldValidator;
        $this->registry = $registry;
    }

    public function route(string $action, $model = null, ...$parameters): string
    {
        foreach (DiscoverTraitMethods::belongingTo(static::class, 'route') as $method) {
            if (null !== ($route = $this->$method($action, $model, ...$parameters))) {
                return $route;
            }
        }

        return $this->generateRoute($action, $model, ...$parameters);
    }

    public function can(string $action, $model = null): bool
    {
        foreach (DiscoverTraitMethods::belongingTo(static::class, 'can') as $method) {
            if ($this->$method($action, $model) === true) {
                return true;
            }
        }

        return false;
    }

    protected function generateRoute(string $action, $model = null, ...$parameters): string
    {
        if ($model) {
            $modelId = (is_object($model) && isset($model->{$model->getKeyName()})) ? $model->{$model->getKeyName()} : $model;

            $parameters = array_merge((array) $modelId, $parameters);
        }

        return route('chief.'.$this->resource::resourceKey().'.'.$action, $parameters);
    }

    protected function guard(string $action, $model = null)
    {
        if (! $this->can($action, $model)) {
            throw NotAllowedManagerAction::notAllowedAction($action, $this->resource::resourceKey());
        }
    }

    /**
     * The authorize method provides a check against the current admin permissions.
     *
     * @param  string  $action
     *
     * @throws NotAllowedManagerAction
     */
    private function authorize(string $permission): void
    {
        if (! chiefAdmin() || ! chiefAdmin()->hasPermissionTo($permission)) {
            throw NotAllowedManagerAction::notAllowedPermission($permission, get_class($this));
        }
    }

    public function managedModelClass(): string
    {
        return $this->resource::modelClassName();
    }

    private function managedModelClassInstance(...$attributes)
    {
        $modelClass = $this->managedModelClass();

        if (! empty($attributes)) {
            return new $modelClass(...$attributes);
        }

        return app()->make($modelClass);
    }

    /**
     * Which model contains the fields.
     */
    protected function fieldsModel($id)
    {
        return $this->managedModelClass()::withoutGlobalScopes()->findOrFail($id);
    }

    protected function fieldValidator(): FieldValidator
    {
        return $this->fieldValidator;
    }
}
