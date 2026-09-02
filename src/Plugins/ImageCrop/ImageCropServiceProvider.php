<?php

namespace Thinktomorrow\Chief\Plugins\ImageCrop;

use Livewire\Livewire;
use Thinktomorrow\Chief\Plugins\ChiefPluginSections;
use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;

class ImageCropServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-image-crop', __DIR__.'/UI/views');

        Livewire::addNamespace(
            namespace: 'chief-wire-image-crop',
            classNamespace: 'Thinktomorrow\\Chief\\Plugins\\ImageCrop\\UI\\Livewire',
            classPath: __DIR__.'/UI/Livewire',
            classViewPath: __DIR__.'/UI/views/livewire',
        );

        $this->app->make(ChiefPluginSections::class)
            ->addFooterSection('chief-image-crop::footer')
            ->addLivewireFileComponent('chief-wire-image-crop::image-cropper')
            ->addLivewireFileEditAction('chief-image-crop::file-edit-action');
    }
}
