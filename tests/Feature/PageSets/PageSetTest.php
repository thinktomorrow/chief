<?php

namespace Thinktomorrow\Chief\Tests\Feature\PageSets;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\PageSets\PageSet;
use Thinktomorrow\Chief\PageSets\StoredPageSetReference;
use Thinktomorrow\Chief\PageSets\PageSetReference;
use Thinktomorrow\Chief\Tests\Fakes\AgendaPageFake;
use Thinktomorrow\Chief\Tests\TestCase;

class PageSetTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->app['config']->set('thinktomorrow.chief.collections', [
            'agenda' => AgendaPageFake::class,
        ]);

        $this->app['config']->set('thinktomorrow.chief.pagesets', [
            'foobar'   => [
                'action' => DummyPageSetRepository::class.'@all',
                'parameters' => [2],
            ],
        ]);
    }

    /** @test */
    function it_can_store_a_pageset_reference()
    {
        $stored_pageset_ref = (new PageSetReference('key','foobar@all', [5]))->store();

        $this->assertInstanceOf(StoredPageSetReference::class, $stored_pageset_ref);
        $this->assertEquals('foobar@all', $stored_pageset_ref->action);
        $this->assertEquals([5], $stored_pageset_ref->parameters);
    }

    /** @test */
    function it_guards_against_non_existing_class_reference()
    {
        $this->expectException(\InvalidArgumentException::class);

        $pageset_ref = (new PageSetReference('key','foobar@all', [5]));
        $pageset_ref->toPageSet();
    }

    /** @test */
    function it_guards_against_non_existing_method_reference()
    {
        $this->expectException(\InvalidArgumentException::class);

        $pageset_ref = (new PageSetReference('key',DummyPageSetRepository::class.'@unknown', [5]));
        $pageset_ref->toPageSet();
    }

    /** @test */
    function it_can_run_a_pageset_query()
    {
        AgendaPageFake::create(['collection' => 'agenda']);

        $stored_pageset_ref = (new PageSetReference('key',DummyPageSetRepository::class.'@all', [5]))->store();
        $pageset = $stored_pageset_ref->toPageSet();

        $this->assertInstanceOf(PageSet::class, $pageset);
        $this->assertInstanceOf(Collection::class, $pageset);
        $this->assertCount(1, $pageset);
    }

    /** @test */
    function it_can_run_a_stored_pageset_reference()
    {
        AgendaPageFake::create(['collection' => 'agenda']);

        $pageset_ref = (new PageSetReference('key',DummyPageSetRepository::class.'@all', [5]));
        $pageset = $pageset_ref->toPageSet();

        $this->assertInstanceOf(PageSet::class, $pageset);
        $this->assertInstanceOf(Collection::class, $pageset);
        $this->assertCount(1, $pageset);
    }

    /** @test */
    function it_can_present_itself_with_a_human_readable_label()
    {
        $pageset_ref = (new PageSetReference('key',DummyPageSetRepository::class.'@all', [5], 'foobar'));

        $this->assertEquals('foobar', $pageset_ref->flatReferenceLabel());
    }

    /** @test */
    function it_can_find_a_pageset_ref()
    {
        $pageset_ref = PageSetReference::find('foobar');

        $this->assertInstanceOf(PageSetReference::class, $pageset_ref);
        $this->assertEquals('foobar', $pageset_ref->key());
        $this->assertEquals([2], $pageset_ref->store()->parameters);
    }

    /** @test */
    function it_can_use_parameters()
    {
        AgendaPageFake::create(['collection' => 'agenda']);
        AgendaPageFake::create(['collection' => 'agenda']);
        AgendaPageFake::create(['collection' => 'agenda']);

        $pageset_ref = PageSetReference::find('foobar');

        // Parameter of 2 for query limit is passed.
        $this->assertCount(2, $pageset_ref->toPageSet());
    }


}
