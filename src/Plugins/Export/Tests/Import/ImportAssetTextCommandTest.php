<?php

namespace Thinktomorrow\Chief\Plugins\Export\Tests\Import;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\App\FileApplication;
use Thinktomorrow\Chief\Plugins\Export\Tests\TestCase;

class ImportAssetTextCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
    }

    public function test_it_can_import_basename()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $this->artisan('chief:export-asset-text');

        $filepath = Storage::disk('local')->path('exports/'.date('Ymd').'/'.config('app.name').'-asset-text-'.date('Y-m-d').'.xlsx');

        // Change the basename in the database
        app(FileApplication::class)->updateFileName($asset->id, 'changed-image');

        // Now import it again
        $this->artisan('chief:import-asset-text', ['file' => $filepath]);

        $this->assertEquals('image', Asset::find(1)->getBasename());
    }

    public function test_it_can_import_alt_texts(): void
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        app(FileApplication::class)->updateAssetData($asset->id, [
            'alt' => [
                'nl' => 'original alt text nl',
                'en' => 'original alt text en',
            ],
        ]);

        $this->artisan('chief:export-asset-text');

        $filepath = Storage::disk('local')->path('exports/'.date('Ymd').'/'.config('app.name').'-asset-text-'.date('Y-m-d').'.xlsx');

        // Change the alt texts in the database
        app(FileApplication::class)->updateAssetData($asset->id, [
            'alt' => [
                'nl' => 'changed alt text nl',
                'en' => 'changed alt text en',
            ],
        ]);

        // Now import it again
        $this->artisan('chief:import-asset-text', ['file' => $filepath]);

        $this->assertEquals('original alt text nl', Asset::find(1)->getData('alt')['nl']);
        $this->assertEquals('original alt text en', Asset::find(1)->getData('alt')['en']);
    }
}
