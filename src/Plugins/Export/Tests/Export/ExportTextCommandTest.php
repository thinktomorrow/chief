<?php

namespace Thinktomorrow\Chief\Plugins\Export\Tests\Export;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Thinktomorrow\Chief\Plugins\Export\Tests\TestCase;
use Thinktomorrow\Squanto\Database\Application\AddDatabaseLine;
use Thinktomorrow\Squanto\Domain\Line;
use Thinktomorrow\Squanto\Domain\Metadata\Metadata;

class ExportTextCommandTest extends TestCase
{
    public function test_it_can_export_squanto_text()
    {
        app(AddDatabaseLine::class)->handle(
            $line = Line::fromRaw('about.title', ['nl' => 'test nl', 'en' => 'test en']),
            Metadata::fromLine($line)
        );

        $this->artisan('chief:export-text');

        $filepath = storage_path('app/exports/'.date('Ymd-His').'/'.config('app.name') .'-text-'.date('Y-m-d').'.xlsx');

        $sheet = IOFactory::load($filepath)->getActiveSheet();

        $this->assertEquals('about', $sheet->getCell('B2')->getValue());
        $this->assertEquals('title', $sheet->getCell('C2')->getValue());
        $this->assertEquals('test nl', $sheet->getCell('D2')->getValue());
        $this->assertEquals('test en', $sheet->getCell('E2')->getValue());
    }
}
