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

class ReorderingModelFilesTest extends ChiefTestCase
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
            'previewFiles' => [],
            'allowMultiple' => true,
        ]);
    }

    public function test_when_reordering_input_values_reflect_new_order()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $asset2 = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image2.png'))
            ->save();

        $this->fileFieldUploadComponent
            ->call('onAssetsChosen', [$asset->id, $asset2->id])
            ->assertCount('previewFiles', 2)
            ->assertSeeHtmlInOrder([
                'name="thumb[order][0]"',
                'value="'.$asset->id.'"',
                'name="thumb[order][1]"',
                'value="'.$asset2->id.'"',
            ])
            ->call('reorder', [$asset2->id, $asset->id])
            ->assertSeeHtmlInOrder([
                'name="thumb[order][0]"',
                'value="'.$asset2->id.'"',
                'name="thumb[order][1]"',
                'value="'.$asset->id.'"',
            ]);
    }
}
