<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\Fields\Field;
use Thinktomorrow\Chief\Common\Fields\InputField;
use Thinktomorrow\Chief\Common\Fields\MediaField;
use Thinktomorrow\Chief\Management\AbstractManager;
use Thinktomorrow\Chief\Management\ManagedModelDetails;
use Thinktomorrow\Chief\Management\ManagementDefaults;
use Thinktomorrow\Chief\Management\ModelManager;
use Thinktomorrow\Chief\Management\ManagedModel;

class ManagerFake extends AbstractManager implements ModelManager
{
    public function manage(ManagedModel $model = null): ModelManager
    {
        if(is_null($model)) {
            $model = new ManagedModelFake();
        }

        $this->model = $model;

        return $this;
    }

    public static function findById($id): ?ModelManager
    {
        return app(static::class)->manage(ManagedModelFake::where('id', $id)->first());
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