<?php

namespace Thinktomorrow\Chief\Models\App\Actions;

use Thinktomorrow\Chief\Forms\Events\FormUpdated;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Layouts\Layout;
use Thinktomorrow\Chief\Fragments\UI\Livewire\_partials\WithNullifyEmptyValues;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUpdated;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Sites\HasAllowedSites;

class ModelApplication
{
    use WithNullifyEmptyValues;

    private Registry $registry;

    private FieldValidator $fieldValidator;

    public function __construct(Registry $registry, FieldValidator $fieldValidator)
    {
        $this->registry = $registry;
        $this->fieldValidator = $fieldValidator;
    }

    public function create(CreateModel $command): string
    {
        $modelClass = $command->getModelClass();
        $resource = $this->getResource($command->getModelClass());

        /**
         * Nullify empty string values so that they are stored as null in the database and
         * not as empty strings. This is important for the fallback locale mechanism.
         */
        $input = $this->nullifyEmptyValues($command->getInput());

        // TODO: remove values for locales that are not set for the model
        $input = $resource->prepareInputOnCreate($input);

        $model = new $modelClass($resource->getAttributesOnCreate());

        $fields = Layout::make($resource->fields($model))
            ->model($model)
            ->setLocales($command->getLocales())
            ->getFields()
            ->filterByNotTagged(['edit', 'not-on-model-create', 'not-on-create']);

        $this->fieldValidator->handle($fields, $input);

        app($resource->getSaveFieldsClass())->save(
            $model,
            $fields,
            $input,
            $command->getFiles(),
        );

        if ($model instanceof HasAllowedSites) {
            $model->update(['allowed_sites' => $command->getLocales()]);
        }

        event(new ManagedModelCreated($model->modelReference()));

        return $model->fresh()->getKey();
    }

    public function updateForm(UpdateForm $command): void
    {
        $model = $command->getModelReference()->instance();
        $resource = $this->getResource($model::class);

        /**
         * Nullify empty string values so that they are stored as null in the database and
         * not as empty strings. This is important for the fallback locale mechanism.
         */
        $input = $this->nullifyEmptyValues($command->getInput());

        $fields = Layout::make($resource->fields($model))
            ->findForm($command->getFormId())
            ->model($model)
            ->setLocales($command->getLocales())
            ->getFields();

        $this->fieldValidator->handle($fields, $input);

        app($resource->getSaveFieldsClass())->save(
            $model,
            $fields,
            $input,
            $command->getFiles(),
        );

        event(new ManagedModelUpdated($model->modelReference()));

        event(new FormUpdated($model->modelReference(), $command->getFormId()));
    }

    private function getResource(string $modelClass): Resource
    {
        return $this->registry->findResourceByModel($modelClass);
    }
}
