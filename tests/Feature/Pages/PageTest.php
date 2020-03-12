<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\TestCase;
use Illuminate\Support\Carbon;

class PageTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /** @test */
    public function it_can_find_sorted_by_recent()
    {
        factory(Page::class)->create([
            'current_state' => PageState::DRAFT,
            'created_at'    => Carbon::now()->subDays(3)
        ]);
        factory(Page::class)->create([
            'current_state' => PageState::DRAFT,
            'created_at'    => Carbon::now()->subDays(1)
        ]);

        $pages = Page::sortedByCreated()->get();

        $this->assertTrue($pages->first()->created_at->gt($pages->last()->created_at));
    }

    /** @test */
    public function if_no_labelsingular_is_set_it_takes_singular_classname()
    {
        $page = factory(Page::class)->create([
            'current_state' => PageState::DRAFT,
        ]);

        $this->assertEquals('Thinktomorrow\Chief\Pages\Page', $page->flatReferenceGroup());
    }
}
