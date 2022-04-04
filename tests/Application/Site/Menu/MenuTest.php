<?php

namespace Thinktomorrow\Chief\Tests\Application\Site\Menu;

use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\Site\Menu\ChiefMenuFactory;
use Thinktomorrow\Chief\Site\Menu\Menu;
use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Thinktomorrow\Chief\Site\Menu\Tree\MenuItemNode;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Vine\NodeCollection;

class MenuTest extends ChiefTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_nest_a_menu_item()
    {
        $parent = MenuItem::create(['label.nl' => 'first item']);
        MenuItem::create(['label.nl' => 'second item', 'parent_id' => $parent->id]);

        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'nl');

        $this->assertInstanceof(NodeCollection::class, $collection);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(2, $collection->total());
    }

    /** @test */
    public function it_can_reference_an_internal_page()
    {
        $page = $this->setupAndCreateArticle(['custom' => 'artikel titel', 'current_state' => PageState::PUBLISHED]);

        $this->asAdmin()
            ->post(route('chief.back.menuitem.store'), [
                'menu_type' => 'main',
                'type' => 'internal',
                'owner_reference' => $page->modelReference()->getShort(),
                'trans' => [],
            ])->assertSessionHasNoErrors();

        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'nl');

        $this->assertEquals('artikel titel', $collection->first()->getAdminUrlLabel());
    }

    /** @test */
    public function it_takes_page_title_as_label_if_no_label_is_given()
    {
        $page = $this->setupAndCreateArticle(['custom' => 'artikel titel', 'current_state' => PageState::PUBLISHED]);

        $this->asAdmin()
            ->post(route('chief.back.menuitem.store'), [
                'menu_type' => 'main',
                'type' => 'internal',
                'owner_reference' => $page->modelReference()->getShort(),
                'trans' => [],
            ])->assertSessionHasNoErrors();

        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'nl');

        $this->assertEquals('artikel titel', $collection->first()->getLabel());
    }

    /** @test */
    public function it_can_contain_a_custom_link()
    {
        $item = MenuItem::create([
            'label' => ['nl' => 'second item'],
            'type' => 'custom',
            'url' => ['nl' => 'https://google.com'],
        ]);

        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'nl');

        $this->assertNotNull($collection->find(function ($node) {
            return $node->getUrl() == 'https://google.com';
        }));
    }

    /** @test */
    public function it_can_be_rendered_with_a_generic_api()
    {
        $page = $this->setupAndCreateArticle(['custom.nl' => 'artikel titel', 'current_state' => PageState::PUBLISHED]);
        $this->updateLinks($page, ['nl' => 'pagelink-nl']);

        // Via admin because this way the internal labeling is projected on the menu item record
        $this->asAdmin()
            ->post(route('chief.back.menuitem.store'), [
                'menu_type' => 'main',
                'type' => 'internal',
                'owner_reference' => $page->modelReference()->getShort(),
                'trans' => [
                    'nl' => [
                        'label' => 'first item',
                    ],
                ],
            ])->assertSessionHasNoErrors();

        MenuItem::create(['type' => 'custom', 'label' => ['nl' => 'second item'], 'url' => ['nl' => 'https://google.com']]);

        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'nl');

        $this->assertCount(2, $collection);
        $check = 0;
        $collection->each(function (MenuItemNode $node) use (&$check) {
            $this->assertNotNull($node->getLabel());
            $this->assertNotNull($node->getUrl());
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

        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'nl');

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

        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'nl');

        $this->assertInstanceof(NodeCollection::class, $collection);
        $this->assertEquals('last item', $collection->first()->getChildNodes()->first()->getLabel());
    }

    /** @test */
    public function it_can_order_the_menu_items()
    {
        $parent = MenuItem::create(['label' => ['nl' => 'first item']]);
        $second = MenuItem::create(['label' => ['nl' => 'second item'], 'parent_id' => $parent->id, 'order' => 2]);
        $third = MenuItem::create(['label' => 'last item', 'parent_id' => $parent->id, 'order' => 1]);

        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'nl');
        $this->assertInstanceof(NodeCollection::class, $collection);

        $this->assertEquals("last item", $collection->first()->getChildNodes()->first()->getLabel());

        $second->order = 1;
        $second->save();

        $third->order = 2;
        $third->save();

        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'nl');
        $this->assertEquals("second item", $collection->first()->getChildNodes()->first()->getLabel());
    }

    /** @test */
    public function it_can_get_menu_by_type()
    {
        $first = MenuItem::create(['label' => 'first item', 'menu_type' => 'main']);
        $second = MenuItem::create(['label' => 'second item', 'menu_type' => 'main']);
        $third = MenuItem::create(['label' => 'first item', 'menu_type' => 'footer']);

        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'nl');
        $this->assertEquals(2, $collection->total());

        $collection = app(ChiefMenuFactory::class)->forAdmin('footer', 'nl');
        $this->assertEquals(1, $collection->total());
    }

    /** @test */
    public function it_can_get_all_menu_types()
    {
        $this->assertCount(1, Menu::all());
    }
}
