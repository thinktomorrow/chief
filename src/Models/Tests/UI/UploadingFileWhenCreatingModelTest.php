<?php

namespace Thinktomorrow\Chief\Models\Tests\UI;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Livewire;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Assets\Livewire\PreviewFile;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Models\UI\Livewire\CreateModelComponent;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

class UploadingFileWhenCreatingModelTest extends ChiefTestCase
{
    use RefreshDatabase;
    use TestingFileUploads;

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
    }

    public function test_it_uploads_file()
    {
        $filePath = $this->storeFakeImageOnLivewireDisk('image.png');
        $previewFile = PreviewFile::fromTemporaryUploadedFile(TemporaryUploadedFile::createFromLivewire($filePath));

        $this->assertDatabaseCount('assets', 0);
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
