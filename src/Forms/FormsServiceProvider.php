<?php

namespace Thinktomorrow\Chief\Forms;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\App\Http\Middleware\AuthenticateChiefSession;
use Thinktomorrow\Chief\Forms\Fields\File\Livewire\AssetDeleteComponent;
use Thinktomorrow\Chief\Forms\Fields\File\Livewire\FileEditComponent;
use Thinktomorrow\Chief\Forms\Fields\File\Livewire\FilesChooseComponent;
use Thinktomorrow\Chief\Forms\Fields\File\Livewire\FilesComponent;
use Thinktomorrow\Chief\Forms\Fields\File\Livewire\GalleryComponent;
use Thinktomorrow\Chief\Forms\Fields\File\Plugins\ImageCropComponent;
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

        Livewire::component('chief-wire::file-gallery', GalleryComponent::class);
        Livewire::component('chief-wire::file-upload', FilesComponent::class);
        Livewire::component('chief-wire::files-choose', FilesChooseComponent::class);
        Livewire::component('chief-wire::file-edit', FileEditComponent::class);
        Livewire::component('chief-wire::image-crop', ImageCropComponent::class);
        Livewire::component('chief-wire::asset-delete', AssetDeleteComponent::class);
    }
}
