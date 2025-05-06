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

class ShowsExistingModelFileTest extends ChiefTestCase
{
    use TestingFileUploads;

    private Testable $fileFieldUploadComponent;

    private ArticlePage $model;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        $this->model = ArticlePage::create();

        $this->fileFieldUploadComponent = Livewire::test(FileFieldUploadComponent::class, [
            'modelReference' => $this->model->modelReference()->get(),
            'fieldKey' => 'thumb',
            'fieldName' => 'thumb',
            'locale' => 'nl',
            'allowMultiple' => true,
        ]);
    }

    public function test_it_shows_existing_file()
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
        ])->assertSeeHtmlInOrder(['name="thumb[attach][0][id]"', 'value="'.$asset->id.'"']);
    }
}
