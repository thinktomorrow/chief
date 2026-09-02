<?php

namespace Thinktomorrow\Chief\Assets\Tests\Livewire\ModelFiles;

use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\AssetLibrary\AssetContract;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Assets\UI\Livewire\FileFieldAssetUploader;
use Thinktomorrow\Chief\Assets\UI\Livewire\PreviewFile;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class ShowsExistingModelFileTest extends ChiefTestCase
{
    use TestingFileUploads;

    private Testable $fileFieldUploadComponent;

    private ArticlePage $model;

    private AssetContract $asset;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        $this->model = ArticlePage::create();

        $this->asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $this->fileFieldUploadComponent = Livewire::test(FileFieldAssetUploader::class, [
            'modelReference' => $this->model->modelReference()->get(),
            'fieldKey' => 'thumb',
            'previewFiles' => [PreviewFile::fromAsset($this->asset)],
            'fieldName' => 'thumb',
            'locale' => 'nl',
            'allowMultiple' => true,
        ]);
    }

    public function test_it_shows_existing_file()
    {
        $this->fileFieldUploadComponent
            ->assertSeeHtmlInOrder(['name="thumb[attach][0][id]"', 'value="'.$this->asset->id.'"']);
    }
}
