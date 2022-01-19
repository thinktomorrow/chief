<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FallbackLocaleRequiredRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileDimensionsRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileMaxRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileMimetypesRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileMinRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileRequiredRule;

class ValidationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Custom validator for requiring on translations only the fallback locale
        // this is called in the validation as required-fallback-locale
//        Validator::extendImplicit('requiredFallbackLocale', function ($attribute, $value) {
//            $fallbackLocale = config('app.fallback_locale');
//
//            if (false !== strpos($attribute, 'trans.'.$fallbackLocale.'.')) {
//                return (bool) trim($value);
//            }
//
//            return true;
//        }, 'Voor :attribute is minstens de default taal verplicht in te vullen, aub.');

        Validator::extendImplicit(FallbackLocaleRequiredRule::RULE, FallbackLocaleRequiredRule::class.'@validate');
        Validator::extendImplicit('file_required', FileRequiredRule::class.'@validate');
        Validator::extend('file_mimetypes', FileMimetypesRule::class.'@validate');
        Validator::extend('file_dimensions', FileDimensionsRule::class.'@validate');
        Validator::extend('file_min', FileMinRule::class.'@validate');
        Validator::extend('file_max', FileMaxRule::class.'@validate');

//        $this->bootMediaValidationRules();
    }

    private function bootMediaValidationRules(): void
    {
        Validator::extendImplicit('file_required', FileRequiredRule::class.'@validate');
        Validator::extend('file_mimetypes', FileMimetypesRule::class.'@validate');
        Validator::extend('file_dimensions', FileDimensionsRule::class.'@validate');
        Validator::extend('file_min', FileMinRule::class.'@validate');
        Validator::extend('file_max', FileMaxRule::class.'@validate');
    }
}
