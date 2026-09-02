<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit;

use Thinktomorrow\Chief\Plugins\HotSpots\HotSpotsServiceProvider;
use Thinktomorrow\Chief\Plugins\ImageCrop\ImageCropServiceProvider;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class LivewireComponentRegistrationTest extends ChiefTestCase
{
    public function test_it_registers_all_core_livewire_components(): void
    {
        $componentNames = [
            'chief-wire-assets::asset-delete-dialog',
            'chief-wire-assets::asset-gallery',
            'chief-wire-assets::external-file-field-asset-chooser',
            'chief-wire-assets::file-field-asset-chooser',
            'chief-wire-assets::file-field-asset-editor',
            'chief-wire-assets::file-field-asset-uploader',
            'chief-wire-assets::gallery-asset-editor',
            'chief-wire-assets::gallery-asset-uploader',
            'chief-wire-form::action-dialog',
            'chief-wire-form::edit-model-form',
            'chief-wire-form::model-form',
            'chief-wire-form::repeat-field-editor',
            'chief-wire-fragments::add-context',
            'chief-wire-fragments::add-fragment',
            'chief-wire-fragments::edit-context',
            'chief-wire-fragments::edit-fragment',
            'chief-wire-fragments::fragment-context',
            'chief-wire-fragments::fragment-contexts',
            'chief-wire-menu::add-menu',
            'chief-wire-menu::edit-menu',
            'chief-wire-menu::menu-list',
            'chief-wire-models::create-model',
            'chief-wire-models::edit-model',
            'chief-wire-sites::edit-model-site-selection',
            'chief-wire-sites::global-site-toggle',
            'chief-wire-sites::model-site-selection',
            'chief-wire-sites::model-site-toggle',
            'chief-wire-states::edit-model-state',
            'chief-wire-states::model-state',
            'chief-wire-table::data-table',
            'chief-wire-urls::edit-model-links',
            'chief-wire-urls::model-links',
        ];

        foreach ($componentNames as $componentName) {
            $this->assertTrue(app('livewire')->exists($componentName), "Livewire component [{$componentName}] is not registered.");
        }
    }

    public function test_it_registers_plugin_livewire_components_by_namespace(): void
    {
        (new HotSpotsServiceProvider($this->app))->boot();
        (new ImageCropServiceProvider($this->app))->boot();

        $this->assertTrue(app('livewire')->exists('chief-wire-hotspots::hot-spot-editor'));
        $this->assertTrue(app('livewire')->exists('chief-wire-image-crop::image-cropper'));
    }
}
