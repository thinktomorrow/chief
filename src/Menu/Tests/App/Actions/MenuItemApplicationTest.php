<?php

namespace Thinktomorrow\Chief\Menu\Tests\App\Actions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
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
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Menu\MenuLinkType;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class MenuItemApplicationTest extends ChiefTestCase
{
    use RefreshDatabase;

    private MenuItemApplication $menuItemApplication;

    protected function setUp(): void
    {
        parent::setUp();
        Menu::create(['type' => 'main']);
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
            data: ['title' => ['en' => 'Home', 'nl' => 'Thuis'], 'url' => ['nl' => 'https://example.com']]
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
            data: ['url' => ['nl' => 'https://example.com']]
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

        $this->expectException(ModelNotFoundException::class);
        $this->menuItemApplication->update($command);

        Event::assertNotDispatched(MenuItemUpdated::class);
    }

    public function test_it_throws_exception_when_deleting_non_existent_menu_item(): void
    {
        Event::fake();

        $command = new DeleteMenuItem(999);

        $this->expectException(ModelNotFoundException::class);
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
            data: ['url' => ['nl' => '/child']]
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

    public function test_it_places_a_new_child_item_at_the_end_of_its_parent_scope(): void
    {
        $parentMenuItem = MenuItem::create([
            'menu_id' => 1,
            'type' => 'custom',
        ]);

        MenuItem::create([
            'menu_id' => 1,
            'parent_id' => $parentMenuItem->id,
            'type' => 'custom',
            'order' => 0,
        ]);

        MenuItem::create([
            'menu_id' => 1,
            'parent_id' => $parentMenuItem->id,
            'type' => 'custom',
            'order' => 1,
        ]);

        $menuItemId = $this->menuItemApplication->create(new CreateMenuItem(
            menuId: 1,
            linkType: 'custom',
            parentId: (string) $parentMenuItem->id,
            ownerReference: null,
            data: ['url' => ['nl' => '/child']]
        ));

        $this->assertDatabaseHas('menu_items', [
            'id' => $menuItemId,
            'parent_id' => $parentMenuItem->id,
            'order' => 2,
        ]);
    }

    public function test_it_places_a_reparented_item_at_the_end_of_the_new_parent_scope(): void
    {
        $firstParent = MenuItem::create([
            'menu_id' => 1,
            'type' => 'custom',
        ]);

        $secondParent = MenuItem::create([
            'menu_id' => 1,
            'type' => 'custom',
        ]);

        $menuItem = MenuItem::create([
            'menu_id' => 1,
            'parent_id' => $firstParent->id,
            'type' => 'custom',
            'order' => 0,
        ]);

        MenuItem::create([
            'menu_id' => 1,
            'parent_id' => $secondParent->id,
            'type' => 'custom',
            'order' => 0,
        ]);

        MenuItem::create([
            'menu_id' => 1,
            'parent_id' => $secondParent->id,
            'type' => 'custom',
            'order' => 1,
        ]);

        $this->menuItemApplication->update(new UpdateMenuItem(
            menuItemId: (string) $menuItem->id,
            linkType: 'custom',
            ownerReference: null,
            parentId: (string) $secondParent->id,
            data: ['url' => ['nl' => '/moved-child']]
        ));

        $this->assertDatabaseHas('menu_items', [
            'id' => $menuItem->id,
            'parent_id' => $secondParent->id,
            'order' => 2,
        ]);
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
            data: ['url' => ['nl' => '/page']]
        );

        $menuItemId = $this->menuItemApplication->create($command);

        // The submitted url is ignored: an internal type has its url projected from the owner
        $this->assertDatabaseHas('menu_items', [
            'id' => $menuItemId,
            'menu_id' => 1,
            'type' => 'internal',
            'values' => null,
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
            data: ['url' => ['nl' => 'thinktomorrow.be']]
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
            data: ['url' => ['nl' => 'thinktomorrow.be']]
        ));
    }

    public function test_url_is_removed_when_no_link_is_selected()
    {
        Event::fake();

        $menuItemId = $this->menuItemApplication->create(new CreateMenuItem(
            menuId: 1,
            linkType: 'custom',
            parentId: null,
            ownerReference: null,
            data: ['url' => ['nl' => 'thinktomorrow.be']]
        ));

        $this->assertDatabaseHas('menu_items', [
            'id' => $menuItemId,
            'menu_id' => 1,
            'type' => 'custom',
            'values' => json_encode(['url' => ['nl' => 'https://thinktomorrow.be']]),
            'parent_id' => null,
        ]);

        $this->menuItemApplication->update(new UpdateMenuItem(
            menuItemId: $menuItemId,
            linkType: MenuLinkType::nolink->value,
            ownerReference: null,
            parentId: null,
            data: []
        ));

        $this->assertDatabaseHas('menu_items', [
            'id' => $menuItemId,
            'menu_id' => 1,
            'type' => MenuLinkType::nolink->value,
            'values' => json_encode([]),
            'parent_id' => null,
        ]);
    }

    public function test_url_is_removed_when_switching_to_no_link_even_when_submitted()
    {
        Event::fake();

        $menuItemId = $this->menuItemApplication->create(new CreateMenuItem(
            menuId: 1,
            linkType: 'custom',
            parentId: null,
            ownerReference: null,
            data: ['label' => ['nl' => 'Home'], 'url' => ['nl' => 'thinktomorrow.be']]
        ));

        /**
         * The custom url input is hidden but never disabled by the form, so the
         * previous url is submitted again along with the nolink type.
         */
        $this->menuItemApplication->update(new UpdateMenuItem(
            menuItemId: $menuItemId,
            linkType: MenuLinkType::nolink->value,
            ownerReference: null,
            parentId: null,
            data: ['label' => ['nl' => 'Home'], 'url' => ['nl' => 'https://thinktomorrow.be']]
        ));

        $this->assertDatabaseHas('menu_items', [
            'id' => $menuItemId,
            'type' => MenuLinkType::nolink->value,
            'values' => json_encode(['label' => ['nl' => 'Home']]),
        ]);

        $this->assertNull(MenuItem::find($menuItemId)->getUrl('nl'));
    }

    public function test_url_is_not_stored_when_creating_a_no_link_item()
    {
        Event::fake();

        $menuItemId = $this->menuItemApplication->create(new CreateMenuItem(
            menuId: 1,
            linkType: MenuLinkType::nolink->value,
            parentId: null,
            ownerReference: null,
            data: ['label' => ['nl' => 'Home'], 'url' => ['nl' => 'thinktomorrow.be']]
        ));

        $this->assertDatabaseHas('menu_items', [
            'id' => $menuItemId,
            'type' => MenuLinkType::nolink->value,
            'values' => json_encode(['label' => ['nl' => 'Home']]),
        ]);

        $this->assertNull(MenuItem::find($menuItemId)->getUrl('nl'));
    }

    public function test_a_submitted_url_is_ignored_for_the_internal_link_type()
    {
        Event::fake();

        ArticlePage::migrateUp();
        $page = ArticlePage::create();

        $menuItemId = $this->menuItemApplication->create(new CreateMenuItem(
            menuId: 1,
            linkType: MenuLinkType::internal->value,
            parentId: null,
            ownerReference: $page->modelReference()->getShort(),
            data: ['label' => ['nl' => 'Home'], 'url' => ['nl' => 'thinktomorrow.be']]
        ));

        $this->assertDatabaseHas('menu_items', [
            'id' => $menuItemId,
            'type' => MenuLinkType::internal->value,
            'values' => json_encode(['label' => ['nl' => 'Home']]),
        ]);
    }

    public function test_a_stale_custom_url_is_removed_when_switching_to_an_internal_link()
    {
        Event::fake();

        ArticlePage::migrateUp();
        $page = ArticlePage::create();

        $menuItemId = $this->menuItemApplication->create(new CreateMenuItem(
            menuId: 1,
            linkType: MenuLinkType::custom->value,
            parentId: null,
            ownerReference: null,
            data: ['label' => ['nl' => 'Home'], 'url' => ['nl' => 'thinktomorrow.be']]
        ));

        $this->assertEquals('https://thinktomorrow.be', MenuItem::find($menuItemId)->getUrl('nl'));

        $this->menuItemApplication->update(new UpdateMenuItem(
            menuItemId: $menuItemId,
            linkType: MenuLinkType::internal->value,
            ownerReference: $page->modelReference()->getShort(),
            parentId: null,
            data: ['label' => ['nl' => 'Home'], 'url' => ['nl' => 'thinktomorrow.be']]
        ));

        /**
         * The url of an internal item is projected from its owner page. The previous custom
         * url must not survive the switch, since projection is not guaranteed to overwrite
         * it: an owner that is not visitable or no longer present leaves the url untouched.
         */
        $this->assertDatabaseHas('menu_items', [
            'id' => $menuItemId,
            'type' => MenuLinkType::internal->value,
            'values' => json_encode(['label' => ['nl' => 'Home']]),
        ]);

        $this->assertNull(MenuItem::find($menuItemId)->getUrl('nl'));
    }

    public function test_owner_is_cleared_when_switching_away_from_an_internal_link()
    {
        Event::fake();

        ArticlePage::migrateUp();
        $page = ArticlePage::create();

        $menuItemId = $this->menuItemApplication->create(new CreateMenuItem(
            menuId: 1,
            linkType: MenuLinkType::internal->value,
            parentId: null,
            ownerReference: $page->modelReference()->getShort(),
            data: ['label' => ['nl' => 'Home']]
        ));

        MenuItem::find($menuItemId)->setOwnerLabel('artikel titel', 'nl');

        $this->assertDatabaseHas('menu_items', [
            'id' => $menuItemId,
            'owner_type' => $page->modelReference()->shortClassName(),
            'owner_id' => $page->id,
        ]);

        $this->menuItemApplication->update(new UpdateMenuItem(
            menuItemId: $menuItemId,
            linkType: MenuLinkType::custom->value,
            ownerReference: $page->modelReference()->getShort(),
            parentId: null,
            data: ['label' => ['nl' => 'Home'], 'url' => ['nl' => 'thinktomorrow.be']]
        ));

        $this->assertDatabaseHas('menu_items', [
            'id' => $menuItemId,
            'type' => MenuLinkType::custom->value,
            'owner_type' => null,
            'owner_id' => null,
            'values' => json_encode(['label' => ['nl' => 'Home'], 'url' => ['nl' => 'https://thinktomorrow.be']]),
        ]);
    }
}
