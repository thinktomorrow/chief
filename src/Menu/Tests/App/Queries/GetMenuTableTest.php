<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\Tests\App\Queries;

use Livewire\Livewire;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Forms\Tests\TestSupport\CustomAsset;
use Thinktomorrow\Chief\Menu\App\Queries\GetMenuTable;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Menu\Resources\MenuItemResource;
use Thinktomorrow\Chief\Menu\Tests\TestSupport\ProjectMenuItemResource;
use Thinktomorrow\Chief\Table\Livewire\TableComponent;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class GetMenuTableTest extends ChiefTestCase
{
    public function test_menu_nesting_is_enabled_by_default_and_configurable_per_menu(): void
    {
        $menu = Menu::create(['type' => 'footer']);
        $this->assertTrue(app(GetMenuTable::class)->getTable((string) $menu->id)->shouldReturnResultsAsTree());

        $this->app->bind(MenuItemResource::class, ProjectMenuItemResource::class);
        $this->assertFalse(app(GetMenuTable::class)->getTable((string) $menu->id)->shouldReturnResultsAsTree());

        $main = Menu::create(['type' => 'main']);
        $this->assertTrue(app(GetMenuTable::class)->getTable((string) $main->id)->shouldReturnResultsAsTree());
    }

    public function test_flat_menu_shows_and_reorders_children_without_changing_parents(): void
    {
        $this->app->bind(MenuItemResource::class, ProjectMenuItemResource::class);
        $menu = Menu::create(['type' => 'footer']);
        $parent = MenuItem::create(['menu_id' => $menu->id, 'type' => 'custom', 'label.nl' => 'Parent item', 'order' => 2]);
        $child = MenuItem::create(['menu_id' => $menu->id, 'type' => 'custom', 'label.nl' => 'Child item', 'parent_id' => $parent->id, 'order' => 0]);

        $component = Livewire::test(TableComponent::class, [
            'table' => app(GetMenuTable::class)->getTable((string) $menu->id),
        ])->assertSeeInOrder(['Child item', 'Parent item']);

        $this->assertFalse($component->instance()->areResultsAsTree());
        $this->assertFalse($component->instance()->isTreeReorderingAllowed());
        $this->assertFalse($component->instance()->allowsTreeBreadcrumbColumnSelection());

        $component->call('startReordering')
            ->call('reorder', [$parent->id, $child->id]);

        $this->assertEquals(0, $parent->fresh()->order);
        $this->assertEquals(1, $child->fresh()->order);
        $this->assertEquals($parent->id, $child->fresh()->parent_id);

        $component->call('moveToParent', $child->id, null, [$child->id, $parent->id])->assertForbidden();
        $this->assertEquals($parent->id, $child->fresh()->parent_id);
        $this->assertEquals(1, $child->fresh()->order);
    }

    public function test_default_search_finds_labels_and_urls_across_locales(): void
    {
        $menu = Menu::create(['type' => 'main']);
        $otherMenu = Menu::create(['type' => 'footer']);

        MenuItem::create([
            'menu_id' => $menu->id,
            'type' => 'custom',
            'label.nl' => 'Eerste item',
            'label.en' => 'English needle',
            'url.nl' => '/eerste',
            'url.en' => '/english',
        ]);
        MenuItem::create([
            'menu_id' => $menu->id,
            'type' => 'custom',
            'label.nl' => 'Tweede item',
            'url.nl' => '/url-naald',
        ]);
        MenuItem::create([
            'menu_id' => $otherMenu->id,
            'type' => 'custom',
            'label.nl' => 'Ander menu',
            'label.en' => 'English needle',
        ]);

        $component = Livewire::test(TableComponent::class, [
            'table' => app(GetMenuTable::class)->getTable((string) $menu->id),
        ]);

        $component
            ->set('filters.search', 'English needle')
            ->assertSee('Eerste item')
            ->assertDontSee('Tweede item')
            ->assertDontSee('Ander menu')
            ->set('filters.search', 'url-naald')
            ->assertSee('Tweede item')
            ->assertDontSee('Eerste item');
    }

    public function test_project_resource_can_extend_the_default_table(): void
    {
        $this->app->bind(MenuItemResource::class, ProjectMenuItemResource::class);

        $menu = Menu::create(['type' => 'main']);
        MenuItem::create([
            'menu_id' => $menu->id,
            'type' => 'custom',
            'label.nl' => 'Project item',
            'description.nl' => 'Projectbeschrijving',
        ]);

        $table = app(GetMenuTable::class)->getTable((string) $menu->id);
        $columnKeys = collect($table->getColumns())
            ->flatMap(fn ($column) => collect($column->getItems())->map(fn ($item) => $item->getKey()))
            ->all();

        $this->assertContains('thumbnail', $columnKeys);
        $this->assertContains('description', $columnKeys);
        $this->assertSame('search', $table->getFilters()[0]->getKey());

        Livewire::test(TableComponent::class, ['table' => $table])
            ->assertSee('Thumbnail')
            ->assertSee('Beschrijving')
            ->assertSee('Projectbeschrijving');
    }

    public function test_project_resource_can_configure_columns_per_menu(): void
    {
        $this->app->bind(MenuItemResource::class, ProjectMenuItemResource::class);

        $menu = Menu::create(['type' => 'footer']);
        $table = app(GetMenuTable::class)->getTable((string) $menu->id);
        $columnKeys = collect($table->getColumns())
            ->flatMap(fn ($column) => collect($column->getItems())->map(fn ($item) => $item->getKey()))
            ->all();

        $this->assertNotContains('thumbnail', $columnKeys);
        $this->assertNotContains('description', $columnKeys);
        $this->assertSame('search', $table->getFilters()[0]->getKey());
    }

    public function test_table_and_tree_results_eager_load_assets_and_media(): void
    {
        config()->set('thinktomorrow.assetlibrary.types.custom', CustomAsset::class);

        $menu = Menu::create(['type' => 'main']);
        $menuItem = MenuItem::create([
            'menu_id' => $menu->id,
            'type' => 'custom',
        ]);
        $asset = Asset::create(['asset_type' => 'custom']);
        $menuItem->assetRelation()->attach($asset->id, [
            'type' => 'thumbnail',
            'locale' => 'nl',
            'order' => 0,
        ]);

        $table = app(GetMenuTable::class)->getTable((string) $menu->id);
        $queryMenuItem = $table->getQuery()()->firstOrFail();
        $treeMenuItem = (new MenuItem)->getTreeModels([(int) $menuItem->id])->first();

        $this->assertAssetsAndMediaAreLoaded($queryMenuItem);
        $this->assertAssetsAndMediaAreLoaded($treeMenuItem);
    }

    private function assertAssetsAndMediaAreLoaded(MenuItem $menuItem): void
    {
        $this->assertTrue($menuItem->relationLoaded('assetRelation'));
        $this->assertTrue($menuItem->assetRelation->first()->relationLoaded('media'));
    }
}
