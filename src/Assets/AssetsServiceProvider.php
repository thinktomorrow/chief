<?php

namespace Thinktomorrow\Chief\Assets;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\AssetLibrary\AssetLibraryServiceProvider;
use Thinktomorrow\Chief\App\Http\Middleware\AuthenticateChiefSession;

class AssetsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        (new AssetLibraryServiceProvider($this->app))->boot();

        $this->app['view']->addNamespace('chief-assets', __DIR__.'/UI/views');

        // Livewire components
        Livewire::addPersistentMiddleware([
            AuthenticateChiefSession::class,
        ]);

        Blade::component('chief-assets::components.upload-and-dropzone', 'chief-assets::upload-and-dropzone');

        Livewire::addNamespace(
            namespace: 'chief-wire-assets',
            classNamespace: 'Thinktomorrow\\Chief\\Assets\\UI\\Livewire',
            classPath: __DIR__.'/UI/Livewire',
            classViewPath: __DIR__.'/UI/views/livewire',
        );

        // Reset general livewire rules - these rules will be set via chief
        // instead so we can have a uniform validation flow
        if ($maxFileSize = config('chief.assets.max_file_size_in_bytes')) {
            config()->set('livewire.temporary_file_upload.rules', [
                'required', 'file', 'max:'.($maxFileSize / 1024),
            ]);

            config()->set('media-library.max_file_size', $maxFileSize);
        }
    }

    public function register()
    {
        (new AssetLibraryServiceProvider($this->app))->register();
    }
}
