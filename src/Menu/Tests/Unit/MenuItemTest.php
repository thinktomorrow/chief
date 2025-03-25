<?php

namespace Thinktomorrow\Chief\Menu\Tests\Unit;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
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
        $parent = MenuItem::create(['label.nl' => 'first item']);
        MenuItem::create(['label.nl' => 'second item', 'parent_id' => $parent->id]);

        $collection = MenuItem::tree('main');

        $this->assertInstanceof(NodeCollection::class, $collection);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(2, $collection->total());
    }

    public function test_it_can_reference_an_internal_page()
    {
        $page = $this->setupAndCreateArticle(['custom' => 'artikel titel', 'current_state' => PageState::published]);

        $this->asAdmin()
            ->post(route('chief.back.menuitem.store'), [
                'menu_type' => 'main',
                'type' => 'internal',
                'owner_reference' => $page->modelReference()->getShort(),
                'trans' => [],
            ])->assertSessionHasNoErrors();

        $collection = MenuItem::tree('main');

        $this->assertEquals('artikel titel', $collection->first()->getOwnerLabel());
    }

    public function test_it_takes_page_title_as_label_if_no_label_is_given()
    {
        $page = $this->setupAndCreateArticle(['custom' => 'artikel titel', 'current_state' => PageState::published]);

        $this->asAdmin()
            ->post(route('chief.back.menuitem.store'), [
                'menu_type' => 'main',
                'type' => 'internal',
                'owner_reference' => $page->modelReference()->getShort(),
                'trans' => [],
            ])->assertSessionHasNoErrors();

        $collection = MenuItem::tree('main');

        $this->assertEquals('artikel titel', $collection->first()->getAnyLabel());
    }

    public function test_it_can_contain_a_custom_link()
    {
        $item = MenuItem::create([
            'label' => ['nl' => 'second item'],
            'type' => 'custom',
            'url' => ['nl' => 'https://google.com'],
        ]);

        $collection = MenuItem::tree('main');

        $this->assertNotNull($collection->find(function ($node) {
            return $node->getUrl() == 'https://google.com';
        }));
    }

    public function test_a_menuitem_can_be_nested()
    {
        $parent = MenuItem::create(['type' => 'custom', 'label' => ['nl' => 'foobar'], 'url' => ['nl' => 'http://google.com']]);

        $response = $this->asAdmin()
            ->post(route('chief.back.menuitem.store'), $this->validParams([
                'allow_parent' => true,
                'parent_id' => $parent->id,
            ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menus.show', 'main'));

        $this->assertCount(1, $parent->fresh()->children);
        $this->assertEquals($parent->id, MenuItem::find(2)->parent->id); // Hardcoded assumption that newly created has id of 2
    }

    public function test_menu_item_without_parent_is_considered_top_level()
    {
        $parent = MenuItem::create(['label' => 'first item']);
        MenuItem::create(['label' => 'second item', 'parent_id' => $parent->id]);
        MenuItem::create(['label' => 'last item']);

        $collection = MenuItem::tree('main');

        $this->assertInstanceof(NodeCollection::class, $collection);
        $this->assertEquals(2, $collection->count());
        $this->assertEquals(3, $collection->total());
    }

    public function test_it_can_be_sorted()
    {
        $parent = MenuItem::create(['label' => ['nl' => 'first item']]);
        MenuItem::create(['label' => ['nl' => 'second item'], 'parent_id' => $parent->id, 'order' => 2]);
        MenuItem::create(['label' => ['nl' => 'last item'], 'parent_id' => $parent->id, 'order' => 1]);

        $collection = MenuItem::tree('main');

        $this->assertInstanceof(NodeCollection::class, $collection);
        $this->assertEquals('last item', $collection->first()->getChildNodes()->first()->getLabel());
    }

    public function test_it_can_order_the_menu_items()
    {
        $parent = MenuItem::create(['label' => ['nl' => 'first item']]);
        $second = MenuItem::create(['label' => ['nl' => 'second item'], 'parent_id' => $parent->id, 'order' => 2]);
        $third = MenuItem::create(['label' => 'last item', 'parent_id' => $parent->id, 'order' => 1]);

        $collection = MenuItem::tree('main');
        $this->assertInstanceof(NodeCollection::class, $collection);

        $this->assertEquals('last item', $collection->first()->getChildNodes()->first()->getLabel());

        $second->order = 1;
        $second->save();

        $third->order = 2;
        $third->save();

        $collection = MenuItem::tree('main');
        $this->assertEquals('second item', $collection->first()->getChildNodes()->first()->getLabel());
    }
}
