<?php

namespace Thinktomorrow\Chief\Assets\Tests\Livewire\ModelFiles;

use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Thinktomorrow\Chief\Assets\Livewire\FileFieldUploadComponent;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class UploadingModelFileTest extends ChiefTestCase
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
            'previewFiles' => [],
            'fieldKey' => 'thumb',
            'fieldName' => 'thumb',
            'locale' => 'nl',
            'allowMultiple' => true,
        ]);
    }

    public function test_it_can_upload_new_asset_as_model_field()
    {
        $filePath = $this->storeFakeImageOnLivewireDisk('image.png');

        $this->fileFieldUploadComponent
            ->assertCount('previewFiles', 0)
            ->set('files', [[
                'id' => 'xxx',
                'fileName' => 'image.png',
                'fileSize' => 10,
            ]])
            ->dispatch('upload:finished', 'files.0.fileRef', [$filePath])
            ->assertCount('previewFiles', 1)
            ->assertSeeHtml('name="thumb[uploads][0][id]"')
            ->assertSeeHtml('value="'.$filePath.'"')
            ->assertSeeHtml('name="thumb[order][0]"')
            ->assertSeeHtml('value="'.$filePath.'"');
    }
}
