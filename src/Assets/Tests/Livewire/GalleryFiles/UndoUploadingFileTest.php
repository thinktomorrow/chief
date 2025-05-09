<?php

namespace Thinktomorrow\Chief\Assets\Tests\Livewire\GalleryFiles;

use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Thinktomorrow\Chief\Assets\Livewire\FileUploadComponent;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class UndoUploadingFileTest extends ChiefTestCase
{
    use TestingFileUploads;

    private Testable $fileFieldUploadComponent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileUploadComponent = Livewire::test(FileUploadComponent::class, [
            'parentId' => 'xxx',
            'fieldName' => 'thumb',
            'allowMultiple' => true,
        ]);
    }

    public function test_it_can_undo_uploading_file()
    {
        $filePath = $this->storeFakeImageOnLivewireDisk('image.png');

        // Upload file
        $this->fileUploadComponent
            ->set('files', [[
                'id' => 'xxx',
                'fileName' => 'image.png',
                'fileSize' => 10,
            ]])
            ->call('open')
            ->dispatch('upload:finished', 'files.0.fileRef', [$filePath])
            ->assertCount('previewFiles', 1)
            ->assertSeeHtml('name="thumb[uploads][0][id]"')
            ->assertSeeHtml('value="'.$filePath.'"');

        // Remove from upload queue
        $this->fileUploadComponent
            ->call('deleteFile', $filePath)
            ->assertSet('previewFiles.0.isQueuedForDeletion', true)
            ->assertDontSeeHtml('name="thumb[uploads][0][id]"');
    }
}
