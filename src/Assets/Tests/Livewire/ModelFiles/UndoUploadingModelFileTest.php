<?php

namespace Thinktomorrow\Chief\Assets\Tests\Livewire\ModelFiles;

use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Assets\Livewire\FileFieldUploadComponent;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class UndoUploadingModelFileTest extends ChiefTestCase
{
    use TestingFileUploads;

    private ArticlePage $model;

    private Testable $fileFieldUploadComponent;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        $this->model = ArticlePage::create();

        $this->fileFieldUploadComponent = Livewire::test(FileFieldUploadComponent::class, [
            'modelReference' => $this->model->modelReference()->get(),
            'previewFiles' => [],
            'fieldKey' => 'thumb',
            'fieldName' => 'thumb',
            'locale' => 'nl',
            'allowMultiple' => true,
        ]);
    }

    public function test_it_can_queue_unsaved_uploaded_file_for_deletion()
    {
        $filePath = $this->storeFakeImageOnLivewireDisk('image.png');

        $this->fileFieldUploadComponent
            ->set('files', [[
                'id' => 'xxx',
                'fileName' => 'image.png',
                'fileSize' => 10,
            ]])
            ->dispatch('upload:finished', 'files.0.fileRef', [$filePath])
            ->call('deleteFile', $filePath)
            ->assertSet('previewFiles.0.isQueuedForDeletion', true)
            ->assertDontSeeHtml('name="thumb[uploads][0][id]"');
    }

    public function test_it_can_queue_added_file_for_deletion()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $this->fileFieldUploadComponent
            ->call('onAssetsChosen', [$asset->id])
            ->assertCount('previewFiles', 1)
            ->call('deleteFile', $asset->id)
            ->assertSet('previewFiles.0.isQueuedForDeletion', true)
            ->assertDontSeeHtml('name="thumb[attach][0]"');
    }
}
