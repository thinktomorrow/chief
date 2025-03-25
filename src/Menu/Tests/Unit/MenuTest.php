<?php

namespace Thinktomorrow\Chief\Menu\Tests\Unit;

use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class MenuTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_can_create_menu(): void
    {
        // Create menu for site / type
        $menu = Menu::create([
            '',
        ]);
    }

    public function test_it_can_get_the_menu_for_a_site(): void
    {
        // Get menu for site / type
    }

    public function test_it_can_get_menu_by_type()
    {
        $first = MenuItem::create(['label' => 'first item', 'menu_type' => 'main']);
        $second = MenuItem::create(['label' => 'second item', 'menu_type' => 'main']);
        $third = MenuItem::create(['label' => 'first item', 'menu_type' => 'footer']);

        $collection = MenuItem::tree('main');
        $this->assertEquals(2, $collection->total());

        $collection = MenuItem::tree('footer');
        $this->assertEquals(1, $collection->total());
    }

    public function test_it_can_get_all_menu_types()
    {
        $this->assertCount(1, Menu::all());
    }
}
