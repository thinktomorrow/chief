<?php

namespace Thinktomorrow\Chief\Tests\Feature\Menu;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Menu\ChiefMenu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\TestCase;
use Vine\NodeCollection;
use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Support\Carbon;

class MenuTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->app['config']->set('thinktomorrow.chief.collections', [
            'singles' => Single::class,
            'articles' => ArticlePageFake::class,
        ]);

        // We expect to have frontend routes for the pages and articles
        Route::get('statics/{slug}', function () {
        })->name('pages.show');
        Route::get('articles/{slug}', function () {
        })->name('articles.show');

        // Make sure we get the proper translations based on the locale
        app()->setLocale('nl');
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
            'collection'    => 'articles',
            'published'     => 1
        ]);

        $first  = MenuItem::create(['label:nl' => 'first item', 'type' => 'internal']);
        $second = MenuItem::create(['label:nl' => 'second item', 'type' => 'custom', 'url' => 'https://google.com', 'parent_id' => $first->id]);

        $tree = ChiefMenu::fromArray([$first, $second])->items();

        $this->assertNotNull($tree->find('url', 'https://google.com'));
    }

    /** @test */
    public function it_does_not_require_a_link()
    {
        $first  = MenuItem::create(['label:nl' => 'first item', 'type' => 'nolink']);

        $tree = ChiefMenu::fromArray([$first])->items();

        $this->assertNull($tree->first()->url);
    }

    /** @test */
    public function it_can_reference_a_collection_of_pages()
    {
        factory(Page::class, 3)->create([
            'collection'    => 'articles',
            'published'     => 1
        ]);

        // Sanity check
        $this->assertCount(3, ArticlePageFake::all());

        // Create main collection menu item - this will hold the collection as children
        $mainMenuItem = MenuItem::create(['type' => 'collection', 'collection_type' => 'articles', 'label:nl' => 'titel van articles', 'url:nl' => 'foobar.com']);

        // Retrieve the menu
        $main = ChiefMenu::fromMenuItems()->items()->first();

        $this->assertEquals(4, ChiefMenu::fromMenuItems()->items()->total());
        $this->assertEquals(3, $main->children()->count());

        // Make sure our labels and urls match
        $this->assertEquals($mainMenuItem->label, $main->label);
        $this->assertEquals($mainMenuItem->url, $main->url);

        foreach (ArticlePageFake::all() as $k => $page) {
            $item = $main->children()[$k];

            $this->assertEquals($page->menuLabel(), $item->label);
            $this->assertEquals($page->menuUrl(), $item->url);
        }
    }

    /** @test */
    public function it_can_be_rendered_with_a_generic_api()
    {
        $page = factory(Page::class)->create([
            'collection' => 'singles',
            'slug'      => 'foobar',
            'published' => 1
        ]);

        factory(Page::class, 3)->create([
            'collection'    => 'articles',
            'published'     => 1
        ]);

        MenuItem::create(['type' => 'internal', 'label:nl' => 'first item', 'page_id' => $page->id]);
        MenuItem::create(['type' => 'custom', 'label:nl' => 'second item', 'url:nl' => 'https://google.com']);
        MenuItem::create(['type' => 'collection', 'collection_type' => 'articles', 'label:nl' => 'titel van articles', 'url:nl' => 'foobar.com/article-index']);

        $collection = ChiefMenu::fromMenuItems()->items();

        $this->assertCount(3, $collection);
        $check = 0;
        $collection->each(function ($node) use (&$check) {
            $this->assertNotNull($node->label);
            $this->assertNotNull($node->url);
            $check++;
        });

        $this->assertEquals(3, $check);
    }

    /** @test */
    public function menu_item_without_parent_is_considered_top_level()
    {
        $first  = MenuItem::create(['label:nl' => 'first item']);
        $second = MenuItem::create(['label:nl' => 'second item', 'parent_id' => $first->id]);
        $third  = MenuItem::create(['label:nl' => 'last item']);

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
        $page = factory(Page::class)->create(['hidden_in_menu' => 1]);
        app()->setLocale('nl');
        $first  = MenuItem::create(['label:nl' => 'first item']);
        $second = MenuItem::create(['label:nl' => 'second item', 'parent_id' => $first->id, 'order' => 2]);
        $third  = MenuItem::create(['label:nl' => 'last item', 'type' => 'internal', 'page_id' =>  $page->id, 'parent_id' => $first->id, 'order' => 1]);

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
    public function if_url_is_external_the_link_will_contain_target_blank()
    {
        // test it out
    }

    /** @test */
    public function it_can_order_the_menu_items()
    {
        $page = factory(Page::class)->create();
        app()->setLocale('nl');
        $first  = MenuItem::create(['label:nl' => 'first item']);
        $second = MenuItem::create(['label:nl' => 'second item', 'parent_id' => $first->id, 'order' => 2]);
        $third  = MenuItem::create(['label:nl' => 'last item', 'type' => 'internal', 'page_id' =>  $page->id, 'parent_id' => $first->id, 'order' => 1]);

        $collection = ChiefMenu::fromMenuItems()->items();
        $this->assertInstanceof(NodeCollection::class, $collection);

        $this->assertEquals("last item", $collection->first()->children()->first()->label);

        $second->order  = 1;
        $third->order   = 2;
        $second->save();
        $third->save();

        $collection = ChiefMenu::fromMenuItems()->items();
        $this->assertEquals("second item", $collection->first()->children()->first()->label);
    }

    /** @test */
    public function it_can_show_create_menu()
    {
        $this->setUpDefaultAuthorization();

        factory(Page::class)->create([
            'published'     => 0,
            'created_at'    => Carbon::now()->subDays(3)
        ]);
        factory(Page::class)->create([
            'published'     => 1,
            'created_at'    => Carbon::now()->subDays(1)
        ]);

        $response = $this->asAdmin()
            ->get(route('chief.back.menu.create'));

        $response->assertStatus(200);

        $pages = $this->getResponseData($response, 'pages');

        $this->assertCount(1, $pages);
    }
}
