<?php

namespace Thinktomorrow\Chief\Assets\Tests\Livewire\GalleryFiles;

use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Thinktomorrow\Chief\Assets\Livewire\FileUploadComponent;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class UploadingFileTest extends ChiefTestCase
{
    use TestingFileUploads;

    private Testable $fileUploadComponent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileUploadComponent = Livewire::test(FileUploadComponent::class, [
            'parentId' => 'xxx',
            'fieldName' => 'thumb',
            'allowMultiple' => true,
        ]);
    }

    public function test_it_can_create_component()
    {
        $this->fileUploadComponent
            ->assertSet('parentId', 'xxx')
            ->assertSet('fieldName', 'thumb')
            ->assertSet('allowMultiple', true)
            ->assertSet('previewFiles', [])
            ->assertSet('acceptedMimeTypes', []);
    }

    public function test_it_can_upload_new_asset()
    {
        $filePath = $this->storeFakeImageOnLivewireDisk('image.png');

        $this->fileUploadComponent
            ->assertCount('previewFiles', 0)
            ->set('files', [[
                'id' => 'xxx',
                'fileName' => 'image.png',
                'fileSize' => 10,
            ]])
            ->call('open')
            ->dispatch('upload:finished', 'files.0.fileRef', [$filePath])
            ->assertCount('previewFiles', 1)
            ->assertSeeHtml('name="thumb[uploads][0][id]"')
            ->assertSeeHtml('value="'.$filePath.'"')
            ->assertSeeHtml('name="thumb[order][0]"')
            ->assertSeeHtml('value="'.$filePath.'"');
    }
}
