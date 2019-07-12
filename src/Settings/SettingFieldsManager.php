<?php

namespace Thinktomorrow\Chief\Settings;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\FieldManager;
use Thinktomorrow\Chief\Fields\RenderingFields;
use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Fields\Types\InputField;

class SettingFieldsManager extends Fields implements FieldManager
{
    use RenderingFields;

    /** @var Settings */
    private $settingsManager;

    public function __construct(Settings $settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }

    public function fields(): Fields
    {
        return new Fields([
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

    public function fieldValue(Field $field, $locale = null)
    {
        return $this->settingsManager->get($field->key(), $locale);
    }

    public function saveFields(Request $request)
    {
        ddd($request->all());
    }
}