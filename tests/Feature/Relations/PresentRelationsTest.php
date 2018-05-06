<?php

namespace Chief\Tests\Feature\Relations;

use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;

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
    function a_child_can_be_presented_in_regards_to_its_parent()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();
        $parent->adoptChild($child);

        $render = $child->presentForParent($parent, $child->relationWithParent($parent));
        $this->assertEquals('<rendered-view>child '.$child->id.' view for parent '.$parent->id.'</rendered-view>', $render);
    }

    /** @test */
    function a_parent_can_be_presented_in_regards_to_its_child()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();
        $parent->adoptChild($child);

        $render = $parent->presentForChild($child, $parent->relationWithChild($child));
        $this->assertEquals('<rendered-view>parent '.$parent->id.' view for child '.$child->id.'</rendered-view>', $render);
    }

    /** @test */
    function a_parent_has_a_convenience_method_for_presenting_all_children()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();
        $child2 = ChildFake::create();
        $parent->adoptChild($child);
        $parent->adoptChild($child2);

        $render = $parent->presentChildren();
        $this->assertEquals(collect([
            '<rendered-view>child '.$child->id.' view for parent '.$parent->id.'</rendered-view>',
             '<rendered-view>child '.$child2->id.' view for parent '.$parent->id.'</rendered-view>'
        ]), $render );
    }
}