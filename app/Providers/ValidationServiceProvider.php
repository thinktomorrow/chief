<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Thinktomorrow\Chief\ManagedModels\Fields\ValidationRules\FileFieldMinRule;
use Thinktomorrow\Chief\ManagedModels\Fields\ValidationRules\FileFieldMaxRule;
use Thinktomorrow\Chief\ManagedModels\Fields\ValidationRules\ImageFieldMaxRule;
use Thinktomorrow\Chief\ManagedModels\Fields\ValidationRules\ImageFieldMinRule;
use Thinktomorrow\Chief\ManagedModels\Fields\ValidationRules\FileFieldRequiredRule;
use Thinktomorrow\Chief\ManagedModels\Fields\ValidationRules\FileFieldMimetypesRule;
use Thinktomorrow\Chief\ManagedModels\Fields\ValidationRules\ImageFieldRequiredRule;
use Thinktomorrow\Chief\ManagedModels\Fields\ValidationRules\FileFieldDimensionsRule;
use Thinktomorrow\Chief\ManagedModels\Fields\ValidationRules\ImageFieldMimetypesRule;
use Thinktomorrow\Chief\ManagedModels\Fields\ValidationRules\ImageFieldDimensionsRule;

class ValidationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Custom validator for requiring on translations only the fallback locale
        // this is called in the validation as required-fallback-locale
        Validator::extendImplicit('requiredFallbackLocale', function ($attribute, $value, $parameters, $validator) {
            $fallbackLocale = config('app.fallback_locale');

            if (false !== strpos($attribute, 'trans.' . $fallbackLocale . '.')) {
                return !!trim($value);
            }

            return true;
        }, 'Voor :attribute is minstens de default taal verplicht in te vullen, aub.');

        $this->bootMediaValidationRules();
    }

    private function bootMediaValidationRules()
    {
        Validator::extendImplicit('filefield_required', FileFieldRequiredRule::class . '@validate');
        Validator::extend('filefield_mimetypes', FileFieldMimetypesRule::class . '@validate');
        Validator::extend('filefield_dimensions', FileFieldDimensionsRule::class . '@validate');
        Validator::extend('filefield_min', FileFieldMinRule::class . '@validate');
        Validator::extend('filefield_max', FileFieldMaxRule::class . '@validate');

        Validator::extendImplicit('imagefield_required', ImageFieldRequiredRule::class . '@validate');
        Validator::extend('imagefield_mimetypes', ImageFieldMimetypesRule::class . '@validate');
        Validator::extend('imagefield_dimensions', ImageFieldDimensionsRule::class . '@validate');
        Validator::extend('imagefield_max', ImageFieldMaxRule::class . '@validate');
        Validator::extend('imagefield_min', ImageFieldMinRule::class . '@validate');
    }
}
