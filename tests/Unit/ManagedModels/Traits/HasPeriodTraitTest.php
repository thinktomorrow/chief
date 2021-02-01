<?php

namespace Thinktomorrow\Chief\Tests\Unit\ManagedModels\Traits;

use Illuminate\Support\Carbon;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class HasPeriodTraitTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
    }

    /** @test */
    public function can_have_a_period()
    {
        $start_at = Carbon::now()->addDays(1);
        $end_at   = Carbon::now()->addWeeks(1);
        $article  = ArticlePage::create(['start_at' => $start_at, 'end_at' => $end_at]);

        $this->assertTrue($start_at->toDateTimeString() == ArticlePage::first()->start_at->toDateTimeString());
        $this->assertTrue($end_at->toDateTimeString() == ArticlePage::first()->end_at->toDateTimeString());
    }

    /** @test */
    public function it_can_get_only_passed_articles()
    {
        ArticlePage::create([
            'start_at'   => Carbon::now()->subWeeks(1),
            'end_at'     => Carbon::now()->subDays(1)
        ]);
        ArticlePage::create([
            'start_at'   => Carbon::now()->addDays(1),
            'end_at'     => Carbon::now()->addWeeks(1)
        ]);

        $this->assertCount(1, ArticlePage::passed()->get());
    }

    /** @test */
    public function it_can_get_only_upcoming_articles()
    {
        ArticlePage::create([
            'start_at'   => Carbon::now()->subWeeks(1),
            'end_at'     => Carbon::now()->subDays(1)
        ]);
        ArticlePage::create([
            'start_at'   => Carbon::now()->addDays(1),
            'end_at'     => Carbon::now()->addWeeks(1)
        ]);

        $this->assertCount(1, ArticlePage::upcoming()->get());
    }

    /** @test */
    public function it_can_get_only_ongoing_articles()
    {
        ArticlePage::create([
            'start_at'   => Carbon::now()->subWeeks(1),
            'end_at'     => Carbon::now()->subDays(1)
        ]);
        ArticlePage::create([
            'start_at'   => Carbon::now()->subDays(1),
            'end_at'     => Carbon::now()->addWeeks(1)
        ]);

        $this->assertCount(1, ArticlePage::ongoing()->get());
    }

    /** @test */
    public function it_can_articles_sorted_by_start_date()
    {
        $article1 = ArticlePage::create([
            'start_at'   => Carbon::now()->subDays(1),
            'end_at'     => Carbon::now()->subDays(1)
        ]);

        $article2 = ArticlePage::create([
            'start_at'   => Carbon::now()->subDays(5),
            'end_at'     => Carbon::now()->addWeeks(1)
        ]);

        $this->assertEquals($article2->id, ArticlePage::all()->first()->id);
    }
}
