<?php

namespace Thinktomorrow\Chief\Tests\Feature\Relations;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Relations\Relation;
use Thinktomorrow\Chief\Relations\AvailableChildren;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;

class RelationsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ParentFake::migrate();
        ChildFake::migrate();
    }

    /** @test */
    public function a_parent_can_adopt_a_child()
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
    public function a_parent_can_adopt_many_children()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();
        $child2 = ChildFake::create();

        $parent->adoptChild($child);
        $parent->adoptChild($child2);

        $this->assertCount(2, $parent->children());
        $this->assertInstanceOf(ChildFake::class, $parent->children()->first());
    }

    /** @test */
    public function a_child_can_accept_multiple_parents()
    {
        $parent = ParentFake::create();
        $parent2 = ParentFake::create();
        $child = ChildFake::create();

        $child->acceptParent($parent);
        $child->acceptParent($parent2);

        $this->assertCount(2, $child->parents());
        $this->assertInstanceOf(ParentFake::class, $child->parents()->first());
    }

    /** @test */
    public function a_parent_can_reject_a_child()
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
    public function a_child_can_reject_its_parent()
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
    public function a_parent_or_child_can_get_relation_model()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();
        $parent->adoptChild($child);

        $this->assertInstanceOf(Relation::class, $parent->relationWithChild($child));
        $this->assertInstanceOf(Relation::class, $child->relationWithParent($parent));

        // Relation can fetch the related instances as well
        $this->assertInstanceOf(ChildFake::class, $parent->relationWithChild($child)->child);
        $this->assertInstanceOf(ParentFake::class, $child->relationWithParent($parent)->parent);
    }

    /** @test */
    public function child_relations_are_sorted()
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
    public function parent_relations_are_sorted()
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
    public function available_children_for_a_parent_can_be_listed_as_a_collection()
    {
        config()->set('thinktomorrow.chief.relations.children', [
            ChildFake::class,
        ]);

        $parent = ParentFake::create();
        $child = ChildFake::create();
        $child2 = ChildFake::create();
        $parent->adoptChild($child, ['sort' => 2]);
        $parent->adoptChild($child2, ['sort' => 1]);

        $relations = AvailableChildren::forParent($parent)->all();

        $this->assertInstanceOf(Collection::class, $relations);
        $this->assertEquals([$child->id, $child2->id], $relations->pluck('id')->toArray());
    }

    /** @test */
    public function available_children_can_be_page_or_module()
    {
        config()->set('thinktomorrow.chief.relations.children', [
            ChildFake::class,
        ]);

        $parent = ParentFake::create();
        $child = ChildFake::create();
        $child2 = ChildFake::create();
        $parent->adoptChild($child, ['sort' => 2]);
        $parent->adoptChild($child2, ['sort' => 1]);

        $relations = AvailableChildren::forParent($parent)->all();

        $this->assertInstanceOf(Collection::class, $relations);
        $this->assertEquals([$child->id, $child2->id], $relations->pluck('id')->toArray());
    }

    /** @test */
    public function page_that_is_not_listed_as_child_should_not_be_available()
    {
        config()->set('thinktomorrow.chief.relations.children', [
            ChildFake::class,
            ArticlePageFake::class,
        ]);

        // Create models
        $parent = ParentFake::create();
        ChildFake::create();
        ArticlePageFake::create();
        Single::create();

        $availableChildren = AvailableChildren::forParent($parent)->all();

        $this->assertCount(2, $availableChildren);
        $this->assertInstanceOf(ArticlePageFake::class, $availableChildren[0]);
        $this->assertInstanceOf(ChildFake::class, $availableChildren[1]);
    }

    /** @test */
    public function page_as_parent_should_not_listed_as_available()
    {
        config()->set('thinktomorrow.chief.relations.children', [
            ChildFake::class,
            ArticlePageFake::class,
        ]);

        // Create models
        $parent = ArticlePageFake::create();
        ChildFake::create();
        ArticlePageFake::create(['id' => 999]);
        Single::create();

        $availableChildren = AvailableChildren::forParent($parent)->all();

        $this->assertCount(2, $availableChildren);
        $this->assertInstanceOf(ArticlePageFake::class, $availableChildren[0]);
        $this->assertInstanceOf(ChildFake::class, $availableChildren[1]);

        // Assert the proper article is kept as child
        $this->assertEquals(999, $availableChildren[0]->id);
    }
}
