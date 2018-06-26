<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\TestCase;
use Illuminate\Support\Carbon;

class PageTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /** @test */
    public function it_can_find_by_slug()
    {
        $page = factory(Page::class)->create([
            'slug'      => 'foobar',
            'published' => 0
        ]);

        $this->assertEquals($page->id, Page::findBySlug('foobar')->id);
    }

    /** @test */
    public function it_can_find_published_by_slug()
    {
        factory(Page::class)->create([
            'slug' => 'foobar',
            'published' => 1
        ]);
        factory(Page::class)->create([
            'slug' => 'barfoo',
            'published' => 0
        ]);

        $this->assertNotNull(Page::findPublishedBySlug('foobar'));
        $this->assertNull(Page::findPublishedBySlug('barfoo'));
    }

    /** @test */
    public function it_can_find_sorted_by_recent()
    {
        factory(Page::class)->create([
            'published'     => 0,
            'created_at'    => Carbon::now()->subDays(3)
        ]);
        factory(Page::class)->create([
            'published'     => 0,
            'created_at'    => Carbon::now()->subDays(1)
        ]);

        $pages = Page::sortedByCreated()->get();

        $this->assertTrue($pages->first()->created_at->gt($pages->last()->created_at));
    }
}
