<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\Fields\Field;
use Thinktomorrow\Chief\Common\Fields\InputField;
use Thinktomorrow\Chief\Common\Fields\MediaField;
use Thinktomorrow\Chief\Management\ManagedModelDetails;
use Thinktomorrow\Chief\Management\ManagementDefaults;
use Thinktomorrow\Chief\Management\ModelManager;
use Thinktomorrow\Chief\Management\Register;

class ManagerFake implements ModelManager
{
    use ManagementDefaults{
        __construct as __baseConstruct;
    }

    public function __construct(Register $register)
    {
        $this->model = new ManagedModel();

        $this->__baseConstruct($register);
    }

    public function manage(ManagedModel $model): ModelManager
    {
        $this->model = $model;

        return $this;
    }

    public static function findById($id): ?ModelManager
    {
        return app(static::class)->manage(ManagedModel::where('id', $id)->first());
    }

    public static function findAllManaged(): Collection
    {
        $models = ManagedModel::all();

        return $models->map(function($model){
            return app(static::class)->manage($model);
        });
    }

    public function fields(): array
    {
        return [
            InputField::make('title'),
            InputField::make('custom'),
            InputField::make('title_trans')->translatable(true),
            InputField::make('content_trans')->translatable(true),
            MediaField::make('avatar'),
        ];
    }

    public function setCustomField(Field $field, Request $request)
    {
        $this->model->custom_column = $request->get($field->key());
    }

    /**
     * Information regarding a specific managed model instance.
     *
     * @return ManagedModelDetails
     */
    public function managedModelDetails(): ManagedModelDetails
    {
        return new ManagedModelDetails($this->model->title ?? '', '', '', ['nl','en']);
    }
}