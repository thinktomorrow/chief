<?php

namespace Thinktomorrow\Chief\Tests\Feature\Relations;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\TestCase;

class PageRelationsTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /** @test */
    function a_page_can_have_a_relation_with_another_page()
    {
        $parent = Page::create();
        $child = Page::create();

        $parent->adoptChild($child);

        $this->assertCount(1, $parent->children());
        $this->assertInstanceOf(Page::class, $parent->children()->first());
        $this->assertEquals($child->id, $parent->children()->first()->id);

        $this->assertCount(1, $child->parents());
        $this->assertInstanceOf(Page::class, $child->parents()->first());
        $this->assertEquals($parent->id, $child->parents()->first()->id);
    }

    /** @test */
    function a_page_can_have_a_relation_with_a_module()
    {
        ParentFake::migrate();
        ChildFake::migrate();

        $page = Page::create();
        $parent = ParentFake::create();
        $child = ChildFake::create();

        $page->adoptChild($child);
        $page->acceptParent($parent);

        $this->assertCount(1, $page->children());
        $this->assertEquals($parent->id, $page->parents()->first()->id);
        $this->assertInstanceOf(ParentFake::class, $page->parents()->first());

        $this->assertCount(1, $page->parents());
        $this->assertInstanceOf(ChildFake::class, $page->children()->first());
        $this->assertEquals($child->id, $page->children()->first()->id);
    }
}