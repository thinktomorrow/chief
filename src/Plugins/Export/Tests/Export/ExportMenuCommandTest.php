<?php

namespace Thinktomorrow\Chief\Plugins\Export\Tests\Export;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Plugins\Export\Tests\TestCase;
use Thinktomorrow\Chief\Sites\ChiefSites;

class ExportMenuCommandTest extends TestCase
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

    public function test_it_can_export_menu_text()
    {
        $this->disableExceptionHandling();
        $menu = Menu::create([
            'type' => 'main',
        ]);

        MenuItem::create([
            'menu_id' => $menu->id,
            'values' => json_encode([
                'url' => ['nl' => '/nl-link', 'en' => '/en-link'],
                'label' => ['nl' => 'test nl', 'en' => 'test en'],
                'owner_label' => ['nl' => 'test owner nl', 'en' => 'test owner en'],
            ]),
        ]);

        $this->artisan('chief:export-menu');

        $filepath = Storage::disk('local')->path('exports/'.date('Ymd').'/'.config('app.name').'-menu-'.date('Y-m-d').'.xlsx');

        $sheet = IOFactory::load($filepath)->getActiveSheet();

        $this->assertEquals('main', $sheet->getCell('B2')->getValue());
        $this->assertEquals('custom', $sheet->getCell('C2')->getValue());
        $this->assertEquals('/nl-link', $sheet->getCell('D2')->getValue());
        $this->assertEquals('test nl', $sheet->getCell('E2')->getValue());
        $this->assertEquals('test owner nl', $sheet->getCell('F2')->getValue());
        $this->assertEquals('/en-link', $sheet->getCell('G2')->getValue());
        $this->assertEquals('test en', $sheet->getCell('H2')->getValue());
        $this->assertEquals('test owner en', $sheet->getCell('I2')->getValue());
    }
}
