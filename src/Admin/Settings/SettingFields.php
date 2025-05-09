<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Settings;

use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Urls\App\Listeners\ChangeHomepage;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlHelper;

class SettingFields
{
    /** @var Settings */
    private $settings;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    protected function fields(): iterable
    {
        yield MultiSelect::make('homepage')
            ->name('homepage.:locale')
            ->id('homepage.:locale')
            ->options(UrlHelper::allOnlineModels())
            ->locales()
            ->required()
            ->grouped()
            ->label('Homepagina')
            ->description('Geef hier de homepagina voor de site op.');
        yield Text::make('app_name')
            ->label('Site naam')
            ->required()
            ->description('Naam van de applicatie. Dit wordt getoond in o.a. de mail communicatie.');
        yield Text::make('contact_email')
            ->rules('required|email')
            ->label('Webmaster email')
            ->description('Het emailadres van de webmaster. Hierop ontvang je standaard alle contactnames.');
        yield Text::make('contact_name')
            ->required()
            ->label('Webmaster naam')
            ->description('Voor en achternaam van de webmaster.');
    }

    public function populatedFields(): Fields
    {
        return Fields::make($this->fields())->map(function (Field $field) {
            return $field->value(function ($model, $locale, $field) {
                return $this->settings->get($field->getKey(), $locale);
            });
        });
    }

    public function saveFields(Fields $fields, array $input, array $files): void
    {
        $existingHomepageValue = [];

        foreach ($fields->all()->keys() as $key) {
            if (! $setting = Setting::where('key', $key)->first()) {
                Setting::create([
                    'key' => $key,
                    'value' => data_get($input, $key, ''),
                ]);

                continue;
            }

            if ($key === Setting::HOMEPAGE && is_array($setting->value)) {
                $existingHomepageValue = $setting->value;
            }

            $setting->update(['value' => data_get($input, $key, '')]);
        }

        // A changed homepage needs to be reflected in the urls as well in order to respond to incoming requests.
        if (data_get($input, Setting::HOMEPAGE, null)) {
            app(ChangeHomepage::class)->onSettingChanged($existingHomepageValue);
        }
    }
}
