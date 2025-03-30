<?php

namespace Thinktomorrow\Chief\Forms\App\Actions;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Events\FormUpdated;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Layouts\Layout;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class UpdateForm
{
    private FieldValidator $validator;

    public function __construct(FieldValidator $validator)
    {
        $this->validator = $validator;
    }

    public function handle(ModelReference $modelReference, string $formId, array $data, array $files)
    {
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
