<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Events\FragmentsReordered;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class SortFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    private Quote $fragment;

    private Quote $fragment2;

    private Quote $fragment3;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
        $this->fragment = $this->setupAndCreateQuote($this->owner, [], 0);
        $this->fragment2 = $this->setupAndCreateQuote($this->owner, [], 1, false);
        $this->fragment3 = $this->setupAndCreateQuote($this->owner, [], 2, false);
    }

    public function test_fragments_can_be_sorted()
    {
        $this->asAdmin()->post($this->manager($this->owner)->route('fragments-reorder', $this->owner), [
            'indices' => [
                $this->fragment3->fragmentModel()->id,
                $this->fragment2->fragmentModel()->id,
                $this->fragment->fragmentModel()->id,
            ],
        ]);

        $fragments = app(FragmentRepository::class)->getByOwner($this->owner);

        $this->assertEquals($this->fragment3->fragmentModel()->id, $fragments[0]->fragmentModel()->id);
        $this->assertEquals($this->fragment2->fragmentModel()->id, $fragments[1]->fragmentModel()->id);
        $this->assertEquals($this->fragment->fragmentModel()->id, $fragments[2]->fragmentModel()->id);
    }

    public function test_invalid_fragmentids_are_ignored()
    {
        $this->asAdmin()->post($this->manager($this->owner)->route('fragments-reorder', $this->owner), [
            'indices' => [
                $this->fragment3->fragmentModel()->id,
                'cfv',
                $this->fragment2->fragmentModel()->id,
                '3fw',
                $this->fragment->fragmentModel()->id,
            ],
        ]);

        $fragments = app(FragmentRepository::class)->getByOwner($this->owner);

        $this->assertEquals($this->fragment3->fragmentModel()->id, $fragments[0]->fragmentModel()->id);
        $this->assertEquals($this->fragment2->fragmentModel()->id, $fragments[1]->fragmentModel()->id);
        $this->assertEquals($this->fragment->fragmentModel()->id, $fragments[2]->fragmentModel()->id);
    }

    public function test_it_emits_event_after_sorting()
    {
        Event::fake();

        $this->asAdmin()->post($this->manager($this->owner)->route('fragments-reorder', $this->owner), [
            'indices' => [
                $this->fragment3->fragmentModel()->id,
                $this->fragment2->fragmentModel()->id,
                $this->fragment->fragmentModel()->id,
            ],
        ]);

        Event::assertDispatched(FragmentsReordered::class);
    }
}
