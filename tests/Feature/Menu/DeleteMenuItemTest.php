<?php

namespace Thinktomorrow\Chief\Tests\Feature\MenuItems;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Menu\MenuItem;

class DeleteMenuItemTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        app()->setLocale('nl');
    }

    /** @test */
    public function delete_a_new_menuItem()
    {
        $menuitem = factory(MenuItem::class)->create();

        $response = $this->asAdmin()
            ->delete(route('chief.back.menu.destroy', $menuitem->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menu.index'));

        $this->assertCount(0, MenuItem::all());
    }

    /** @test */
    public function only_authenticated_admin_can_delete_a_menuItem()
    {
        $menuitem = factory(MenuItem::class)->create();

        $response = $this->delete(route('chief.back.menu.destroy', $menuitem->id));

        $response->assertRedirect(route('chief.back.login'));
        $this->assertCount(1, MenuItem::all());
    }

}
