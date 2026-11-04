<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\HotSpots\Tests;

use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Assets\Livewire\PreviewFile;
use Thinktomorrow\Chief\Plugins\HotSpots\HotSpotComponent;

class HotSpotComponentTest extends TestCase
{
    private Testable $livewireInstance;

    protected function setUp(): void
    {
        parent::setUp();

        $this->livewireInstance = Livewire::test(HotSpotComponent::class, [
            'parentId' => 'xxx',
        ]);
    }

    public function test_it_notifies_the_admin_scripts_when_the_dialog_opens()
    {
        $this->livewireInstance
            ->call('open', $this->openPayload())
            ->assertSet('isOpen', true)
            ->assertDispatched('form-dialog-opened', componentId: $this->livewireInstance->id(), parentComponentId: 'xxx');
    }

    public function test_it_notifies_the_admin_scripts_when_a_hotspot_is_added()
    {
        $this->livewireInstance
            ->call('open', $this->openPayload())
            ->call('addHotSpot', 10, 20, 30, 40)
            ->assertCount('hotSpots', 1)
            ->assertDispatched('form-dialog-opened', componentId: $this->livewireInstance->id(), parentComponentId: 'xxx');
    }

    private function openPayload(): array
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        return [
            'previewfile' => PreviewFile::fromAsset($asset),
            'previous_sibling_id' => 'yyy',
        ];
    }
}
