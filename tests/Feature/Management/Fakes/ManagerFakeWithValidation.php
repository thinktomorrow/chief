<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Fields\Fields;

class ManagerFakeWithValidation extends ManagerFake
{
    public function fields(): Fields
    {
        return new Fields([
            InputField::make('title')->validation(['required']),
            InputField::make('custom')->validation('required', ['custom.required' => 'custom error for :attribute'], ['custom' => 'custom attribute']),
            InputField::make('title_trans')->validation('required')->translatable(['nl', 'en']),
            InputField::make('content_trans')->translatable(['nl', 'en']),
        ]);
    }
}
