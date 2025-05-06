<?php

namespace Thinktomorrow\Chief\Menu\Tests\Unit;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Menu\App\Queries\MenuTree;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Vine\NodeCollection;

class MenuItemTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_can_nest_a_menu_item()
    {
        $menu = Menu::create(['type' => 'main', 'allowed_sites' => ['nl']]);

        $parent = MenuItem::create(['menu_id' => $menu->id, 'label.nl' => 'first item']);
        MenuItem::create(['menu_id' => $menu->id, 'label.nl' => 'second item', 'parent_id' => $parent->id]);

        $collection = MenuTree::byMenu($menu->id);

        $this->assertInstanceof(NodeCollection::class, $collection);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(2, $collection->total());
    }

    public function test_it_can_reference_an_internal_page()
    {
        $menu = Menu::create(['type' => 'main', 'allowed_sites' => ['nl']]);
        $page = $this->setupAndCreateArticle(['title.nl' => 'artikel titel', 'current_state' => PageState::published]);

        $this->asAdmin()
            ->post(route('chief.back.menuitem.store', $menu->id), [
                'menu_type' => 'main',
                'type' => 'internal',
                'owner_reference' => $page->modelReference()->getShort(),
                'trans' => [],
            ])->assertSessionHasNoErrors();

        $collection = MenuTree::byMenu($menu->id);

        $this->assertEquals('artikel titel', $collection->first()->getOwnerLabel());
    }

    public function test_it_takes_page_title_as_label_if_no_label_is_given()
    {
        $menu = Menu::create(['type' => 'main', 'allowed_sites' => ['nl']]);
        $page = $this->setupAndCreateArticle(['title.nl' => 'artikel titel', 'current_state' => PageState::published]);

        $this->asAdmin()
            ->post(route('chief.back.menuitem.store', $menu->id), [
                'menu_type' => 'main',
                'type' => 'internal',
                'owner_reference' => $page->modelReference()->getShort(),
                'trans' => [],
            ])->assertSessionHasNoErrors();

        $collection = MenuTree::byMenu($menu->id);

        $this->assertEquals('artikel titel', $collection->first()->getAnyLabel());
    }

    public function test_it_can_contain_a_custom_link()
    {
        $menu = Menu::create(['type' => 'main', 'allowed_sites' => ['nl']]);
        $item = MenuItem::create([
            'menu_id' => $menu->id,
            'label' => ['nl' => 'second item'],
            'type' => 'custom',
            'url' => ['nl' => 'https://google.com'],
        ]);

        $collection = MenuTree::byMenu($menu->id);

        $this->assertNotNull($collection->find(function ($node) {
            return $node->getUrl() == 'https://google.com';
        }));
    }

    public function test_a_menuitem_can_be_nested()
    {
        $this->disableExceptionHandling();
        $menu = Menu::create(['type' => 'main', 'allowed_sites' => ['nl']]);
        $parent = MenuItem::create(['menu_id' => $menu->id, 'type' => 'custom', 'label' => ['nl' => 'foobar'], 'url' => ['nl' => 'http://google.com']]);

        $response = $this->asAdmin()
            ->post(route('chief.back.menuitem.store', $menu->id), [
                'menu_type' => 'main',
                'type' => 'custom',
                'allow_parent' => true,
                'parent_id' => (string) $parent->id,
                'trans' => [],
            ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menus.show', ['main', $menu->id]));

        $this->assertCount(1, $parent->fresh()->children);
        $this->assertEquals($parent->id, MenuItem::find(2)->parent->id); // Hardcoded assumption that newly created has id of 2
    }

    public function test_menu_item_without_parent_is_considered_top_level()
    {
        $menu = Menu::create(['type' => 'main', 'allowed_sites' => ['nl']]);
        $parent = MenuItem::create(['menu_id' => $menu->id, 'label' => 'first item']);
        MenuItem::create(['menu_id' => $menu->id, 'label' => 'second item', 'parent_id' => $parent->id]);
        MenuItem::create(['menu_id' => $menu->id, 'label' => 'last item']);

        $collection = MenuTree::byMenu($menu->id);

        $this->assertInstanceof(NodeCollection::class, $collection);
        $this->assertEquals(2, $collection->count());
        $this->assertEquals(3, $collection->total());
    }

    public function test_it_can_be_sorted()
    {
        $menu = Menu::create(['type' => 'main', 'allowed_sites' => ['nl']]);
        $parent = MenuItem::create(['menu_id' => $menu->id, 'label' => ['nl' => 'first item']]);
        MenuItem::create(['menu_id' => $menu->id, 'label' => ['nl' => 'second item'], 'parent_id' => $parent->id, 'order' => 2]);
        MenuItem::create(['menu_id' => $menu->id, 'label' => ['nl' => 'last item'], 'parent_id' => $parent->id, 'order' => 1]);

        $collection = MenuTree::byMenu($menu->id);

        $this->assertInstanceof(NodeCollection::class, $collection);
        $this->assertEquals('last item', $collection->first()->getChildNodes()->first()->getLabel());
    }

    public function test_it_can_order_the_menu_items()
    {
        $menu = Menu::create(['type' => 'main', 'allowed_sites' => ['nl']]);
        $parent = MenuItem::create(['menu_id' => $menu->id, 'label' => ['nl' => 'first item']]);
        $second = MenuItem::create(['menu_id' => $menu->id, 'label' => ['nl' => 'second item'], 'parent_id' => $parent->id, 'order' => 2]);
        $third = MenuItem::create(['menu_id' => $menu->id, 'label' => 'last item', 'parent_id' => $parent->id, 'order' => 1]);

        $collection = MenuTree::byMenu($menu->id);
        $this->assertInstanceof(NodeCollection::class, $collection);

        $this->assertEquals('last item', $collection->first()->getChildNodes()->first()->getLabel());

        $second->order = 1;
        $second->save();

        $third->order = 2;
        $third->save();

        $collection = MenuTree::byMenu($menu->id);
        $this->assertEquals('second item', $collection->first()->getChildNodes()->first()->getLabel());
    }
}
