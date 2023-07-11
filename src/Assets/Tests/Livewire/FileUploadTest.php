<?php

namespace Livewire;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\Chief\Assets\Livewire\FileUploadComponent;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class
FileUploadTest extends ChiefTestCase
{
    private $model;
    private \Livewire\Testing\TestableLivewire $livewireInstance;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        $this->model = ArticlePage::create();

        $this->livewireInstance = Livewire::test(FileUploadComponent::class, [
            'parentId' => 'xxx',
            'fieldName' => 'thumb',
            'allowMultiple' => true,
        ]);
    }

    public function test_it_can_create_component()
    {
        $this->livewireInstance
            ->assertSet('parentId', 'xxx')
            ->assertSet('fieldName', 'thumb')
            ->assertSet('allowMultiple', true)
            ->assertSet('previewFiles', [])
            ->assertSet('acceptedMimeTypes', []);
    }

    public function test_it_can_upload_new_asset()
    {
        $filePath = $this->uploadForLivewire($file = UploadedFile::fake()->image('image.png'));

        $this->livewireInstance
            ->assertCount('previewFiles', 0)
            ->set('files', [[
                'id' => 'xxx',
                'fileName' => $file->getClientOriginalName(),
                'fileSize' => $file->getSize(),
            ]])
            ->call('open')
            ->emit('upload:finished', 'files.0.fileRef', [$filePath])
            ->assertCount('previewFiles', 1)
            ->assertSeeHtml('name="thumb[uploads][0][id]" value="' . $filePath . '"')
            ->assertSeeHtml('name="thumb[order][0]" value="' . $filePath . '"');
    }

    public function test_it_can_queue_unsaved_uploaded_file_for_deletion()
    {
        $filePath = $this->uploadForLivewire($file = UploadedFile::fake()->image('image.png'));

        $this->livewireInstance
            ->set('files', [[
                'id' => 'xxx',
                'fileName' => $file->getClientOriginalName(),
                'fileSize' => $file->getSize(),
            ]])
            ->emit('upload:finished', 'files.0.fileRef', [$filePath])
            ->call('deleteFile', $filePath)
            ->assertSet('previewFiles.0.isQueuedForDeletion', true)
            ->assertDontSeeHtml('name="thumb[uploads][0][id]"');
    }
}
