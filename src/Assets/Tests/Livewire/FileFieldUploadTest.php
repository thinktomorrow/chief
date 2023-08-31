<?php

namespace Livewire;

use Illuminate\Http\UploadedFile;
use Livewire\Testing\TestableLivewire;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Assets\Livewire\FileFieldUploadComponent;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class FileFieldUploadTest extends ChiefTestCase
{
    private $model;
    private TestableLivewire $livewireInstance;

    public function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        $this->model = ArticlePage::create();

        $this->livewireInstance = Livewire::test(FileFieldUploadComponent::class, [
            'modelReference' => $this->model->modelReference()->get(),
            'fieldKey' => 'thumb',
            'fieldName' => 'thumb',
            'locale' => 'nl',
            'allowMultiple' => true,
        ]);
    }

    public function test_it_can_create_component()
    {
        $this->livewireInstance
            ->assertSet('modelReference', $this->model->modelReference()->get())
            ->assertSet('fieldKey', 'thumb')
            ->assertSet('fieldName', 'thumb')
            ->assertSet('locale', 'nl')
            ->assertSet('allowMultiple', true)
            ->assertSet('previewFiles', [])
            ->assertSet('acceptedMimeTypes', []);
    }

    public function test_it_can_create_component_with_existing_assets()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        Livewire::test(FileFieldUploadComponent::class, [
            'modelReference' => $this->model->modelReference()->get(),
            'fieldKey' => 'thumb',
            'fieldName' => 'thumb',
            'locale' => 'nl',
            'assets' => [$asset],
        ])
            ->assertCount('previewFiles', 1)
            ->assertSeeHtml('name="thumb[order][0]"') // Better should be to check this input in one assertion but linebreaks make it difficult
            ->assertSeeHtml('value="' . $asset->id . '"');
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
            ->dispatch('upload:finished', 'files.0.fileRef', [$filePath])
            ->assertCount('previewFiles', 1)
            ->assertSeeHtml('name="thumb[uploads][0][id]"')
            ->assertSeeHtml('value="' . $filePath . '"')
            ->assertSeeHtml('name="thumb[order][0]"')
            ->assertSeeHtml('value="' . $filePath . '"');
    }

    public function test_it_can_add_existing_asset()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $this->livewireInstance
            ->assertCount('previewFiles', 0)
            ->call('onAssetsChosen', [$asset->id])
            ->assertCount('previewFiles', 1)
            ->assertSeeHtmlInOrder(['name="thumb[attach][0][id]"', 'value="' . $asset->id . '"'])
            ->assertSeeHtmlInOrder(['name="thumb[order][0]"', 'value="' . $asset->id . '"']);
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
            ->assertCount('previewFiles', 2)
            ->assertSeeHtmlInOrder([
                'name="thumb[order][0]"',
                'value="' . $asset->id . '"',
                'name="thumb[order][1]"',
                'value="' . $asset2->id . '"',
            ])
            ->call('reorder', [$asset2->id, $asset->id])
            ->assertSeeHtmlInOrder([
                'name="thumb[order][0]"',
                'value="' . $asset2->id . '"',
                'name="thumb[order][1]"',
                'value="' . $asset->id . '"',
            ]);
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

        $this->livewireInstance
            ->call('onAssetsChosen', [$asset->id])
            ->assertCount('previewFiles', 1)
            ->call('deleteFile', $asset->id)
            ->assertSet('previewFiles.0.isQueuedForDeletion', true)
            ->assertDontSeeHtml('name="thumb[attach][0]"');
    }

    public function test_it_can_queue_existing_file_for_deletion()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        Livewire::test(FileFieldUploadComponent::class, [
            'modelReference' => $this->model->modelReference()->get(),
            'fieldKey' => 'thumb',
            'fieldName' => 'thumb',
            'locale' => 'nl',
            'assets' => [$asset],
        ])->call('deleteFile', $asset->id)
            ->assertSet('previewFiles.0.isQueuedForDeletion', true)
            ->assertSeeHtmlInOrder(['name="thumb[queued_for_deletion][0]"', 'value="' . $asset->id . '"']);
    }

    public function test_it_has_existing_file_reference_on_single_field()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        Livewire::test(FileFieldUploadComponent::class, [
            'modelReference' => $this->model->modelReference()->get(),
            'fieldKey' => 'thumb',
            'fieldName' => 'thumb',
            'locale' => 'nl',
            'assets' => [$asset],
            'allowMultiple' => false,
        ])->assertSeeHtmlInOrder(['name="thumb[attach][0][id]"', 'value="' . $asset->id . '"']);
    }

    public function test_it_can_queue_existing_file_for_deletion_on_single_field()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        Livewire::test(FileFieldUploadComponent::class, [
            'modelReference' => $this->model->modelReference()->get(),
            'fieldKey' => 'thumb',
            'fieldName' => 'thumb',
            'locale' => 'nl',
            'assets' => [$asset],
            'allowMultiple' => false,
        ])->call('deleteFile', $asset->id)
            ->assertSet('previewFiles.0.isQueuedForDeletion', true)
            ->assertSeeHtmlInOrder(['name="thumb[queued_for_deletion][0]"', 'value="' . $asset->id . '"']);
    }
}
