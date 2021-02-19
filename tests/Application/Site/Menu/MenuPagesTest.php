<?php

namespace Thinktomorrow\Chief\Tests\Application\Site\Menu;

use Thinktomorrow\Chief\Site\Menu\Menu;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class MenuPagesTest extends ChiefTestCase
{
    /** @test */
    public function admin_can_view_the_menu_index()
    {
        config()->set('chief.menus.footer', [
            'label' => 'Hoofdnavigatie',
            'view' => 'front.menus.main',
            ]);

        $response = $this->asAdmin()->get(route('chief.back.menus.index'));
        $response->assertViewIs('chief::back.menu.index')
                 ->assertStatus(200);
    }

    /** @test */
    public function menu_index_route_shows_menu_show_if_there_is_only_one_menu()
    {
        $response = $this->asAdmin()->get(route('chief.back.menus.index'));
        $response->assertViewIs('chief::back.menu.show')
                 ->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_the_menu_show()
    {
        $menu = Menu::all()->first();

        $response = $this->asAdmin()->get(route('chief.back.menus.show', $menu->key()));
        $response->assertViewIs('chief::back.menu.show')
                 ->assertStatus(200);
    }
}
