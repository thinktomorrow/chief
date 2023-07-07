<?php

namespace Thinktomorrow\Chief\Assets;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\AssetLibrary\AssetLibraryServiceProvider;
use Thinktomorrow\Chief\App\Http\Middleware\AuthenticateChiefSession;
use Thinktomorrow\Chief\Assets\Livewire\AssetDeleteComponent;
use Thinktomorrow\Chief\Assets\Livewire\FileEditComponent;
use Thinktomorrow\Chief\Assets\Livewire\FileFieldChooseComponent;
use Thinktomorrow\Chief\Assets\Livewire\FileFieldEditComponent;
use Thinktomorrow\Chief\Assets\Livewire\FileFieldUploadComponent;
use Thinktomorrow\Chief\Assets\Livewire\FileUploadComponent;
use Thinktomorrow\Chief\Assets\Livewire\GalleryComponent;
use Thinktomorrow\Chief\Assets\Plugins\ImageCropComponent;

class AssetsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        (new AssetLibraryServiceProvider($this->app))->boot();

        $this->app['view']->addNamespace('chief-assets', __DIR__ . '/App/resources');

        // Livewire components
        Livewire::addPersistentMiddleware([
            AuthenticateChiefSession::class,
        ]);

        Blade::component('chief-assets::components.upload-and-dropzone', 'chief-assets::upload-and-dropzone');

        Livewire::component('chief-wire::file-gallery', GalleryComponent::class);
        Livewire::component('chief-wire::file-field-upload', FileFieldUploadComponent::class);
        Livewire::component('chief-wire::file-field-edit', FileFieldEditComponent::class);
        Livewire::component('chief-wire::file-field-choose', FileFieldChooseComponent::class);
        Livewire::component('chief-wire::file-upload', FileUploadComponent::class);
        Livewire::component('chief-wire::file-edit', FileEditComponent::class);
        Livewire::component('chief-wire::image-crop', ImageCropComponent::class);
        Livewire::component('chief-wire::asset-delete', AssetDeleteComponent::class);
    }

    public function register()
    {
        (new AssetLibraryServiceProvider($this->app))->register();
    }
}
