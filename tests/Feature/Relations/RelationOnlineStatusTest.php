<?php

namespace Thinktomorrow\Chief\Tests\Feature\Relations;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeActingAsChild;

class RelationOnlineStatusTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ParentFake::migrate();
        ChildFake::migrate();
    }

    /** @test */
    public function a_relation_is_default_online()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();

        $parent->adoptChild($child);

        $this->assertTrue($parent->children()->first()->relation->isOnline());
    }

    /** @test */
    public function it_can_be_put_offline()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();

        $parent->adoptChild($child);
        $this->assertTrue($parent->children()->first()->relation->isOnline());

        $this->asAdmin()->put(route('chief.api.relation.status'), [
            'parent_type' => $parent->getMorphClass(),
            'parent_id' => $parent->id,
            'child_type' => $child->getMorphClass(),
            'child_id' => $child->id,
            'online_status' => 0,
        ]);

        $this->assertTrue($parent->freshChildren()->first()->relation->isOffline());
    }

    /** @test */
    public function it_can_be_put_online()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();

        $parent->adoptChild($child);
        $this->assertTrue($parent->children()->first()->relation->isOnline());

        $this->asAdmin()->put(route('chief.api.relation.status'), [
            'parent_type' => $parent->getMorphClass(),
            'parent_id' => $parent->id,
            'child_type' => $child->getMorphClass(),
            'child_id' => $child->id,
            'online_status' => 1,
        ]);

        $this->assertTrue($parent->freshChildren()->first()->relation->isOnline());
    }

    /** @test */
    public function it_only_renders_children_that_are_online()
    {
        ManagedModelFakeActingAsChild::migrateUp();

        $parent = ParentFake::create();
        $child = ChildFake::create();
        $child2 = ManagedModelFakeActingAsChild::create(); // Other model so it will not be rendered as set.

        $parent->adoptChild($child);
        $parent->adoptChild($child2);

        $parent->breakChildRelationCache();
        $this->assertCount(2, $parent->presentChildren());

        $this->asAdmin()->put(route('chief.api.relation.status'), [
            'parent_type' => $parent->getMorphClass(),
            'parent_id' => $parent->id,
            'child_type' => $child->getMorphClass(),
            'child_id' => $child->id,
            'online_status' => 0,
        ]);

        $parent->breakChildRelationCache();
        $this->assertCount(1, $parent->presentChildren());
    }

}
