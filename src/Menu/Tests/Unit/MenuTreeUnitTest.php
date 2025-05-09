<?php

namespace Thinktomorrow\Chief\Menu\Tests\Unit;

use Thinktomorrow\Chief\Menu\App\Queries\MenuTree;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class MenuTreeUnitTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_can_get_menu_by_type()
    {
        $menu = Menu::create(['type' => 'main', 'allowed_sites' => ['nl']]);
        $menuFooter = Menu::create(['type' => 'footer', 'allowed_sites' => ['nl']]);

        MenuItem::create(['menu_id' => $menu->id, 'label' => 'first item']);
        MenuItem::create(['menu_id' => $menu->id, 'label' => 'second item']);
        MenuItem::create(['menu_id' => $menuFooter->id, 'label' => 'first item']);

        $collection = MenuTree::byMenu($menu->id);
        $this->assertEquals(2, $collection->total());

        $collection = MenuTree::byMenu($menuFooter->id);
        $this->assertEquals(1, $collection->total());
    }
}
