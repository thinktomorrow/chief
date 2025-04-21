<?php

namespace Thinktomorrow\Chief\Plugins\Export\Tests\Export;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Plugins\Export\Tests\TestCase;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class ExportResourceCommandTest extends TestCase
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

    public function test_it_can_export_resource()
    {
        $article = $this->setUpAndCreateArticle(['title' => 'article title', 'content_trans' => ['nl' => 'content article nl', 'en' => 'content article en']]);

        FragmentTestHelpers::createContextAndAttachFragment($article, SnippetStub::class, null, 0, ['title' => 'quote title', 'title_trans' => ['nl' => 'title quote nl', 'en' => 'title quote en']]);

        $this->artisan('chief:export-resource article_page --include-static');

        $filepath = storage_path('app/exports/'.date('Ymd').'/'.config('app.name').'-article_page-'.date('Y-m-d').'.xlsx');

        $sheet = IOFactory::load($filepath)->getActiveSheet();

        $this->assertEquals('article page', $sheet->getCell('B2')->getValue());
        $this->assertEquals('Article page', $sheet->getCell('C2')->getValue());
        $this->assertEquals('title', $sheet->getCell('D2')->getValue());
        $this->assertEquals('article title', $sheet->getCell('E2')->getValue());
        $this->assertEquals(null, $sheet->getCell('F2')->getValue());
        $this->assertEquals(null, $sheet->getCell('G2')->getValue());

        $this->assertEquals('article page', $sheet->getCell('B3')->getValue());
        $this->assertEquals('Article page', $sheet->getCell('C3')->getValue());
        $this->assertEquals('content_trans', $sheet->getCell('D3')->getValue());
        $this->assertEquals(null, $sheet->getCell('E3')->getValue());
        $this->assertEquals('content article nl', $sheet->getCell('F3')->getValue());
        $this->assertEquals('content article en', $sheet->getCell('G3')->getValue());

        $this->assertEquals('article page', $sheet->getCell('B4')->getValue());
        $this->assertEquals('Snippet stub', $sheet->getCell('C4')->getValue());
        $this->assertEquals('title', $sheet->getCell('D4')->getValue());
        $this->assertEquals('quote title', $sheet->getCell('E4')->getValue());
        $this->assertEquals(null, $sheet->getCell('F4')->getValue());
        $this->assertEquals(null, $sheet->getCell('G4')->getValue());

        $this->assertEquals('article page', $sheet->getCell('B5')->getValue());
        $this->assertEquals('Snippet stub', $sheet->getCell('C5')->getValue());
        $this->assertEquals('title_trans', $sheet->getCell('D5')->getValue());
        $this->assertEquals(null, $sheet->getCell('E5')->getValue());
        $this->assertEquals('title quote nl', $sheet->getCell('F5')->getValue());
        $this->assertEquals('title quote en', $sheet->getCell('G5')->getValue());
    }
}
