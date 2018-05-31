<?php

namespace Thinktomorrow\Chief\Tests\Unit;

use Thinktomorrow\Chief\Menu\ChiefMenu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\TestCase;
use Vine\NodeCollection;

class MenuTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /** @test */
    function it_can_nest_a_menu_item()
    {
        $first = MenuItem::create(['label:nl' => 'first item']);
        $second = MenuItem::create(['label:nl' => 'second item', 'parent_id' => $first->id]);

        $this->assertInstanceof(NodeCollection::class, (new ChiefMenu([$first, $second]))->items());
//        $this->assertCount(2, (new ChiefMenu)->items()->flatten());
    }

    /** @test */
    function it_can_reference_an_internal_page()
    {
        // test it out


    }

    /** @test */
    function it_can_reference_a_collection_of_pages()
    {
        // test it out
    }

    /** @test */
    function it_can_be_a_custom_link()
    {
        // test it out
    }
    
    /** @test */
    function it_can_have_a_custom_value()
    {
        // test it out
        // Column icon toegevoegd per project -> wordt automatisch meegepakt
    }
    
    /** @test */
    function menu_item_without_parent_is_considered_top_level()
    {
        // test it out
    }
    
    /** @test */
    function first_menu_item_is_the_toggle()
    {
        // test it out
    }

    /** @test */
    function it_can_be_sorted()
    {
        // test it out
    }

    /** @test */
    function if_a_page_is_hidden_it_is_not_shown_in_menu()
    {
        // test it out
    }

    /** @test */
    function if_url_is_external_the_link_will_contain_target_blank()
    {
        // test it out
    }

}
