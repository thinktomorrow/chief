<?php

namespace Thinktomorrow\Chief\Assets\Tests\App;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\App\FileApplication;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Tests\TestSupport\PageWithAssets;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class ReplacingFileMediaTest extends ChiefTestCase
{
    use TestingFileUploads;

    private PageWithAssets $model;

    private int $assetId;

    protected function setUp(): void
    {
        parent::setUp();

        PageWithAssets::migrateUp();
        chiefRegister()->resource(PageWithAssets::class);

        PageWithAssets::setFieldsDefinition(fn () => [
            File::make('thumb')->locales(['nl', 'en']),
        ]);

        $this->model = PageWithAssets::create();

        $this->uploadImageField($this->model, 'thumb');
        $this->assetId = $this->model->asset('thumb', 'nl')->id;
    }

    public function test_it_can_replace_media()
    {
        $uploadedFile = $this->storeFakeImageOnDisk('other-image.png');

        app(FileApplication::class)->replaceMedia($this->assetId, $uploadedFile);

        $this->assertEquals('other-image.png', $this->model->fresh()->asset('thumb', 'nl')->getFileName());
        $this->assertEquals('png', $this->model->fresh()->asset('thumb', 'nl')->getExtension());
    }

    public function test_no_assets_are_added_when_replacing_media()
    {
        $uploadedFile = $this->storeFakeImageOnDisk('other-image.png');

        app(FileApplication::class)->replaceMedia($this->assetId, $uploadedFile);

        $this->assertCount(1, Asset::all());
        $this->assertCount(1, Media::all());
    }
}
