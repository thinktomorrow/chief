<?php

namespace Thinktomorrow\Chief\Assets;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\AssetLibrary\AssetLibraryServiceProvider;
use Thinktomorrow\Chief\App\Http\Middleware\AuthenticateChiefSession;
use Thinktomorrow\Chief\Assets\Livewire\AssetDeleteComponent;
use Thinktomorrow\Chief\Assets\Livewire\AttachedFileEditComponent;
use Thinktomorrow\Chief\Assets\Livewire\FileEditComponent;
use Thinktomorrow\Chief\Assets\Livewire\FileEditDialog;
use Thinktomorrow\Chief\Assets\Livewire\FilesChooseComponent;
use Thinktomorrow\Chief\Assets\Livewire\FilesComponent;
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

        Livewire::component('chief-wire::file-gallery', GalleryComponent::class);
        Livewire::component('chief-wire::file-upload', FilesComponent::class);
        Livewire::component('chief-wire::files-choose', FilesChooseComponent::class);
        Livewire::component('chief-wire::attached-file-edit', AttachedFileEditComponent::class);
        Livewire::component('chief-wire::file-edit', FileEditComponent::class);
        Livewire::component('chief-wire::image-crop', ImageCropComponent::class);
        Livewire::component('chief-wire::asset-delete', AssetDeleteComponent::class);
    }

    public function register()
    {
        (new AssetLibraryServiceProvider($this->app))->register();
    }
}
