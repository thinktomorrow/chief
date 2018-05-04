<?php

namespace Chief\Tests\Feature\Relations;

use Chief\Pages\Page;
use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;
use Chief\Users\User;
use Chief\Pages\Application\CreatePage;
use Illuminate\Support\Facades\DB;

class CreatePageTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /** @test */
    function a_parent_can_have_a_child()
    {
        ParentFake::migrate();
        ChildFake::migrate();

        $parent = ParentFake::create();
        $child = ChildFake::create();

        $parent->adoptChild($child);

        $this->assertCount(1, $parent->children);
        $this->assertEquals($child->id, $parent->children->first()->id);

        $this->assertCount(1, $child->parents);
        $this->assertEquals($parent->id, $child->parents->first()->id);
    }

    /** @test */
    function a_parent_can_have_multiple_children()
    {
        ParentFake::migrate();
        ChildFake::migrate();

        $parent = ParentFake::create();
        $child = ChildFake::create();
        $child2 = ChildFake::create();

        DB::enableQueryLog();
        $parent->adoptChild($child);
        $parent->adoptChild($child2);
        $parent->children;
        dd(DB::getQueryLog());
        $this->assertCount(2, $parent->children);
    }
}