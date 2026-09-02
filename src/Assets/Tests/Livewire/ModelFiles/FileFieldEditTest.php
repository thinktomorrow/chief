<?php

namespace Thinktomorrow\Chief\Assets\Tests\Livewire\ModelFiles;

use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Assets\UI\Livewire\FileFieldAssetEditor;
use Thinktomorrow\Chief\Assets\UI\Livewire\PreviewFile;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class FileFieldEditTest extends ChiefTestCase
{
    private $model;

    private Testable $livewireInstance;

    private Text $textComponent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setUpAndCreateArticle();

        $this->livewireInstance = Livewire::test(FileFieldAssetEditor::class, [
            'modelReference' => $this->model->modelReference()->get(),
            'fieldKey' => 'thumb',
            'locale' => 'nl',
            'parentId' => 'xxx',
            'previewFiles' => [],
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

        app(AddAsset::class)->handle($this->model, $asset, 'thumb', 'nl', 0, []);

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

        $this->assertSame('alt text', $this->model->fresh()->asset('thumb', 'nl')->getPivotData('alt'));
    }

    public function test_it_can_submit_without_model_reference(): void
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $previewFile = PreviewFile::fromAsset($asset);

        Livewire::test(FileFieldAssetEditor::class, [
            'modelReference' => null,
            'fieldKey' => 'thumb',
            'locale' => 'nl',
            'parentId' => 'xxx',
            'previewFiles' => [],
            'components' => [
                Text::make('alt'),
            ],
        ])
            ->call('open', ['previewfile' => $previewFile])
            ->call('submit')
            ->assertDispatched('assetUpdated-xxx');
    }

    public function test_it_can_rename_a_file_associated_with_an_unregistered_menu_item_resource(): void
    {
        $menu = Menu::create(['type' => 'main']);
        $menuItem = MenuItem::create([
            'menu_id' => $menu->id,
            'type' => 'custom',
        ]);
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('menu-thumbnail.png'))
            ->save();

        app(AddAsset::class)->handle($menuItem, $asset, 'thumbnail', 'nl', 0, []);

        Livewire::test(FileFieldAssetEditor::class, [
            'modelReference' => $menuItem->modelReference()->get(),
            'fieldKey' => 'thumbnail',
            'locale' => 'nl',
            'parentId' => 'xxx',
            'components' => [],
        ])
            ->call('open', ['previewfile' => PreviewFile::fromAsset($asset)])
            ->set('form.basename', 'renamed-thumbnail')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertDispatched('assetUpdated-xxx');

        $this->assertSame('renamed-thumbnail.png', $asset->fresh()->getFirstMedia()->file_name);
    }

    public function test_it_hides_file_edit_site_toggle_for_single_locale_project()
    {
        config()->set('chief.sites', [
            ['locale' => 'nl'],
        ]);
        ChiefSites::clearCache();

        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $previewFile = PreviewFile::fromAsset($asset);

        $this->livewireInstance
            ->call('open', ['previewfile' => $previewFile])
            ->assertDontSee('file-edit-site-toggle');
    }

    public function test_it_shows_file_edit_site_toggle_for_multiple_locales_project()
    {
        config()->set('chief.sites', [
            ['locale' => 'nl'],
            ['locale' => 'en'],
        ]);
        ChiefSites::clearCache();

        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $previewFile = PreviewFile::fromAsset($asset);

        $this->livewireInstance
            ->call('open', ['previewfile' => $previewFile])
            ->assertSee('file-edit-site-toggle');
    }
}
