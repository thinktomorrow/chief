<?php

namespace Thinktomorrow\Chief\Menu\Tests\App\Queries;

use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Menu\MenuItemStatus;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class MenuTreeTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_it_can_render_a_tree()
    {
        $menu = Menu::create([
            'type' => 'main',
            'sites' => ['nl'],
        ]);

        $firstMenuitem = MenuItem::create([
            'menu_id' => $menu->id,
            'label' => ['nl' => 'first item'],
            'type' => 'custom',
            'url' => ['nl' => 'https://google.com'],
        ]);

        MenuItem::create([
            'menu_id' => $menu->id,
            'label' => ['nl' => 'second item'],
            'type' => 'custom',
            'url' => ['nl' => 'https://google.com'],
        ]);

        $childItem = MenuItem::create([
            'menu_id' => $menu->id,
            'parent_id' => $firstMenuitem->id,
            'label' => ['nl' => 'second item'],
            'type' => 'custom',
            'url' => ['nl' => 'https://google.com'],
        ]);

        $collection = chiefmenu('main');

        $this->assertCount(2, $collection);
        $this->assertEquals($childItem->id, $collection->first()->children->first()->id);
    }

    public function test_it_can_render_by_site(): void
    {
        $menu = Menu::create([
            'type' => 'main',
            'sites' => ['nl'],
        ]);

        MenuItem::create([
            'menu_id' => $menu->id,
            'label' => ['nl' => 'first item'],
            'type' => 'custom',
            'url' => ['nl' => 'https://google.com'],
        ]);

        $this->assertCount(1, chiefmenu('main', 'nl'));
        $this->assertCount(0, chiefmenu('main', 'en'));
    }

    public function test_rendering_for_site_does_not_include_offline_items()
    {
        $menu = Menu::create([
            'type' => 'main',
            'sites' => ['nl'],
        ]);

        MenuItem::create([
            'menu_id' => $menu->id,
            'label' => ['nl' => 'first item'],
            'type' => 'custom',
            'status' => MenuItemStatus::offline->value,
            'url' => ['nl' => 'https://google.com'],
        ]);

        MenuItem::create([
            'menu_id' => $menu->id,
            'label' => ['nl' => 'second item'],
            'type' => 'custom',
            'url' => ['nl' => 'https://google.com'],
        ]);

        $collection = chiefmenu('main');

        $this->assertCount(1, $collection);
    }
}
