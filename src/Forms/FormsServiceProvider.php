<?php

namespace Thinktomorrow\Chief\Forms;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Thinktomorrow\Chief\Forms\Livewire\FileUpload;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileMaxRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileMinRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileRequiredRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileMimetypesRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileDimensionsRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FallbackLocaleRequiredRule;

class FormsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-form', __DIR__ . '/resources');

        // Custom validation rules
        Validator::extendImplicit(FallbackLocaleRequiredRule::RULE, FallbackLocaleRequiredRule::class.'@validate');
        Validator::extendImplicit('file_required', FileRequiredRule::class.'@validate');
        Validator::extend('file_mimetypes', FileMimetypesRule::class.'@validate');
        Validator::extend('file_dimensions', FileDimensionsRule::class.'@validate');
        Validator::extend('file_min', FileMinRule::class.'@validate');
        Validator::extend('file_max', FileMaxRule::class.'@validate');

        // Livewire components
        Livewire::component('chief-wire::file-upload', FileUpload::class);
    }
}
