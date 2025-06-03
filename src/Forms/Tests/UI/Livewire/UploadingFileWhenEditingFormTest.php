<?php

namespace Thinktomorrow\Chief\Forms\Tests\UI\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Livewire;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Assets\Livewire\PreviewFile;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Forms\UI\Livewire\EditFormComponent;
use Thinktomorrow\Chief\Models\UI\Livewire\CreateModelComponent;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

class UploadingFileWhenEditingFormTest extends ChiefTestCase
{
    use RefreshDatabase;
    use TestingFileUploads;

    private ArticlePage $model;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class);

        ArticlePageResource::setFieldsDefinition(function () {
            return [
                Form::make('main')->items([
                    Text::make('title_trans')->locales()->required(),
                    Image::make('image'),
                ]),
            ];
        });

        $this->model = ArticlePage::create(
            ['title_trans' => ['nl' => 'oude model titel', 'en' => 'old model title']],
        );
    }

    public function test_it_can_see_existing_files(): void
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        app(AddAsset::class)->handle($this->model, $asset, 'image', 'nl', 0, []);

        $component = Livewire::test(EditFormComponent::class, [
            'modelReference' => $this->model->modelReference(),
            'formComponent' => Form::make('main'),
            'parentComponentId' => 'xxx',
        ])->call('open', ['locales' => ['nl', 'en']]);

        $component->assertSet('form.files.image.nl', [PreviewFile::fromAsset($asset)]);
    }

    public function test_it_uploads_file()
    {
        $filePath = $this->storeFakeImageOnLivewireDisk('image.png');
        $previewFile = PreviewFile::fromTemporaryUploadedFile(TemporaryUploadedFile::createFromLivewire($filePath));

        $this->assertDatabaseCount('assets', 0);
        $this->assertDatabaseCount('assets_pivot', 0);

        $component = Livewire::test(EditFormComponent::class, [
            'modelReference' => $this->model->modelReference(),
            'formComponent' => Form::make('main'),
            'parentComponentId' => 'xxx',
        ]);

        $component->call('open', ['locales' => ['nl', 'en']])
            ->set('form.files.image.nl', [$previewFile]);

        $component->call('save');

        $this->assertDatabaseCount('assets', 1);
        $this->assertDatabaseCount('assets_pivot', 1);
    }

    public function test_it_attaches_file()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $previewFile = PreviewFile::fromAsset($asset);

        $this->assertDatabaseCount('assets', 1);
        $this->assertDatabaseCount('assets_pivot', 0);

        Livewire::test(CreateModelComponent::class)
            ->dispatch('open-create-model', ['modelClass' => ArticlePage::class])
            ->set('locales', ['nl'])
            ->set('form', [
                'title_trans' => ['nl' => 'Test title'],
                'files' => ['image' => ['nl' => [$previewFile]]],
            ])
            ->call('save');

        $this->assertDatabaseCount('assets', 1);
        $this->assertDatabaseCount('assets_pivot', 1);
    }
}
