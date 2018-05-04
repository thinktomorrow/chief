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
    function a_page_can_have_a_relation_with_another_page()
    {
        $parent = Page::create();
        $child = Page::create();

        $parent->adoptChild($child);

        $this->assertCount(1, $parent->children);
        $this->assertEquals($child->id, $parent->children->first()->id);

        $this->assertCount(1, $child->parents);
        $this->assertEquals($parent->id, $child->parents->first()->id);
    }
}