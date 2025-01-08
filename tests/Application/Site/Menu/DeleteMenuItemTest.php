<?php

namespace Thinktomorrow\Chief\Tests\Application\Site\Menu;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Site\Menu\Events\MenuItemDeleted;
use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class DeleteMenuItemTest extends ChiefTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        app()->setLocale('nl');
    }

    public function test_it_can_delete_a_menu_item()
    {
        $menuitem = MenuItem::create(['menu_type' => 'main']);

        $response = $this->asAdmin()
            ->delete(route('chief.back.menuitem.destroy', $menuitem->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menus.show', $menuitem->menuType()));

        $this->assertCount(0, MenuItem::all());
    }

    public function test_only_authenticated_admin_can_delete_a_menu_item()
    {
        $menuitem = MenuItem::create();

        $response = $this->delete(route('chief.back.menuitem.destroy', $menuitem->id));

        $response->assertRedirect(route('chief.back.login'));
        $this->assertCount(1, MenuItem::all());
    }

    public function test_deleting_a_new_menu_item_emits_event()
    {
        Event::fake();

        $menuitem = MenuItem::create();

        $this->asAdmin()->delete(route('chief.back.menuitem.destroy', $menuitem->id));

        Event::assertDispatched(MenuItemDeleted::class);
    }
}
