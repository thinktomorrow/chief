<?php

namespace Thinktomorrow\Chief\Assets\Tests\App;

use Thinktomorrow\Chief\Assets\App\FileApplication;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Tests\TestSupport\PageWithAssets;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class UpdatingFilenameTest extends ChiefTestCase
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

    public function test_it_can_update_filename()
    {
        app(FileApplication::class)->updateFileName($this->assetId, 'foobar');

        $this->assertEquals('foobar.png', $this->model->fresh()->asset('thumb', 'nl')->getFileName());
    }

    public function test_it_updating_extension_via_filename_field_should_be_prevented()
    {
        app(FileApplication::class)->updateFileName($this->assetId, 'foobar.jpg');

        $this->assertEquals('foobar.jpg.png', $this->model->fresh()->asset('thumb', 'nl')->getFileName());
        $this->assertEquals('png', $this->model->fresh()->asset('thumb', 'nl')->getExtension());
    }
}
