<?php

namespace Chief\Tests\Feature\Relations;

use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;

class RelationsTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();

        ParentFake::migrate();
        ChildFake::migrate();
    }

    /** @test */
    function a_parent_can_have_a_child()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();

        $parent->adoptChild($child);

        $this->assertCount(1, $parent->children());
        $this->assertEquals($child->id, $parent->children()->first()->id);

        $this->assertCount(1, $child->parents());
        $this->assertEquals($parent->id, $child->parents()->first()->id);
    }

    /** @test */
    function a_parent_can_have_multiple_children()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();
        $child2 = ChildFake::create();

        $parent->adoptChild($child);
        $parent->adoptChild($child2);

        $this->assertCount(2, $parent->children());
        $this->assertInstanceOf(ChildFake::class,$parent->children()->first());
    }

    /** @test */
    function a_child_can_have_multiple_parents()
    {
        $parent = ParentFake::create();
        $parent2 = ParentFake::create();
        $child = ChildFake::create();

        $child->acceptParent($parent);
        $child->acceptParent($parent2);

        $this->assertCount(2, $child->parents());
        $this->assertInstanceOf(ParentFake::class,$child->parents()->first());
    }
}