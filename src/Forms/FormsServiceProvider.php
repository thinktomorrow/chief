<?php

namespace Thinktomorrow\Chief\Forms;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\App\Http\Middleware\AuthenticateChiefSession;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FallbackLocaleRequiredRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileDimensionsRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileMaxRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileMimetypesRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileMinRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileRequiredRule;
use Thinktomorrow\Chief\Forms\Livewire\FileUpload;

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
        Livewire::addPersistentMiddleware([
            AuthenticateChiefSession::class,
        ]);

        Livewire::component('chief-wire::form', \Thinktomorrow\Chief\Forms\Livewire\Form::class);
        Livewire::component('chief-wire::dialog', \Thinktomorrow\Chief\Forms\Livewire\Dialog::class);
        Livewire::component('chief-wire::file-upload', FileUpload::class);
    }
}
