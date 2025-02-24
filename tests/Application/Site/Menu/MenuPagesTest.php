<?php

namespace Thinktomorrow\Chief\Tests\Application\Site\Menu;

use Thinktomorrow\Chief\Site\Menu\Menu;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class MenuPagesTest extends ChiefTestCase
{
    public function test_admin_can_view_the_menu_index()
    {
        config()->set('chief.menus.footer', [
            'label' => 'Hoofdnavigatie',
            'view' => 'front.menus.main',
        ]);

        $response = $this->asAdmin()->get(route('chief.back.menus.index'));
        $response->assertViewIs('chief::admin.menu.index')
            ->assertStatus(200);
    }

    public function test_menu_index_route_shows_menu_show_if_there_is_only_one_menu()
    {
        $this->disableExceptionHandling();
        $response = $this->asAdmin()->get(route('chief.back.menus.index'));
        $response->assertViewIs('chief::admin.menu.show')
            ->assertStatus(200);
    }

    public function test_admin_can_view_the_menu_show()
    {
        $menu = Menu::all()->first();

        $response = $this->asAdmin()->get(route('chief.back.menus.show', $menu->key()));
        $response->assertViewIs('chief::admin.menu.show')
            ->assertStatus(200);
    }
}
