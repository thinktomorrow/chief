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

        $this->app['config']->set('thinktomorrow.chief.collections', [
            'statics' => Page::class,
            'articles' => ArticleFake::class,
        ]);
    }

    /** @test */
    public function it_can_nest_a_menu_item()
    {
        $first  = MenuItem::create(['label:nl' => 'first item']);
        $second = MenuItem::create(['label:nl' => 'second item', 'parent_id' => $first->id]);

        $collection = ChiefMenu::fromArray([$first, $second])->items();

        $this->assertInstanceof(NodeCollection::class, $collection);
        $this->assertEquals(1, $collection->count());
        $this->assertEquals(2, $collection->total());
    }

    /** @test */
    public function it_can_reference_an_internal_page()
    {
        $page   = factory(Page::class)->create([
            'slug'      => 'foobar',
            'published' => 1
        ]);
        
        $first  = MenuItem::create(['label:nl' => 'first item', 'type' => 'internal']);
        $second = MenuItem::create(['label:nl' => 'second item', 'type' => 'internal', 'page_id' => $page->id, 'parent_id' => $first->id]);

        $collection = ChiefMenu::fromMenuItems()->items();
        $this->assertEquals($second->id, $collection->find('page_id', $page->id)->id);
    }

    /** @test */
    public function it_can_be_a_custom_link()
    {
        $page   = factory(Page::class, 3)->create([
            'collection'    => 'article',
            'published'     => 1
        ]);
        
        $first  = MenuItem::create(['label:nl' => 'first item', 'type' => 'internal']);
        $second = MenuItem::create(['label:nl' => 'second item', 'type' => 'custom', 'url' => 'https://google.com', 'parent_id' => $first->id]);

        $tree = ChiefMenu::fromArray([$first, $second])->items();

        $this->assertNotNull($tree->find('url', 'https://google.com'));
    }

    /** @test */
    public function it_can_reference_a_collection_of_pages()
    {
        factory(Page::class, 3)->create([
            'collection'    => 'articles',
            'published'     => 1
        ]);

        MenuItem::create(['type' => 'collection', 'collection_type' => 'articles', 'label:nl' => 'titel van articles']);

        $collection = ChiefMenu::fromMenuItems()->items();

        $this->assertEquals(4, $collection->total());
        $this->assertEquals(3, $collection->first()->children()->count());
    }
    
    /** @test */
    public function menu_item_without_parent_is_considered_top_level()
    {
        $first  = MenuItem::create(['label:nl' => 'first item']);
        $second = MenuItem::create(['label:nl' => 'second item', 'parent_id' => $first->id]);
        $third = MenuItem::create(['label:nl' => 'last item']);

        $collection = ChiefMenu::fromArray([$first, $second, $third])->items();

        $this->assertInstanceof(NodeCollection::class, $collection);
        $this->assertEquals(2, $collection->count());
        $this->assertEquals(3, $collection->total());
    }
    
    /** @test */
    public function it_can_be_sorted()
    {
        app()->setLocale('nl');
        $first  = MenuItem::create(['label:nl' => 'first item']);
        $second = MenuItem::create(['label:nl' => 'second item', 'parent_id' => $first->id, 'order' => 2]);
        $third  = MenuItem::create(['label:nl' => 'last item', 'parent_id' => $first->id, 'order' => 1]);
        
        $collection = ChiefMenu::fromMenuItems()->items();
        
        $this->assertInstanceof(NodeCollection::class, $collection);
        $this->assertEquals('last item', $collection->first()->children()->first()->label);
    }
    
    /** @test */
    public function if_a_page_is_hidden_it_is_not_shown_in_menu()
    {
        app()->setLocale('nl');
        $first  = MenuItem::create(['label:nl' => 'first item']);
        $second = MenuItem::create(['label:nl' => 'second item', 'parent_id' => $first->id, 'order' => 2]);
        $third  = MenuItem::create(['label:nl' => 'last item', 'parent_id' => $first->id, 'order' => 1, 'hidden_in_menu' => 1]);
        
        $collection = ChiefMenu::fromMenuItems()->items();
        
        $this->assertInstanceof(NodeCollection::class, $collection);
        $this->assertEquals(2, $collection->total());
    }
    
    /** @test */
    public function it_can_have_a_custom_value()
    {
        // test it out
        // Column icon toegevoegd per project -> wordt automatisch meegepakt
    }

    /** @test */
    public function first_menu_item_is_the_toggle()
    {
        // test it out
    }

    /** @test */
    public function if_url_is_external_the_link_will_contain_target_blank()
    {
        // test it out
    }
}

class ArticleFake extends Page
{
}
