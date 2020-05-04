<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Fields\Types\FileField;
use Thinktomorrow\Chief\Fields\Types\TextField;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Fields\Types\ImageField;
use Thinktomorrow\Chief\Fragments\FragmentField;
use Thinktomorrow\Chief\Management\AbstractManager;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Management\Manager;

class ManagerFake extends AbstractManager implements Manager
{
    public function fields(): Fields
    {
        return parent::fields()->add(
            InputField::make('title'),
            InputField::make('custom'),
            InputField::make('title_trans')->translatable(['nl', 'fr']),
            InputField::make('content_trans')->translatable(['nl', 'fr']),
            TextField::make('dynamic_column'),
            FragmentField::make('fragment-field', new Fields([
                InputField::make('title'),
                TextField::make('content')
            ])),
            ImageField::make('avatar'),
            FileField::make('hero')
        );
    }

    public function setCustomField(Field $field, Request $request)
    {
        $this->model->custom_column = $request->get($field->getKey());
    }
}
