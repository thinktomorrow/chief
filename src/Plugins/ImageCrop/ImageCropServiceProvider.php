<?php

namespace Thinktomorrow\Chief\Plugins\ImageCrop;

use Livewire\Livewire;
use Thinktomorrow\Chief\Plugins\ChiefPluginSections;
use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;

class ImageCropServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-image-crop', __DIR__.'/views');

        Livewire::component('chief-wire::image-crop', ImageCropComponent::class);

        $this->app->make(ChiefPluginSections::class)
            ->addFooterSection('chief-image-crop::footer')
            ->addLivewireFileComponent('chief-wire::image-crop')
            ->addLivewireFileEditAction('chief-image-crop::file-edit-action');
    }
}
