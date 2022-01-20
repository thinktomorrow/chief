<?php

namespace Thinktomorrow\Chief\Forms;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FallbackLocaleRequiredRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileDimensionsRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileMaxRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileMimetypesRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileMinRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileRequiredRule;

class FormsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-forms', __DIR__ . '/resources');

        // Custom validation rules
        Validator::extendImplicit(FallbackLocaleRequiredRule::RULE, FallbackLocaleRequiredRule::class.'@validate');
        Validator::extendImplicit('file_required', FileRequiredRule::class.'@validate');
        Validator::extend('file_mimetypes', FileMimetypesRule::class.'@validate');
        Validator::extend('file_dimensions', FileDimensionsRule::class.'@validate');
        Validator::extend('file_min', FileMinRule::class.'@validate');
        Validator::extend('file_max', FileMaxRule::class.'@validate');

        /* Chief field components */
        Blade::component('chief::components.field.window.multiple', 'chief::fields');
        Blade::component('chief::components.field.window.single', 'chief::field');
        Blade::component('chief::components.field.form.single', 'chief::field.form');
        Blade::component('chief::components.field.form.multiple', 'chief::fields.form');
        Blade::component('chief::components.field.form.error', 'chief::field.form.error');
        Blade::component('chief::components.field.set', 'chief::field.set');
    }
}
