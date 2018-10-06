<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Common\Fields\Field;
use Thinktomorrow\Chief\Common\Fields\InputField;
use Thinktomorrow\Chief\Common\Fields\MediaField;
use Thinktomorrow\Chief\Management\AbstractManager;
use Thinktomorrow\Chief\Management\ManagementDefaults;
use Thinktomorrow\Chief\Management\ModelManager;
use Thinktomorrow\Chief\Management\ManagedModel;

class ManagerFake extends AbstractManager implements ModelManager
{
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
}