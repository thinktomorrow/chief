<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Managers\DiscoverTraitMethods;
use Thinktomorrow\Chief\Managers\Exceptions\NotAllowedManagerAction;
use Thinktomorrow\Chief\Managers\Register\Registry;

trait ManagerDefaults
{
    private string $managedModelClass;
    private FragmentRepository $fragmentRepository;
    private FieldValidator $fieldValidator;
    private Registry $registry;

    public function __construct(string $managedModelClass, FragmentRepository $fragmentRepository, FieldValidator $fieldValidator, Registry $registry)
    {
        $this->managedModelClass = $managedModelClass;
        $this->fragmentRepository = $fragmentRepository;
        $this->fieldValidator = $fieldValidator;
        $this->registry = $registry;
    }

    public function managedModelClass(): string
    {
        return $this->managedModelClass;
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
            if (true === $this->$method($action, $model)) {
                return true;
            }
        }

        return false;
    }

    private function generateRoute(string $action, $model = null, ...$parameters): string
    {
        if ($model) {
            $modelId = (is_object($model) && isset($model->id)) ? $model->id : $model;

            $parameters = array_merge((array)$modelId, $parameters);
        }

        return route('chief.' . $this->managedModelClass()::managedModelKey() . '.' . $action, $parameters);
    }

    /**
     * @return void
     */
    private function guard(string $action, $model = null)
    {
        if (! $this->can($action, $model)) {
            throw NotAllowedManagerAction::notAllowedAction($action, $this->managedModelClass()::managedModelKey());
        }
    }

    /**
     * The authorize method provides a check against the current admin permissions.
     *
     * @param string $action
     * @throws NotAllowedManagerAction
     */
    private function authorize(string $permission): void
    {
        if (! chiefAdmin() || ! chiefAdmin()->hasPermissionTo($permission)) {
            throw NotAllowedManagerAction::notAllowedPermission($permission, get_class($this));
        }
    }

    /**
     * Which model contains the fields.
     */
    private function fieldsModel($id)
    {
        return $this->managedModelClass()::withoutGlobalScopes()->findOrFail($id);
    }

    private function fieldValidator(): FieldValidator
    {
        return $this->fieldValidator;
    }
}
