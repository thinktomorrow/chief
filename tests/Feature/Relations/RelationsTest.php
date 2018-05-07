<?php

namespace Chief\Tests\Feature\Relations;

use Chief\Common\Relations\Relation;
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
    function a_parent_can_attach_a_child()
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
    function a_parent_can_attach_multiple_children()
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
    function a_child_can_attach_multiple_parents()
    {
        $parent = ParentFake::create();
        $parent2 = ParentFake::create();
        $child = ChildFake::create();

        $child->acceptParent($parent);
        $child->acceptParent($parent2);

        $this->assertCount(2, $child->parents());
        $this->assertInstanceOf(ParentFake::class,$child->parents()->first());
    }

    /** @test */
    function a_parent_can_detach_a_child()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();

        $parent->adoptChild($child);
        $this->assertCount(1, $parent->children());

        $parent->rejectChild($child);

        // Relationship is no more but Child object itself still exists
        $this->assertCount(0, $parent->children());

        $this->assertNotNull(ChildFake::find($child->id));
        $this->assertCount(0, $child->fresh()->parents());
    }

    /** @test */
    function a_child_can_detach_its_parent()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();

        $child->acceptParent($parent);
        $this->assertCount(1, $child->parents());

        $child->rejectParent($parent);

        $this->assertCount(0, $child->parents());

        $this->assertNotNull(ParentFake::find($parent->id));
        $this->assertCount(0, $parent->fresh()->children());
    }

    /** @test */
    function a_parent_or_child_can_get_relation_model()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();
        $parent->adoptChild($child);

        $this->assertInstanceOf(Relation::class, $parent->relationWithChild($child));
        $this->assertInstanceOf(Relation::class, $child->relationWithParent($parent));
    }

    /** @test */
    function child_relations_are_sorted()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();
        $child2 = ChildFake::create();

        $parent->adoptChild($child, ['sort' => 2]);
        $parent->adoptChild($child2, ['sort' => 1]);

        $this->assertEquals($child2->id, $parent->children()->first()->id);
        $this->assertEquals($child->id, $parent->children()->last()->id);
    }

    /** @test */
    function parent_relations_are_sorted()
    {
        $parent = ParentFake::create();
        $parent2 = ParentFake::create();
        $child = ChildFake::create();

        $child->acceptParent($parent, ['sort' => 2]);
        $child->acceptParent($parent2, ['sort' => 1]);

        $this->assertEquals($parent2->id, $child->parents()->first()->id);
        $this->assertEquals($parent->id, $child->parents()->last()->id);
    }
    
    /** @test */
    function a_parent_or_child_can_return_a_composite_key()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();

        $this->assertEquals($parent->getMorphClass().'@'.$parent->id, $parent->getCompositeKey());
        $this->assertEquals($child->getMorphClass().'@'.$child->id, $child->getCompositeKey());
    }

    /** @test */
    function all_related_children_of_parent_can_be_flattened_as_composite_keys_for_select()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();
        $child2 = ChildFake::create();
        $parent->adoptChild($child, ['sort' => 2]);
        $parent->adoptChild($child2, ['sort' => 1]);

        dd(Relation::availableChildren($parent));
        dd(Relation::availableChildren($parent)->flattenForSelect());

        $this->assertInstanceOf(RelationCollection::class, Relation::availableChildren($parent));
    }
}