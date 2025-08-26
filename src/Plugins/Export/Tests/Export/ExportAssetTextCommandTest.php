<?php

namespace Thinktomorrow\Chief\Plugins\Export\Tests\Export;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\AssetLibrary\Application\UpdateAssetData;
use Thinktomorrow\Chief\Plugins\Export\Tests\TestCase;
use Thinktomorrow\Chief\Sites\ChiefSites;

class ExportAssetTextCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('chief.sites', [
            ['locale' => 'nl'],
            ['locale' => 'en'],
        ]);

        ChiefSites::clearCache();
    }

    protected function tearDown(): void
    {
        ChiefSites::clearCache();

        parent::tearDown();
    }

    public function test_it_can_export_alt()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        app(UpdateAssetData::class)->handle($asset->id, [
            'alt' => [
                'nl' => 'alt nl',
                'en' => 'alt en',
            ],
        ]);

        $this->artisan('chief:export-asset-text')
            ->expectsConfirmation('This will export alt texts for 1 assets. Do you wish to continue?', 'yes')
            ->assertExitCode(0);

        $filepath = Storage::disk('local')->path('exports/'.date('Ymd').'/'.config('app.name').'-asset-text-'.date('Y-m-d').'.xlsx');

        $sheet = IOFactory::load($filepath)->getActiveSheet();

        $this->assertEquals($asset->getUrl(), $sheet->getCell('B2')->getValue());
        $this->assertEquals($asset->getBaseName(), $sheet->getCell('C2')->getValue());
        $this->assertEquals('alt nl', $sheet->getCell('D2')->getValue());
        $this->assertEquals('alt en', $sheet->getCell('E2')->getValue());
    }

    public function test_it_can_export_empty_alt()
    {
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $this->artisan('chief:export-asset-text')
            ->expectsConfirmation('This will export alt texts for 1 assets. Do you wish to continue?', 'yes')
            ->assertExitCode(0);

        $filepath = Storage::disk('local')->path('exports/'.date('Ymd').'/'.config('app.name').'-asset-text-'.date('Y-m-d').'.xlsx');

        $sheet = IOFactory::load($filepath)->getActiveSheet();

        $this->assertEquals($asset->getUrl(), $sheet->getCell('B2')->getValue());
        $this->assertEquals($asset->getBaseName(), $sheet->getCell('C2')->getValue());
        $this->assertEquals(null, $sheet->getCell('D2')->getValue());
        $this->assertEquals(null, $sheet->getCell('E2')->getValue());
    }
}
