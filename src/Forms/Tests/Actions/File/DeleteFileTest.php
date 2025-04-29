<?php

namespace Thinktomorrow\Chief\Forms\Tests\Actions\File;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;

use function app;

class DeleteFileTest extends ChiefTestCase
{
    use PageFormParams;
    use TestingFileUploads;

    private $model;

    private $manager;

    public function test_a_file_can_be_deleted()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $this->saveFileField($this->model, 'thumb', [
            'nl' => [
                'attach' => [
                    ['id' => $asset->id],
                ],
            ],
        ]);

        $this->saveFileField($this->model, 'thumb', [
            'nl' => [
                'queued_for_deletion' => [
                    ['id' => $asset->id],
                ],
            ],
        ]);

        $this->assertCount(0, $this->model->fresh()->assets());
        $this->assertCount(1, Asset::all());
    }

    public function test_it_can_remove_translatable_images()
    {
        $asset = app(CreateAsset::class)->uploadedFile(UploadedFile::fake()->image('image-nl.png'))->save();
        $assetEn = app(CreateAsset::class)->uploadedFile(UploadedFile::fake()->image('image-en.png'))->save();

        $this->saveFileField($this->model, 'thumb', [
            'nl' => [
                'attach' => [
                    ['id' => $asset->id],
                ],
            ],
            'en' => [
                'attach' => [
                    ['id' => $assetEn->id],
                ],
            ],
        ]);

        $this->assertCount(2, $this->model->fresh()->assets('thumb', null));
        $this->assertCount(2, Asset::all());

        $this->saveFileField($this->model, 'thumb', [
            'en' => [
                'queued_for_deletion' => [
                    ['id' => $assetEn->id],
                ],
            ],
        ]);

        $this->assertEquals('image-nl.png', $this->model->refresh()->asset('thumb', 'nl')->getFileName());

        // Fallback for en is nl
        $this->assertEquals('image-nl.png', $this->model->asset('thumb', 'en')->getFileName());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setupAndCreateArticle();
        $this->manager = $this->manager($this->model);
    }
}
