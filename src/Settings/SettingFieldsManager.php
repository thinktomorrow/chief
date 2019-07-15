<?php

namespace Thinktomorrow\Chief\Settings;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\FieldManager;
use Thinktomorrow\Chief\Fields\RenderingFields;
use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Fields\Types\SelectField;
use Thinktomorrow\Chief\Urls\UrlRecord;

class SettingFieldsManager extends Fields implements FieldManager
{
    use RenderingFields;

    /** @var Settings */
    private $settings;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    public function fields(): Fields
    {
        return new Fields([
            SelectField::make('homepage')
                ->options(UrlRecord::allOnlineModels())
                ->translatable(config('translatable.locales'))
                ->grouped(),
            InputField::make('client_app_name')
                ->label('Site naam')
                ->description('Naam van de applicatie. Dit wordt getoond in o.a. de mail communicatie.'),
            InputField::make('contact_email')
                ->validation('email')
                ->label('Webmaster email')
                ->description('Het emailadres van de webmaster. Hierop ontvang je standaard alle contactnames.'),
            InputField::make('contact_name')
                ->label('Webmaster naam')
                ->description('Voor en achternaam van de webmaster.'),
        ]);
    }

    public function fieldValue(Field $field, $locale = null)
    {
        return $this->settings->get($field->key(), $locale);
    }

    public function saveFields(Request $request)
    {
        ddd($request->all());
        foreach($this->fields() as $key => $field)
        {
            if(!$setting = Setting::where('key', $key)->first()) {
                Setting::create([
                    'key' => $key,
                    'value' => $request->get($key, ''),
                ]);

                continue;
            }

            $setting->update(['value' => $request->get($key, '')]);
        }
    }
}