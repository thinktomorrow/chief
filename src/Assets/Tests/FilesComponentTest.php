<?php

namespace Thinktomorrow\Chief\Assets\Tests;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Controllers\FileUploadHandler;
use Livewire\Livewire;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Assets\Livewire\FilesComponent;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class FilesComponentTest extends ChiefTestCase
{
    private $model;
    private \Livewire\Testing\TestableLivewire $livewireInstance;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        $this->model = ArticlePage::create();

        $this->livewireInstance = Livewire::test(FilesComponent::class, [
            'modelReference' => $this->model->modelReference()->get(),
            'fieldKey' => 'thumb',
            'fieldName' => 'thumb',
            'locale' => 'nl',
        ]);
    }

    public function test_it_can_create_component()
    {
        $this->livewireInstance
            ->assertSet('modelReference',$this->model->modelReference()->get())
            ->assertSet('fieldKey','thumb')
            ->assertSet('fieldName','thumb')
            ->assertSet('locale','nl')
            ->assertSet('allowMultiple',false)
            ->assertSet('previewFiles',[])
            ->assertSet('acceptedMimeTypes',[]);
    }

    public function test_it_can_create_component_with_existing_assets()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        Livewire::test(FilesComponent::class, [
            'modelReference' => $this->model->modelReference()->get(),
            'fieldKey' => 'thumb',
            'fieldName' => 'thumb',
            'locale' => 'nl',
            'assets' => [$asset],
        ])
            ->assertCount('previewFiles',1)
            ->assertSeeHtml('name="thumb[order][0]" value="'.$asset->id.'"');
    }

    public function test_it_can_upload_new_asset()
    {
        $filePath = $this->uploadForLivewire($file = UploadedFile::fake()->image('image.png'));

        $this->livewireInstance
            ->assertCount('previewFiles',0)
            ->set('files', [[
                'fileName' => $file->getClientOriginalName(),
                'fileSize' => $file->getSize(),
            ]])
            ->emit('upload:finished', 'files.0.fileRef',[$filePath])
            ->assertCount('previewFiles',1)
            ->assertSeeHtml('name="thumb[uploads][0][id]" value="'.$filePath.'"')
            ->assertSeeHtml('name="thumb[order][0]" value="'.$filePath.'"');
    }

    public function test_it_can_add_existing_asset()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $this->livewireInstance
            ->assertCount('previewFiles',0)
            ->call('onAssetsChosen', [$asset->id])
            ->assertCount('previewFiles',1)
            ->assertSeeHtml('name="thumb[attach][0][id]" value="'.$asset->id.'"')
            ->assertSeeHtml('name="thumb[order][0]" value="'.$asset->id.'"');
    }

    public function test_when_reordering_input_values_reflect_new_order()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $asset2 = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image2.png'))
            ->save();

        $this->livewireInstance
            ->call('onAssetsChosen', [$asset->id, $asset2->id])
            ->assertCount('previewFiles',2)
            ->assertSeeHtml('name="thumb[order][0]" value="'.$asset->id.'"')
            ->assertSeeHtml('name="thumb[order][1]" value="'.$asset2->id.'"')
            ->call('reorder', [$asset2->id, $asset->id])
            ->assertSeeHtml('name="thumb[order][0]" value="'.$asset2->id.'"')
            ->assertSeeHtml('name="thumb[order][1]" value="'.$asset->id.'"');
    }


    public function test_it_can_queue_unsaved_uploaded_file_for_deletion()
    {
        $filePath = $this->uploadForLivewire($file = UploadedFile::fake()->image('image.png'));

        $this->livewireInstance
            ->set('files', [[
                'fileName' => $file->getClientOriginalName(),
                'fileSize' => $file->getSize(),
            ]])
            ->emit('upload:finished', 'files.0.fileRef',[$filePath])
            ->call('deleteFile',$filePath)
            ->assertSet('previewFiles.0.isQueuedForDeletion', true)
            ->assertDontSeeHtml('name="thumb[uploads][0][id]"');
    }

    public function test_it_can_queue_added_file_for_deletion()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $this->livewireInstance
            ->call('onAssetsChosen', [$asset->id])
            ->assertCount('previewFiles',1)
            ->call('deleteFile',$asset->id)
            ->assertSet('previewFiles.0.isQueuedForDeletion', true)
            ->assertDontSeeHtml('name="thumb[attach][0]"');
    }

    public function test_it_can_queue_existing_file_for_deletion()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        Livewire::test(FilesComponent::class, [
            'modelReference' => $this->model->modelReference()->get(),
            'fieldKey' => 'thumb',
            'fieldName' => 'thumb',
            'locale' => 'nl',
            'assets' => [$asset],
        ])->call('deleteFile',$asset->id)
        ->assertSet('previewFiles.0.isQueuedForDeletion', true)
        ->assertSeeHtml('name="thumb[queued_for_deletion][0]" value="'.$asset->id.'"');
    }

    private function uploadForLivewire(UploadedFile $file)
    {
        Storage::fake('tmp-for-tests');

        $paths = app(FileUploadHandler::class)->validateAndStore([
            $file
        ],'tmp-for-tests');

        return ltrim($paths[0],'/');
    }
}
