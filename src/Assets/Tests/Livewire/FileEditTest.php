<?php

namespace Livewire;

use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportTesting\Testable;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Assets\Livewire\FileEditComponent;
use Thinktomorrow\Chief\Assets\Livewire\PreviewFile;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class FileEditTest extends ChiefTestCase
{
    private $model;

    private Testable $livewireInstance;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setUpAndCreateArticle();

        $this->livewireInstance = Livewire::test(FileEditComponent::class, [
            'parentId' => 'xxx',
        ]);
    }

    public function test_it_can_create_component()
    {
        $this->livewireInstance
            ->assertSet('parentId', 'xxx')
            ->assertSet('previewFile', null);
    }

    public function test_it_sets_previewfile_on_open()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $previewFile = PreviewFile::fromAsset($asset);

        $this->livewireInstance
            ->call('open', ['previewfile' => $previewFile])
            ->assertSet('previewFile', $previewFile);
    }

    public function test_it_sets_previewfile_as_livewire_array_on_open()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $previewFile = PreviewFile::fromAsset($asset);

        $this->livewireInstance
            ->call('open', ['previewfile' => $previewFile->toLivewire()])
            ->assertSet('previewFile', $previewFile);
    }

    public function test_it_unsets_previewfile_on_close()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $previewFile = PreviewFile::fromAsset($asset);

        $this->livewireInstance
            ->call('open', ['previewfile' => $previewFile])
            ->assertSet('previewFile', $previewFile)
            ->call('close')
            ->assertSet('previewFile', null);
    }

    public function test_it_merges_previewfile_fieldvalues_with_form_based_on_passed_form_components()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $previewFile = PreviewFile::fromAsset($asset);
        $previewFile->fieldValues = [
            'alt' => 'alt text',
            'custom' => 'foobar',
        ];

        $this->livewireInstance
            ->assertSet('form', [])
            ->call('open', ['previewfile' => $previewFile])
            ->assertSet('form', [
                'basename' => 'image', // default
            ]);
    }

    public function test_it_merges_previewfile_fieldvalues_with_form_values_on_submit()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $previewFile = PreviewFile::fromAsset($asset);
        $previewFile->fieldValues = [
            'basename' => 'image', // This is for test comparison because basename is present in fieldvalues automatically
            'alt' => 'alt text',
            'custom' => 'foobar',
        ];

        $this->livewireInstance
            ->assertSet('form', [])
            ->call('open', ['previewfile' => $previewFile])
            ->call('submit');

        $this->livewireInstance->assertDispatched('assetUpdated-xxx');

        // Check if the fieldValues match after submit
        $this->livewireInstance->assertDispatched('assetUpdated-xxx', $previewFile->toLivewire());
    }
}
