<?php

namespace Thinktomorrow\Chief\Plugins\Export\Tests\Export;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Thinktomorrow\Chief\Plugins\Export\Tests\TestCase;
use Thinktomorrow\Chief\Site\Menu\MenuItem;

class ExportMenuCommandTest extends TestCase
{
    public function test_it_can_export_menu_text()
    {
        MenuItem::create([
            'values' => json_encode([
                'url' => ['nl' => '/nl-link', 'en' => '/en-link'],
                'label' => ['nl' => 'test nl', 'en' => 'test en'],
                'owner_label' => ['nl' => 'test owner nl', 'en' => 'test owner en'],
            ]),
        ]);

        $this->artisan('chief:export-menu');

        $filepath = storage_path('app/exports/'.config('app.name') .'-menu-'.date('Y-m-d').'.xlsx');

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
