<?php

namespace Livewire;

use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportTesting\Testable;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Assets\Livewire\FileFieldEditComponent;
use Thinktomorrow\Chief\Assets\Livewire\PreviewFile;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class FileFieldEditTest extends ChiefTestCase
{
    private $model;
    private Testable $livewireInstance;
    private Text $textComponent;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setUpAndCreateArticle();

        $this->livewireInstance = Livewire::test(FileFieldEditComponent::class, [
            'modelReference' => $this->model->modelReference()->get(),
            'fieldKey' => 'thumb',
            'locale' => 'nl',
            'parentId' => 'xxx',
            'components' => [
                $this->textComponent = Text::make('alt'),
            ],
        ]);
    }

    public function test_it_can_create_component()
    {
        $this->livewireInstance
            ->assertSet('modelReference', $this->model->modelReference()->get())
            ->assertSet('fieldKey', 'thumb')
            ->assertSet('locale', 'nl')
            ->assertSet('parentId', 'xxx')
            ->assertSet('components', [
                $this->textComponent->toLivewire(),
            ])
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
                'alt' => 'alt text', // Only text field alt is presented in edit form
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
