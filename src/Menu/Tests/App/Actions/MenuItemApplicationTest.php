<?php

namespace Thinktomorrow\Chief\Menu\Tests\App\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Menu\App\Actions\CreateMenuItem;
use Thinktomorrow\Chief\Menu\App\Actions\DeleteMenuItem;
use Thinktomorrow\Chief\Menu\App\Actions\MenuItemApplication;
use Thinktomorrow\Chief\Menu\App\Actions\UpdateMenuItem;
use Thinktomorrow\Chief\Menu\Events\MenuItemCreated;
use Thinktomorrow\Chief\Menu\Events\MenuItemDeleted;
use Thinktomorrow\Chief\Menu\Events\MenuItemUpdated;
use Thinktomorrow\Chief\Menu\Exceptions\OwnerReferenceIsRequiredForInternalLinkType;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class MenuItemApplicationTest extends ChiefTestCase
{
    use RefreshDatabase;

    private MenuItemApplication $menuItemApplication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->menuItemApplication = app(MenuItemApplication::class);
    }

    public function test_it_can_create_a_menu_item_and_dispatch_event(): void
    {
        $this->disableExceptionHandling();
        Event::fake();

        $command = new CreateMenuItem(
            menuId: 1,
            linkType: 'custom',
            parentId: null,
            ownerReference: null,
            data: ['en' => ['title' => 'Home'], 'nl' => ['title' => 'Thuis', 'url' => 'https://example.com']],
        );

        $menuItemId = $this->menuItemApplication->create($command);

        $this->assertDatabaseHas('menu_items', [
            'id' => $menuItemId,
            'menu_id' => 1,
            'type' => 'custom',
            'values' => json_encode(['title' => ['en' => 'Home', 'nl' => 'Thuis'], 'url' => ['nl' => 'https://example.com']]),
            'parent_id' => null,
        ]);

        Event::assertDispatched(MenuItemCreated::class, function ($event) use ($menuItemId) {
            return $event->menuItemId === (string) $menuItemId;
        });
    }

    public function test_it_can_update_a_menu_item_and_dispatch_event(): void
    {
        Event::fake();

        $menuItem = MenuItem::create([
            'menu_id' => 1,
            'type' => 'internal',
            'values' => json_encode(['url' => ['nl' => '/about']]),
            'parent_id' => null,
        ]);

        $command = new UpdateMenuItem(
            menuItemId: $menuItem->id,
            linkType: 'custom',
            ownerReference: null,
            parentId: 2,
            data: ['nl' => ['url' => 'https://example.com']]
        );

        $this->menuItemApplication->update($command);

        $this->assertDatabaseHas('menu_items', [
            'id' => $menuItem->id,
            'type' => 'custom',
            'values' => json_encode(['url' => ['nl' => 'https://example.com']]),
            'parent_id' => 2,
        ]);

        Event::assertDispatched(MenuItemUpdated::class, function ($event) use ($menuItem) {
            return $event->menuItemId === (string) $menuItem->id;
        });
    }

    public function test_it_can_delete_a_menu_item_and_dispatch_event(): void
    {
        Event::fake();

        $menuItem = MenuItem::create([
            'menu_id' => 1,
            'type' => 'internal',
            'values' => json_encode(['url' => ['nl' => '/contact']]),
            'parent_id' => null,
        ]);

        $command = new DeleteMenuItem($menuItem->id);
        $this->menuItemApplication->delete($command);

        $this->assertDatabaseMissing('menu_items', ['id' => $menuItem->id]);

        Event::assertDispatched(MenuItemDeleted::class, function ($event) use ($menuItem) {
            return $event->menuItemId === (string) $menuItem->id;
        });
    }

    public function test_it_throws_exception_when_updating_non_existent_menu_item(): void
    {
        Event::fake();

        $command = new UpdateMenuItem(
            999,
            'custom',
            null,
            'https://example.com',
            []);

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->menuItemApplication->update($command);

        Event::assertNotDispatched(MenuItemUpdated::class);
    }

    public function test_it_throws_exception_when_deleting_non_existent_menu_item(): void
    {
        Event::fake();

        $command = new DeleteMenuItem(999);

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->menuItemApplication->delete($command);

        Event::assertNotDispatched(MenuItemDeleted::class);
    }

    public function test_it_can_create_a_menu_item_with_a_parent(): void
    {
        Event::fake();

        $parentMenuItem = MenuItem::create([
            'menu_id' => 1,
            'type' => 'internal',
            'values' => json_encode(['url' => ['nl' => '/parent']]),
        ]);

        $command = new CreateMenuItem(
            menuId: 1,
            linkType: 'custom',
            parentId: $parentMenuItem->id,
            ownerReference: null,
            data: ['nl' => ['url' => '/child']]
        );

        $menuItemId = $this->menuItemApplication->create($command);

        $this->assertDatabaseHas('menu_items', [
            'id' => $menuItemId,
            'menu_id' => 1,
            'type' => 'custom',
            'values' => json_encode(['url' => ['nl' => '/child']]),
            'parent_id' => $parentMenuItem->id,
        ]);

        Event::assertDispatched(MenuItemCreated::class, function ($event) use ($menuItemId) {
            return $event->menuItemId === (string) $menuItemId;
        });
    }

    public function test_it_can_create_a_menu_item_with_owner_reference(): void
    {
        Event::fake();

        ArticlePage::migrateUp();
        $owner = ArticlePage::create();

        $command = new CreateMenuItem(
            menuId: 1,
            linkType: 'internal',
            ownerReference: $owner->modelReference()->get(),
            parentId: null,
            data: ['nl' => ['url' => '/page']]
        );

        $menuItemId = $this->menuItemApplication->create($command);

        $this->assertDatabaseHas('menu_items', [
            'id' => $menuItemId,
            'menu_id' => 1,
            'type' => 'internal',
            'values' => json_encode(['url' => ['nl' => '/page']]),
            'owner_type' => $owner->getMorphClass(),
            'owner_id' => $owner->id,
        ]);

        Event::assertDispatched(MenuItemCreated::class, function ($event) use ($menuItemId) {
            return $event->menuItemId === (string) $menuItemId;
        });
    }

    public function test_url_field_is_sanitized_if_scheme_is_missing()
    {
        Event::fake();

        $menuItemId = $this->menuItemApplication->create(new CreateMenuItem(
            menuId: 1,
            linkType: 'custom',
            parentId: null,
            ownerReference: null,
            data: ['nl' => ['url' => 'thinktomorrow.be']]
        ));

        $this->assertDatabaseHas('menu_items', [
            'id' => $menuItemId,
            'menu_id' => 1,
            'type' => 'custom',
            'values' => json_encode(['url' => ['nl' => 'https://thinktomorrow.be']]),
            'parent_id' => null,
        ]);
    }

    public function test_owner_reference_is_required_for_internal_link()
    {
        $this->expectException(OwnerReferenceIsRequiredForInternalLinkType::class);

        $this->menuItemApplication->create(new CreateMenuItem(
            menuId: 1,
            linkType: 'internal',
            parentId: null,
            ownerReference: null,
            data: ['nl' => ['url' => 'thinktomorrow.be']]
        ));
    }
}
