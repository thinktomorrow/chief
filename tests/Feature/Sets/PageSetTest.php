<?php

namespace Thinktomorrow\Chief\Tests\Feature\Sets;

use Thinktomorrow\Chief\Sets\Set;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Sets\SetReference;
use Illuminate\Pagination\LengthAwarePaginator;
use Thinktomorrow\Chief\Sets\StoredSetReference;
use Thinktomorrow\Chief\Tests\Fakes\AgendaPageFake;

class PageSetTest extends TestCase
{
    private $parent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('thinktomorrow.chief.sets', [
            'foobar'   => [
                'action' => DummySetRepository::class.'@all',
                'parameters' => [2],
            ],
        ]);

        $this->parent = Single::create();
    }

    /** @test */
    public function it_can_store_a_set_reference()
    {
        $stored_set_ref = (new SetReference('key', 'foobar@all', [5]))->store();

        $this->assertInstanceOf(StoredSetReference::class, $stored_set_ref);
        $this->assertEquals('foobar@all', $stored_set_ref->action);
        $this->assertEquals([5], $stored_set_ref->parameters);
    }

    /** @test */
    public function it_guards_against_non_existing_class_reference()
    {
        $this->expectException(\InvalidArgumentException::class);

        $set_ref = (new SetReference('key', 'foobar@all', [5]));
        $set_ref->toSet($this->parent);
    }

    /** @test */
    public function it_guards_against_non_existing_method_reference()
    {
        $this->expectException(\InvalidArgumentException::class);

        $set_ref = (new SetReference('key', DummySetRepository::class.'@unknown', [5]));
        $set_ref->toSet($this->parent);
    }

    /** @test */
    public function a_stored_reference_must_refer_to_existing_reference()
    {
        $this->expectException(\Exception::class);

        $stored_set_ref = (new SetReference('key', DummySetRepository::class.'@all', [5]))->store();
        $stored_set_ref->toSet($this->parent);
    }

    /** @test */
    public function it_can_run_a_set_query()
    {
        AgendaPageFake::create();

        $stored_set_ref = (new SetReference('foobar', DummySetRepository::class.'@all', [5]))->store();
        $set = $stored_set_ref->toSet($this->parent);

        $this->assertInstanceOf(Set::class, $set);
        $this->assertInstanceOf(Collection::class, $set);
        $this->assertCount(2, $set); // agendapagefake + parent single
    }

    /** @test */
    public function it_can_run_a_stored_set_reference()
    {
        AgendaPageFake::create();

        $set_ref = (new SetReference('key', DummySetRepository::class.'@all', [5]));
        $set = $set_ref->toSet($this->parent);

        $this->assertInstanceOf(Set::class, $set);
        $this->assertInstanceOf(Collection::class, $set);
        $this->assertCount(2, $set); // agendapagefake + parent single
    }

    /** @test */
    public function it_can_present_itself_with_a_human_readable_label()
    {
        $set_ref = (new SetReference('key', DummySetRepository::class.'@all', [5], 'foobar'));

        $this->assertEquals('foobar', $set_ref->flatReferenceLabel());
    }

    /** @test */
    public function it_can_find_a_set_ref()
    {
        $set_ref = SetReference::find('foobar');

        $this->assertInstanceOf(SetReference::class, $set_ref);
        $this->assertEquals('foobar', $set_ref->key());
        $this->assertEquals([2], $set_ref->store()->parameters);
    }

    /** @test */
    public function it_can_use_parameters()
    {
        AgendaPageFake::create();
        AgendaPageFake::create();
        AgendaPageFake::create();

        $set_ref = SetReference::find('foobar');

        // Parameter of 2 for query limit is passed.
        $this->assertCount(2, $set_ref->toSet($this->parent));
    }

    /** @test */
    public function it_can_be_paginated()
    {
        AgendaPageFake::create();
        AgendaPageFake::create();
        AgendaPageFake::create();

        $set_ref = (new SetReference('key', DummySetRepository::class.'@all', [5], 'foobar'));
        $paginatedSet = $set_ref->toSet($this->parent)->paginate(1);

        $this->assertInstanceOf(LengthAwarePaginator::class, $paginatedSet);
        $this->assertCount(1, $paginatedSet);
    }

    /** @test */
    public function it_can_be_simple_paginated()
    {
        AgendaPageFake::create();
        AgendaPageFake::create();
        AgendaPageFake::create();

        $set_ref = (new SetReference('key', DummySetRepository::class.'@all', [5], 'foobar'));
        $paginatedSet = $set_ref->toSet($this->parent)->simplePaginate(1);

        $this->assertInstanceOf(Paginator::class, $paginatedSet);
        $this->assertCount(1, $paginatedSet);
    }
}
