<?php

namespace Thinktomorrow\Chief\Tests\Application\Site\Menu;

use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\Site\Menu\ChiefMenu;
use Thinktomorrow\Chief\Site\Menu\Menu;
use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Vine\NodeCollection;

class MenuTest extends ChiefTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
    }

    /** @test */
    public function it_can_nest_a_menu_item()
    {
        $parent = MenuItem::create(['label' => 'first item']);
        MenuItem::create(['label' => 'second item', 'parent_id' => $parent->id]);

        $collection = ChiefMenu::fromMenuItems()->items();

        $this->assertInstanceof(NodeCollection::class, $collection);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(2, $collection->total());
    }

    /** @test */
    public function it_can_reference_an_internal_page()
    {
        $page = ArticlePage::create(['current_state' => PageState::PUBLISHED]);

        $item = MenuItem::create([
            'menu_type' => 'main',
            'label' => ['nl' => 'second item'],
            'type' => 'internal',
            'owner_type' => $page->getMorphClass(),
            'owner_id' => $page->id,
        ]);

        $collection = ChiefMenu::fromMenuItems()->items();
        $this->assertEquals($item->id, $collection->find('owner_id', $page->id)->id);
    }

    /** @test */
    public function it_can_contain_a_custom_link()
    {
        $item = MenuItem::create([
            'label' => ['nl' => 'second item'],
            'type' => 'custom',
            'url' => ['nl' => 'https://google.com'],
        ]);

        $tree = ChiefMenu::fromMenuItems()->items();

        $this->assertNotNull($tree->find('url', 'https://google.com'));
    }

    /** @test */
    public function it_can_be_rendered_with_a_generic_api()
    {
        $page = ArticlePage::create([
            'current_state' => PageState::PUBLISHED,
        ]);

        $this->updateLinks($page, ['nl' => 'pagelink-nl']);

        MenuItem::create(['type' => 'internal',
                          'label' => 'first item',
                          'owner_type' => $page->getMorphClass(),
                          'owner_id' => $page->id,
        ]);
        MenuItem::create(['type' => 'custom', 'label' => ['nl' => 'second item'], 'url' => ['nl' => 'https://google.com']]);

        $collection = ChiefMenu::fromMenuItems()->items();

        $this->assertCount(2, $collection);
        $check = 0;
        $collection->each(function ($node) use (&$check) {
            $this->assertNotNull($node->label);
            $this->assertNotNull($node->url);
            $check++;
        });

        $this->assertEquals(2, $check);
    }

    /** @test */
    public function menu_item_without_parent_is_considered_top_level()
    {
        $parent = MenuItem::create(['label' => 'first item']);
        MenuItem::create(['label' => 'second item', 'parent_id' => $parent->id]);
        MenuItem::create(['label' => 'last item']);

        $collection = ChiefMenu::fromMenuItems()->items();

        $this->assertInstanceof(NodeCollection::class, $collection);
        $this->assertEquals(2, $collection->count());
        $this->assertEquals(3, $collection->total());
    }

    /** @test */
    public function it_can_be_sorted()
    {
        $parent = MenuItem::create(['label' => ['nl' => 'first item']]);
        MenuItem::create(['label' => ['nl' => 'second item'], 'parent_id' => $parent->id, 'order' => 2]);
        MenuItem::create(['label' => ['nl' => 'last item'], 'parent_id' => $parent->id, 'order' => 1]);

        $collection = ChiefMenu::fromMenuItems()->items();

        $this->assertInstanceof(NodeCollection::class, $collection);
        $this->assertEquals('last item', $collection->first()->children()->first()->label);
    }

    /** @test */
    public function it_can_order_the_menu_items()
    {
        $page = ArticlePage::create(['current_state' => PageState::PUBLISHED]);

        $parent = MenuItem::create(['label' => ['nl' => 'first item']]);
        $second = MenuItem::create(['label' => ['nl' => 'second item'], 'parent_id' => $parent->id, 'order' => 2]);
        $third = MenuItem::create([
            'label' => 'last item',
            'type' => 'internal',
            'owner_type' => $page->getMorphClass(),
            'owner_id' => $page->id,
            'parent_id' => $parent->id,
            'order' => 1,
        ]);

        $collection = ChiefMenu::fromMenuItems()->items();
        $this->assertInstanceof(NodeCollection::class, $collection);

        $this->assertEquals("last item", $collection->first()->children()->first()->label);

        $second->order = 1;
        $second->save();

        $third->order = 2;
        $third->save();

        $collection = ChiefMenu::fromMenuItems()->items();
        $this->assertEquals("second item", $collection->first()->children()->first()->label);
    }

    /** @test */
    public function it_can_get_menu_by_type()
    {
        $page = ArticlePage::create(['current_state' => PageState::PUBLISHED]);

        $first = MenuItem::create(['label' => 'first item', 'type' => 'internal', 'menu_type' => 'main']);
        $second = MenuItem::create(['label' => 'second item', 'type' => 'internal', 'menu_type' => 'main']);
        $third = MenuItem::create(['label' => 'first item', 'type' => 'internal', 'menu_type' => 'footer']);

        $collection = ChiefMenu::fromMenuItems('main')->items();
        $this->assertEquals(2, $collection->total());

        $collection = ChiefMenu::fromMenuItems('footer')->items();
        $this->assertEquals(1, $collection->total());
    }

    /** @test */
    public function it_can_get_all_menu_types()
    {
        $this->assertCount(1, Menu::all());
    }
}
