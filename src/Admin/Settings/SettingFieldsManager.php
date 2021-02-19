<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Settings;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Admin\Settings\Application\ChangeHomepage;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\Field;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\PageField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\SelectField;
use Thinktomorrow\Chief\Site\Urls\UrlHelper;

class SettingFieldsManager
{
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
                ->name('homepage.:locale')
                ->options(UrlHelper::allOnlineModels())
                ->translatable(config('chief.locales'))
                ->validation('required')
                ->grouped()
                ->label('Homepagina')
                ->description('Geef hier de homepagina voor de site op.'),
            InputField::make('app_name')
                ->label('Site naam')
                ->validation('required')
                ->description('Naam van de applicatie. Dit wordt getoond in o.a. de mail communicatie.'),
            InputField::make('contact_email')
                ->validation('required|email')
                ->label('Webmaster email')
                ->description('Het emailadres van de webmaster. Hierop ontvang je standaard alle contactnames.'),
            InputField::make('contact_name')
                ->validation('required')
                ->label('Webmaster naam')
                ->description('Voor en achternaam van de webmaster.'),
            PageField::make('templates')
                ->label('Pagina templates')
                ->pagesAsOptions()
                ->description('Selecteer één of meerdere pagina\'s om te gebruiken als template. Een nieuwe pagina vanuit een template aanmaken start met eenzelfde paginaopbouw.')
                ->multiple(),
        ]);
    }

    public function editFields(): Fields
    {
        return $this->fields()->map(function (Field $field) {
            return $field->valueResolver(function ($model = null, $locale = null, $field) {
                return $this->settings->get($field->getKey(), $locale);
            });
        });
    }

    public function createFields(): Fields
    {
        return new Fields();
    }

    /**
     * Triggers the create save action for all prepared field values.
     *
     * @param Request $request
     */
    public function saveCreateFields(Request $request): void
    {
        // Not used for settings manager but required by interface
    }

    /**
     * Triggers the edit save action for all prepared field values.
     *
     * @param Request $request
     */
    public function saveEditFields(Request $request): void
    {
        $this->saveFields($request);
    }

    private function saveFields(Request $request)
    {
        $existingHomepageValue = [];

        foreach ($this->fields() as $key => $field) {
            if (! $setting = Setting::where('key', $key)->first()) {
                Setting::create([
                    'key' => $key,
                    'value' => $request->input($key, ''),
                ]);

                continue;
            }

            if ($key === Setting::HOMEPAGE && is_array($setting->value)) {
                $existingHomepageValue = $setting->value;
            }

            $setting->update(['value' => $request->input($key, '')]);
        }

        // A changed homepage needs to be reflected in the urls as well in order to respond to incoming requests.
        if ($request->filled(Setting::HOMEPAGE)) {
            app(ChangeHomepage::class)->onSettingChanged($existingHomepageValue);
        }
    }
}
