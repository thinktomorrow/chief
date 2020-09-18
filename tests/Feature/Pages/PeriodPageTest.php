<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Illuminate\Support\Carbon;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Tests\Fakes\AgendaPageFake;

class PeriodPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        AgendaPageFake::migrateUp();
    }

    /** @test */
    public function can_have_a_period()
    {
        $start_at = Carbon::now()->addDays(1);
        $end_at   = Carbon::now()->addWeeks(1);
        $article  = AgendaPageFake::create(['start_at' => $start_at, 'end_at' => $end_at]);

        $this->assertTrue($start_at->toDateTimeString() == AgendaPageFake::first()->start_at->toDateTimeString());
        $this->assertTrue($end_at->toDateTimeString() == AgendaPageFake::first()->end_at->toDateTimeString());
    }

    /** @test */
    public function it_can_get_only_passed_articles()
    {
        AgendaPageFake::create([
            'start_at'   => Carbon::now()->subWeeks(1),
            'end_at'     => Carbon::now()->subDays(1)
        ]);
        AgendaPageFake::create([
            'start_at'   => Carbon::now()->addDays(1),
            'end_at'     => Carbon::now()->addWeeks(1)
        ]);

        $this->assertCount(1, AgendaPageFake::passed()->get());
    }

    /** @test */
    public function it_can_get_only_upcoming_articles()
    {
        AgendaPageFake::create([
            'start_at'   => Carbon::now()->subWeeks(1),
            'end_at'     => Carbon::now()->subDays(1)
        ]);
        AgendaPageFake::create([
            'start_at'   => Carbon::now()->addDays(1),
            'end_at'     => Carbon::now()->addWeeks(1)
        ]);

        $this->assertCount(1, AgendaPageFake::upcoming()->get());
    }

    /** @test */
    public function it_can_get_only_ongoing_articles()
    {
        AgendaPageFake::create([
            'start_at'   => Carbon::now()->subWeeks(1),
            'end_at'     => Carbon::now()->subDays(1)
        ]);
        AgendaPageFake::create([
            'start_at'   => Carbon::now()->subDays(1),
            'end_at'     => Carbon::now()->addWeeks(1)
        ]);

        $this->assertCount(1, AgendaPageFake::ongoing()->get());
    }

    /** @test */
    public function it_can_articles_sorted_by_start_date()
    {
        $article1 = AgendaPageFake::create([
            'start_at'   => Carbon::now()->subDays(1),
            'end_at'     => Carbon::now()->subDays(1)
        ]);

        $article2 = AgendaPageFake::create([
            'start_at'   => Carbon::now()->subDays(5),
            'end_at'     => Carbon::now()->addWeeks(1)
        ]);

        $this->assertEquals($article2->id, AgendaPageFake::all()->first()->id);
    }
}
