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

class ChoosingOtherModelFilesTest extends ChiefTestCase
{
    use TestingFileUploads;

    private Testable $fileFieldUploadComponent;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        $model = ArticlePage::create();

        $this->fileFieldUploadComponent = Livewire::test(FileFieldUploadComponent::class, [
            'modelReference' => $model->modelReference()->get(),
            'fieldKey' => 'thumb',
            'fieldName' => 'thumb',
            'locale' => 'nl',
            'allowMultiple' => true,
        ]);
    }

    public function test_it_can_choose_existing_file()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $this->fileFieldUploadComponent
            ->assertCount('previewFiles', 0)
            ->call('onAssetsChosen', [$asset->id])
            ->assertCount('previewFiles', 1)
            ->assertSeeHtmlInOrder(['name="thumb[attach][0][id]"', 'value="'.$asset->id.'"'])
            ->assertSeeHtmlInOrder(['name="thumb[order][0]"', 'value="'.$asset->id.'"']);
    }
}
