<?php

namespace Thinktomorrow\Chief\Tests\Feature\Relations;

use Thinktomorrow\Chief\Tests\Fakes\NewsletterModuleFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeActingAsChild;
use Thinktomorrow\Chief\Tests\TestCase;

class PresentRelationsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ParentFake::migrate();
        ChildFake::migrate();
    }

    /** @test */
    public function a_child_can_be_presented()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();
        $parent->adoptChild($child);

        $render = $child->presentForParent($parent, $child->relationWithParent($parent));
        $this->assertEquals('<div>child '.$child->id.' view for parent '.$parent->id.'</div>', $render);
    }

    /** @test */
    public function a_parent_can_present_all_its_children()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();
        $child2 = ChildFake::create();
        $parent->adoptChild($child);
        $parent->adoptChild($child2);

        $render = $parent->presentChildren();

        // They are not 2 separate array items since they are treated by the ParseChildrenForPresentation class as 'non-modules'
        // which means they are set together as if they are a collection... Not sure if this is totally expected behaviour...
        $this->assertEquals(collect([
            '<div>child '.$child->id.' view for parent '.$parent->id.'</div><div>child '.$child2->id.' view for parent '.$parent->id.'</div>'
        ]), $render);
    }

    /** @test */
    public function consecutive_models_of_the_same_class_are_presented_as_a_collection()
    {
        ManagedModelFakeActingAsChild::migrateUp();

        $parent = ParentFake::create();
        $child = ManagedModelFakeActingAsChild::create();
        $child2 = ManagedModelFakeActingAsChild::create();
        $parent->adoptChild($child);
        $parent->adoptChild($child2);

        $render = $parent->presentChildren();
        $this->assertEquals(collect([
            'ManagedModelFakeActingAsChild presentation as childManagedModelFakeActingAsChild presentation as child'
        ]), $render);
    }

    /** @test */
    public function consecutive_modules_are_always_presented_on_their_own()
    {
        $parent = ParentFake::create();
        $child = NewsletterModuleFake::create(['slug' => 'newsletter-1', 'content' => 'nieuwsbrief-1']);
        $child2 = NewsletterModuleFake::create(['slug' => 'newsletter-2', 'content' => 'nieuwsbrief-2']);

        $parent->adoptChild($child);
        $parent->adoptChild($child2);

        $this->assertEquals(collect([
            'nieuwsbrief-1',
            'nieuwsbrief-2',
        ]), $parent->presentChildren());
    }
}
