<?php

namespace Thinktomorrow\Chief\Settings;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Fields\Types\InputField;

class SettingFields extends Fields
{
    public static function defaults(): self
    {
        return new static([
            InputField::make('client.app_name')
                ->label('Site naam')
                ->description('Naam van de applicatie. Dit wordt getoond in o.a. de mail communicatie.')
            ->translatable(['nl' => 'nl', 'fr' => 'fr']),
            InputField::make('contact.email')
                ->label('Webmaster email')
                ->description('Het emailadres van de webmaster. Hierop ontvang je standaard alle contactnames.'),
            InputField::make('contact.name')
                ->label('Webmaster naam')
                ->description('Voor en achternaam van de webmaster.'),
        ]);
    }

    public static function renderField(Field $field)
    {
        $field->setModel($this->model);

        return view($field->view(), array_merge([
            'field'           => $field,
            'key'             => $field->key, // As parameter so that it can be altered for translatable values
            'formElementView' => $field->formElementView(),
        ]), $field->viewData())->render();
    }


}