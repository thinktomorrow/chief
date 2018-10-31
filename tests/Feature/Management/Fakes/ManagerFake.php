<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\Types\DocumentField;
use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Fields\Types\MediaField;
use Thinktomorrow\Chief\Management\AbstractManager;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Management\ManagementDefaults;
use Thinktomorrow\Chief\Management\ModelManager;
use Thinktomorrow\Chief\Management\ManagedModel;

class ManagerFake extends AbstractManager implements ModelManager
{
    public function fields(): Fields
    {
        return new Fields([
            InputField::make('title'),
            InputField::make('custom'),
            InputField::make('title_trans')->translatable(['nl', 'fr']),
            InputField::make('content_trans')->translatable(['nl', 'fr']),
            MediaField::make('avatar'),
            DocumentField::make('doc'),
        ]);
    }

    public function setCustomField(Field $field, Request $request)
    {
        $this->model->custom_column = $request->get($field->key());
    }
}