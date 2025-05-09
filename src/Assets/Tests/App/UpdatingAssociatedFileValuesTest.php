<?php

namespace Thinktomorrow\Chief\Assets\Tests\App;

use Thinktomorrow\Chief\Assets\App\FileApplication;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Tests\TestSupport\PageWithAssets;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class UpdatingAssociatedFileValuesTest extends ChiefTestCase
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

    public function test_it_can_update_field_values()
    {
        app(FileApplication::class)->updateAssociatedAssetData($this->model->modelReference()->get(), 'thumb', 'nl', $this->assetId, [
            'caption' => 'I belong to this file',
        ]);

        $this->assertEquals('I belong to this file', $this->model->fresh()->asset('thumb', 'nl')->getData('caption'));
    }

    public function test_it_can_update_localized_field_values()
    {
        app(FileApplication::class)->updateAssociatedAssetData($this->model->modelReference()->get(), 'thumb', 'nl', $this->assetId, [
            'caption' => ['nl' => 'I belong to this file in Dutch', 'en' => 'I belong to this file in English'],
        ]);

        $this->assertEquals('I belong to this file in Dutch', $this->model->fresh()->asset('thumb', 'nl')->getData('caption.nl'));
        $this->assertEquals('I belong to this file in English', $this->model->fresh()->asset('thumb', 'nl')->getData('caption.en'));
    }
}
