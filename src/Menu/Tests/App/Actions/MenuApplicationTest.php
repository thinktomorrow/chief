<?php

namespace Thinktomorrow\Chief\Menu\Tests\App\Actions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Menu\App\Actions\CreateMenu;
use Thinktomorrow\Chief\Menu\App\Actions\DeleteMenu;
use Thinktomorrow\Chief\Menu\App\Actions\MenuApplication;
use Thinktomorrow\Chief\Menu\App\Actions\UpdateMenu;
use Thinktomorrow\Chief\Menu\Events\MenuCreated;
use Thinktomorrow\Chief\Menu\Events\MenuDeleted;
use Thinktomorrow\Chief\Menu\Events\MenuUpdated;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class MenuApplicationTest extends ChiefTestCase
{
    use RefreshDatabase;

    private MenuApplication $menuApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->menuApplication = app(MenuApplication::class);
    }

    public function test_it_can_create_a_menu_and_dispatch_event(): void
    {
        Event::fake();

        $command = new CreateMenu('main', ['nl', 'en'], ['nl'], 'Main Menu');

        $menuId = $this->menuApplication->create($command);

        $this->assertDatabaseHas('menus', [
            'id' => $menuId,
            'type' => 'main',
            'allowed_sites' => json_encode(['nl', 'en']),
            'active_sites' => json_encode(['nl']),
            'title' => 'Main Menu',
        ]);

        Event::assertDispatched(MenuCreated::class, function ($event) use ($menuId) {
            return $event->menuId == $menuId;
        });
    }

    public function test_it_can_update_a_menu_and_dispatch_event(): void
    {
        Event::fake();

        $menu = Menu::create([
            'type' => 'main',
            'allowed_sites' => ['nl'],
            'title' => 'Old Title',
        ]);

        $command = new UpdateMenu($menu->id, ['nl', 'en'], ['nl', 'en'], 'Updated Title');
        $this->menuApplication->update($command);

        $this->assertDatabaseHas('menus', [
            'id' => $menu->id,
            'allowed_sites' => json_encode(['nl', 'en']),
            'active_sites' => json_encode(['nl', 'en']),
            'title' => 'Updated Title',
        ]);

        Event::assertDispatched(MenuUpdated::class, function ($event) use ($menu) {
            return $event->menuId == $menu->id;
        });
    }

    public function test_it_can_delete_a_menu_and_dispatch_event(): void
    {
        Event::fake();

        $menu = Menu::create([
            'type' => 'main',
            'allowed_sites' => ['nl'],
            'title' => 'Menu to delete',
        ]);

        $command = new DeleteMenu($menu->id);
        $this->menuApplication->delete($command);

        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);

        Event::assertDispatched(MenuDeleted::class, function ($event) use ($menu) {
            return $event->menuId == $menu->id;
        });
    }

    public function test_it_fails_if_updating_non_existent_menu(): void
    {
        Event::fake();

        $command = new UpdateMenu(999, ['nl', 'en'], [], 'Updated Title');

        $this->expectException(ModelNotFoundException::class);
        $this->menuApplication->update($command);

        Event::assertNotDispatched(MenuUpdated::class);
    }

    public function test_it_fails_if_deleting_non_existent_menu(): void
    {
        Event::fake();

        $command = new DeleteMenu(999);

        $this->expectException(ModelNotFoundException::class);
        $this->menuApplication->delete($command);

        Event::assertNotDispatched(MenuDeleted::class);
    }
}
