<?php

namespace Thinktomorrow\Chief\Forms\App\Actions;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Events\FormUpdated;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Layouts\Layout;
use Thinktomorrow\Chief\Fragments\UI\Livewire\_partials\WithNullifyEmptyValues;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class UpdateForm
{
    use WithNullifyEmptyValues;

    private FieldValidator $validator;

    public function __construct(FieldValidator $validator)
    {
        $this->validator = $validator;
    }

    public function handle(ModelReference $modelReference, string $formId, array $data, array $files)
    {
        /**
         * Nullify empty string values so that they are stored as null in the database and
         * not as empty strings. This is important for the fallback locale mechanism.
         */
        $data = $this->recursiveNullifyEmptyValues($data);

        $model = $modelReference->instance();
        $resource = app(Registry::class)->findResourceByModel($model::class);

        $fields = $this->getFields($resource, $model, $formId);

        $this->validator->handle($fields, $data);

        app($resource->getSaveFieldsClass())->save($model, $fields, $data, $files);

        event(new FormUpdated($modelReference, $formId));
    }

    private function getFields(Resource $resource, Model $model, string $formId): Fields
    {
        $layout = Layout::make($resource->fields($model));

        return $layout->findForm($formId)
            ->model($model)
            ->getFields();
    }
}
