<?php

namespace Thinktomorrow\Chief\Forms;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\Forms\Dialogs\Livewire\DialogComponent;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FallbackLocaleRequiredRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileDimensionsRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileMaxRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileMimetypesRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileMinRule;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FileRequiredRule;
use Thinktomorrow\Chief\Forms\UI\Livewire\EditFormComponent;
use Thinktomorrow\Chief\Forms\UI\Livewire\FormComponent;
use Thinktomorrow\Chief\Forms\UI\Livewire\RepeatComponent;

class FormsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-form', __DIR__.'/UI/views');

        Livewire::component('chief-form::dialog', DialogComponent::class);
        Livewire::component('chief-wire::form', FormComponent::class);
        Livewire::component('chief-wire::edit-form', EditFormComponent::class);
        Livewire::component('chief-wire::repeat', RepeatComponent::class);

        // Custom validation rules
        Validator::extendImplicit(FallbackLocaleRequiredRule::RULE, FallbackLocaleRequiredRule::class.'@validate');
        Validator::extendImplicit('file_required', FileRequiredRule::class.'@validate');
        Validator::extend('file_mimetypes', FileMimetypesRule::class.'@validate');
        Validator::extend('file_dimensions', FileDimensionsRule::class.'@validate');
        Validator::extend('file_min', FileMinRule::class.'@validate');
        Validator::extend('file_max', FileMaxRule::class.'@validate');
    }
}
