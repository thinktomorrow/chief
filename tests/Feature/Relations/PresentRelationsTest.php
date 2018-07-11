<?php

namespace Thinktomorrow\Chief\Tests\Feature\Relations;

use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\TestCase;

class PresentRelationsTest extends TestCase
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
    public function a_child_can_be_presented_in_regards_to_its_parent()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();
        $parent->adoptChild($child);

        $render = $child->presentForParent($parent, $child->relationWithParent($parent));
        $this->assertEquals('<div>child '.$child->id.' view for parent '.$parent->id.'</div>', $render);
    }

    /** @test */
    public function a_parent_has_a_convenience_method_for_presenting_all_children()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();
        $child2 = ChildFake::create();
        $parent->adoptChild($child);
        $parent->adoptChild($child2);

        $render = $parent->presentChildren();
        $this->assertEquals(collect([
            '<div>child '.$child->id.' view for parent '.$parent->id.'</div>',
             '<div>child '.$child2->id.' view for parent '.$parent->id.'</div>'
        ]), $render);
    }
}
