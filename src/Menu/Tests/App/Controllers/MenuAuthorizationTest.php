<?php

namespace Thinktomorrow\Chief\Menu\Tests\App\Controllers;

use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class MenuAuthorizationTest extends ChiefTestCase
{
    public function test_admin_can_view_the_create_form()
    {
        $response = $this->asAdmin()->get(route('chief.back.menuitem.create', 'main'));
        $response->assertViewIs('chief-menu::create')
            ->assertStatus(200);
    }

    public function test_guests_cannot_view_the_create_form()
    {
        $response = $this->get(route('chief.back.menuitem.create', 'main'));
        $response->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }

    public function test_only_authenticated_admin_can_create_a_menu_item()
    {
        $response = $this->post(route('chief.back.menuitem.store'), $this->validParams(['trans.nl.url' => 'https://thinktomorrow.be']));

        $response->assertRedirect(route('chief.back.login'));
        $this->assertCount(0, MenuItem::all());
    }

    public function test_admin_can_view_the_edit_form()
    {
        $menuitem = MenuItem::create();

        $response = $this->asAdmin()->get(route('chief.back.menuitem.edit', $menuitem->id));
        $response->assertSuccessful();
    }

    public function test_guests_cannot_view_the_edit_form()
    {
        $menuitem = MenuItem::create();

        $response = $this->get(route('chief.back.menuitem.edit', $menuitem->id));
        $response->assertStatus(302)->assertRedirect(route('chief.back.login'));
    }

    public function test_only_authenticated_admin_can_delete_a_menu_item()
    {
        $menuitem = MenuItem::create();

        $response = $this->delete(route('chief.back.menuitem.destroy', $menuitem->id));

        $response->assertRedirect(route('chief.back.login'));
        $this->assertCount(1, MenuItem::all());
    }

    public function test_admin_can_view_the_menu_index()
    {
        config()->set('chief.menus.footer', [
            'label' => 'Hoofdnavigatie',
            'view' => 'front.menus.main',
        ]);

        $response = $this->asAdmin()->get(route('chief.back.menus.index'));
        $response->assertViewIs('chief-menu::index')
            ->assertStatus(200);
    }

    public function test_menu_index_route_shows_menu_show_if_there_is_only_one_menu()
    {
        $response = $this->asAdmin()->get(route('chief.back.menus.index'));
        $response->assertViewIs('chief-menu::show')
            ->assertStatus(200);
    }

    public function test_admin_can_view_the_menu_show()
    {
        $menu = Menu::all()->first();

        $response = $this->asAdmin()->get(route('chief.back.menus.show', $menu->key()));
        $response->assertViewIs('chief-menu::show')
            ->assertStatus(200);
    }
}
