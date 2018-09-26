<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management\Fakes;

use Thinktomorrow\Chief\Common\Fields\InputField;

class ManagerWithValidationFake extends ManagerFake
{
    public function fields(): array
    {
        return [
            InputField::make('title')->validation(['required']),
            InputField::make('custom')->validation('required', ['custom.required' => 'custom error for :attribute'], ['custom' => 'custom attribute']),
            InputField::make('title_trans')->validation(['trans.*.title_trans' => 'required'])->translatable(true),
            InputField::make('content_trans')->translatable(true),
        ];
    }
}