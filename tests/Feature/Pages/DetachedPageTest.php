<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\PageTranslation;
use Thinktomorrow\Chief\Tests\Fakes\DetachedPageFake;
use Thinktomorrow\Chief\Tests\Fakes\DetachedPageFakeTranslation;
use Thinktomorrow\Chief\Tests\TestCase;

class DetachedPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        DetachedPageFake::migrateUp();
    }

    /** @test */
    public function a_detached_page_can_be_retrieved_via_generic_page_model()
    {
        $created = Page::create(['morph_key' => DetachedPageFake::class]);
        $page = Page::find($created->id);

        $this->assertInstanceOf(DetachedPageFake::class, $page);
    }

    /** @test */
    public function it_can_retrieve_custom_translation()
    {
        $page = Page::create([
            'morph_key'  => DetachedPageFake::class,
            'question:nl' => 'nl vraag',
            'slug:nl'     => 'nl slug',
        ]);

        $this->assertEquals('nl vraag', DetachedPageFake::find($page->id)->{'question:nl'});
    }

    /** @test */
    public function it_can_retrieve_translation_from_custom_table()
    {
        DetachedPageFake::create([
            'question:nl' => 'nl vraag',
            'slug:nl'     => 'nl slug',
        ]);

        $this->assertEquals(1, DetachedPageFakeTranslation::count());
        $this->assertEquals(0, PageTranslation::count());
    }
}
