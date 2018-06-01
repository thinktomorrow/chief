<?php

namespace Thinktomorrow\Chief\Tests\Unit;

use Thinktomorrow\Chief\Menu\ChiefMenu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\TestCase;
use Vine\NodeCollection;
use Thinktomorrow\Chief\Pages\Page;

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
        $first  = MenuItem::create(['label:nl' => 'first item']);
        $second = MenuItem::create(['label:nl' => 'second item', 'parent_id' => $first->id]);

        $tree = (new ChiefMenu([$first, $second]))->items();
        $this->assertInstanceof(NodeCollection::class, $tree);
        $this->assertCount(2, $tree);
    }

    /** @test */
    function it_can_reference_an_internal_page()
    {
        $page   = factory(Page::class)->create([
            'slug'      => 'foobar',
            'published' => 1
        ]);
        
        $first  = MenuItem::create(['label:nl' => 'first item', 'type' => 'internal']);
        $second = MenuItem::create(['label:nl' => 'second item', 'type' => 'internal', 'page_id' => $page->id, 'parent_id' => $first->id]);

        $tree = (new ChiefMenu([$first, $second]))->items();

        $this->assertNotNull($tree->find('page_id', $page->id));
    }

    /** @test */
    function it_can_reference_a_collection_of_pages()
    {
        $this->markTestIncomplete();
        $page   = factory(Page::class, 3)->create([
            'collection'    => 'article',
            'published'     => 1
        ]);
        
        $first  = MenuItem::create(['label:nl' => 'first item', 'type' => 'internal']);
        $second = MenuItem::create(['label:nl' => 'second item', 'type' => 'collection', 'collection_type' => 'article', 'parent_id' => $first->id]);

        $tree = (new ChiefMenu([$first, $second]))->items();

        $this->assertNotNull($tree->find('collection_type', 'article'));
        //Should the tree contain a menuitem for each item in the collection or do we handle this is the presenters?
    }

    /** @test */
    function it_can_be_a_custom_link()
    {
        $page   = factory(Page::class, 3)->create([
            'collection'    => 'article',
            'published'     => 1
        ]);
        
        $first  = MenuItem::create(['label:nl' => 'first item', 'type' => 'internal']);
        $second = MenuItem::create(['label:nl' => 'second item', 'type' => 'custom', 'url' => 'https://google.com', 'parent_id' => $first->id]);

        $tree = (new ChiefMenu([$first, $second]))->items();

        $this->assertNotNull($tree->find('url', 'https://google.com'));
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
