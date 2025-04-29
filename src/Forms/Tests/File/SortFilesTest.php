<?php

namespace Thinktomorrow\Chief\Forms\Tests\File;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;

use function app;

class SortFilesTest extends ChiefTestCase
{
    use PageFormParams;
    use TestingFileUploads;

    private $model;

    private $manager;

    private $asset;

    private $asset2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setupAndCreateArticle();
        $this->manager = $this->manager($this->model);

        $this->asset = app(CreateAsset::class)->uploadedFile(UploadedFile::fake()->image('image-nl.png'))->save();
        $this->asset2 = app(CreateAsset::class)->uploadedFile(UploadedFile::fake()->image('image-en.png'))->save();
    }

    public function test_assets_can_be_sorted()
    {
        app(AddAsset::class)->handle($this->model, $this->asset, 'thumb_trans', 'nl', 0, []);
        app(AddAsset::class)->handle($this->model, $this->asset2, 'thumb_trans', 'nl', 0, []);

        $this->uploadFile($this->model, [
            'thumb_trans' => [
                'nl' => [
                    'order' => [$this->asset2->id, $this->asset->id],
                ],
            ],
        ]);

        $assets = $this->model->assets('thumb_trans', 'nl');
        $this->assertEquals($this->asset2->id, $assets[0]->id);
        $this->assertEquals($this->asset->id, $assets[1]->id);
    }

    public function test_localized_assets_can_be_sorted()
    {
        app(AddAsset::class)->handle($this->model, $this->asset, 'thumb_trans', 'nl', 0, []);
        app(AddAsset::class)->handle($this->model, $this->asset2, 'thumb_trans', 'nl', 0, []);
        app(AddAsset::class)->handle($this->model, $this->asset, 'thumb_trans', 'en', 0, []);
        app(AddAsset::class)->handle($this->model, $this->asset2, 'thumb_trans', 'en', 0, []);

        $this->uploadFile($this->model, [
            'thumb_trans' => [
                'nl' => [
                    'order' => [$this->asset2->id, $this->asset->id],
                ],
                'en' => [
                    'order' => [$this->asset->id, $this->asset2->id],
                ],
            ],
        ]);
        $assets = $this->model->assets('thumb_trans', 'nl');
        $this->assertEquals($this->asset2->id, $assets[0]->id);
        $this->assertEquals($this->asset->id, $assets[1]->id);

        $assets = $this->model->assets('thumb_trans', 'en');
        $this->assertEquals($this->asset->id, $assets[0]->id);
        $this->assertEquals($this->asset2->id, $assets[1]->id);
    }
}
