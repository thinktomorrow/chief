<?php

namespace Thinktomorrow\Chief\Menu\Tests\App\Controllers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Menu\Events\MenuItemDeleted;
use Thinktomorrow\Chief\Menu\Events\MenuItemUpdated;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Menu\Resources\MenuItemResource;
use Thinktomorrow\Chief\Menu\Tests\TestSupport\ProjectMenuItemResource;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class MenuItemControllerTest extends ChiefTestCase
{
    public function test_project_resource_fields_are_rendered(): void
    {
        $this->app->bind(MenuItemResource::class, ProjectMenuItemResource::class);

        $menu = Menu::create(['type' => 'main']);

        $this->asAdmin()
            ->get(route('chief.back.menuitem.create', $menu->id))
            ->assertOk()
            ->assertSee('Beschrijving')
            ->assertSee('Onderliggende items tonen');
    }

    public function test_editing_a_menu_item_shows_its_existing_thumbnail(): void
    {
        $this->app->bind(MenuItemResource::class, ProjectMenuItemResource::class);

        $menu = Menu::create(['type' => 'main']);
        $menuItem = MenuItem::create([
            'menu_id' => $menu->id,
            'type' => 'custom',
        ]);
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('menu-thumbnail.png'))
            ->save();

        app(AddAsset::class)->handle($menuItem, $asset, 'thumbnail', 'nl', 0, []);

        $this->asAdmin()
            ->get(route('chief.back.menuitem.edit', $menuItem->id))
            ->assertOk()
            ->assertSee('menu-thumbnail.png');
    }

    public function test_project_resource_fields_are_validated_and_stored_as_dynamic_values(): void
    {
        $this->app->bind(MenuItemResource::class, ProjectMenuItemResource::class);

        $menu = Menu::create(['type' => 'main']);

        $this->asAdmin()
            ->post(route('chief.back.menuitem.store', $menu->id), $this->validParams([
                'description' => [
                    'nl' => 'Nederlandse beschrijving',
                    'en' => 'English description',
                ],
                'show_children' => '1',
            ]))
            ->assertSessionHasNoErrors();

        $menuItem = MenuItem::firstOrFail();

        $this->assertSame('Nederlandse beschrijving', $menuItem->dynamic('description', 'nl'));
        $this->assertSame('English description', $menuItem->dynamic('description', 'en'));
        $this->assertSame('1', $menuItem->dynamic('show_children'));
    }

    public function test_project_resource_validation_errors_are_returned(): void
    {
        $this->app->bind(MenuItemResource::class, ProjectMenuItemResource::class);

        $menu = Menu::create(['type' => 'main']);

        $this->asAdmin()
            ->post(route('chief.back.menuitem.store', $menu->id), $this->validParams())
            ->assertSessionHasErrors(['description.nl', 'description.en']);

        $this->assertDatabaseCount('menu_items', 0);
    }

    public function test_project_resource_fields_can_differ_per_menu(): void
    {
        $this->app->bind(MenuItemResource::class, ProjectMenuItemResource::class);

        $menu = Menu::create(['type' => 'footer']);

        $this->asAdmin()
            ->get(route('chief.back.menuitem.create', $menu->id))
            ->assertOk()
            ->assertDontSee('Beschrijving')
            ->assertDontSee('Onderliggende items tonen')
            ->assertDontSee('Thumbnail');

        $this->asAdmin()
            ->post(route('chief.back.menuitem.store', $menu->id), $this->validParams())
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('menu_items', [
            'menu_id' => $menu->id,
            'type' => 'custom',
        ]);
    }

    public function test_project_resource_fields_are_updated(): void
    {
        $this->app->bind(MenuItemResource::class, ProjectMenuItemResource::class);

        $menu = Menu::create(['type' => 'main']);
        $menuItem = MenuItem::create([
            'menu_id' => $menu->id,
            'type' => 'custom',
            'description.nl' => 'Oud',
            'description.en' => 'Old',
            'show_children' => '0',
        ]);

        $this->asAdmin()
            ->put(route('chief.back.menuitem.update', $menuItem->id), $this->validParams([
                'description' => [
                    'nl' => 'Nieuw',
                    'en' => 'New',
                ],
                'show_children' => '1',
            ]))
            ->assertSessionHasNoErrors();

        $menuItem->refresh();

        $this->assertSame('Nieuw', $menuItem->dynamic('description', 'nl'));
        $this->assertSame('New', $menuItem->dynamic('description', 'en'));
        $this->assertSame('1', $menuItem->dynamic('show_children'));
    }

    public function test_creating_a_new_menu_item()
    {
        $menu = Menu::create(['type' => 'main']);

        $response = $this->asAdmin()
            ->post(route('chief.back.menuitem.store', $menu->id), $this->validParams([
                'trans' => [
                    'label' => [
                        'nl' => 'label one',
                        'en' => 'label two',
                    ],
                    'url' => [
                        'nl' => 'https://thinktomorrow.be',
                        'en' => 'https://thinktomorrow.co.uk',
                    ],
                ],
            ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menus.show', ['main', $menu->id]));

        $this->assertCount(1, MenuItem::all());

        $item = MenuItem::first();

        $this->assertEquals('https://thinktomorrow.be', $item->getUrl('nl'));
        $this->assertEquals('https://thinktomorrow.co.uk', $item->getUrl('en'));

        $this->assertEquals('label one', $item->getLabel('nl'));
        $this->assertEquals('label two', $item->getLabel('en'));
        $this->assertNull($item->getOwnerLabel('nl'));
        $this->assertNull($item->getOwnerLabel('en'));
    }

    public function test_creating_a_new_internal_menu_item()
    {
        $menu = Menu::create(['type' => 'main']);

        $page = $this->setupAndCreateArticle(['title.nl' => 'artikel pagetitle nl', 'title.en' => 'artikel pagetitle en']);
        $this->updateLinks($page, ['nl' => 'foobar-nl', 'en' => 'foobar-en']);

        $this->asAdmin()
            ->post(route('chief.back.menuitem.store', $menu->id), $this->validParams([
                'type' => 'internal',
                'owner_reference' => $page->modelReference()->getShort(),
                'trans.label.nl' => 'label one',
                'trans.label.en' => 'label two',
            ]))
            ->assertStatus(302);

        $item = MenuItem::first();

        $this->assertEquals($page->modelReference(), $item->owner->modelReference());

        $this->assertEquals('/nl-base/foobar-nl', $item->getUrl('nl'));
        $this->assertEquals('/en-base/foobar-en', $item->getUrl('en'));

        $this->assertEquals('label one', $item->getLabel('nl'));
        $this->assertEquals('label two', $item->getLabel('en'));

        $this->assertEquals('artikel pagetitle nl', $item->getOwnerLabel('nl'));
        $this->assertEquals('artikel pagetitle en', $item->getOwnerLabel('en'));
    }

    public function test_editing_a_new_menu_item()
    {
        $menu = Menu::create(['type' => 'main']);
        $menuitem = MenuItem::create(['menu_id' => $menu->id]);

        $response = $this->asAdmin()
            ->put(route('chief.back.menuitem.update', $menuitem->id), $this->validParams([
                'trans.label.nl' => 'foobar',
                'trans.url.nl' => 'https://thinktomorrow.be',
            ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menus.show', [$menu->type, $menu->id]));

        $item = MenuItem::first();
        $this->assertEquals('foobar', $item->label);
        $this->assertEquals('https://thinktomorrow.be', $item->url);
    }

    public function test_only_authenticated_admin_can_update_a_menu_item()
    {
        $menu = Menu::create(['type' => 'main']);
        $menuitem = MenuItem::create(['menu_id' => $menu->id, 'label' => ['nl' => 'existing label']]);

        $this->put(route('chief.back.menuitem.update', $menuitem->id), $this->validParams(['trans.nl.label' => 'foobar']))
            ->assertRedirect(route('chief.back.login'));

        $this->assertEquals('existing label', MenuItem::first()->label);
    }

    public function test_updating_a_new_menu_item_emits_event()
    {
        $this->disableExceptionHandling();
        Event::fake();

        $menu = Menu::create(['type' => 'main']);
        $menuitem = MenuItem::create(['menu_id' => $menu->id]);

        $this->asAdmin()->put(route('chief.back.menuitem.update', $menuitem->id), $this->validParams());

        Event::assertDispatched(MenuItemUpdated::class);
    }

    public function test_editing_an_internal_menu_item()
    {
        $this->disableExceptionHandling();
        $page = $this->setupAndCreateArticle(['title.nl' => 'artikel pagetitle nl', 'title.en' => 'artikel pagetitle en']);
        $this->updateLinks($page, ['nl' => 'foobar-nl', 'en' => 'foobar-en']);

        $menu = Menu::create(['type' => 'main']);
        $menuitem = MenuItem::create(['menu_id' => $menu->id]);

        $this->asAdmin()
            ->put(route('chief.back.menuitem.update', $menuitem->id), $this->validParams([
                'type' => 'internal',
                'owner_reference' => $page->modelReference()->get(),
            ]))->assertStatus(302);

        $item = MenuItem::first();

        $this->assertEquals($page->modelReference(), $item->owner->modelReference());

        $this->assertEquals('/nl-base/foobar-nl', $item->getUrl('nl'));
        $this->assertEquals('/en-base/foobar-en', $item->getUrl('en'));

        $this->assertEquals('artikel pagetitle nl', $item->getOwnerLabel('nl'));
        $this->assertEquals('artikel pagetitle en', $item->getOwnerLabel('en'));
    }

    public function test_a_relative_url_is_sanitized_to_proper_relative_url()
    {
        $menu = Menu::create(['type' => 'main']);
        $menuitem = MenuItem::create(['menu_id' => $menu->id]);

        $this->asAdmin()
            ->put(route('chief.back.menuitem.update', $menuitem->id), $this->validParams([
                'type' => 'custom',
                'trans.url.nl' => 'contact',
            ]));

        $this->assertEquals('/contact', $menuitem->fresh()->url);
    }

    public function test_it_can_delete_a_menu_item()
    {
        $menu = Menu::create(['type' => 'main']);
        $menuitem = MenuItem::create(['menu_id' => $menu->id]);

        $response = $this->asAdmin()
            ->delete(route('chief.back.menuitem.destroy', $menuitem->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menus.show', [$menu->type, $menu->id]));

        $this->assertCount(0, MenuItem::all());
    }

    public function test_deleting_a_new_menu_item_emits_event()
    {
        Event::fake();

        $menu = Menu::create(['type' => 'main']);
        $menuitem = MenuItem::create(['menu_id' => $menu->id]);

        $this->asAdmin()->delete(route('chief.back.menuitem.destroy', $menuitem->id));

        Event::assertDispatched(MenuItemDeleted::class);
    }

    private function validParams($overrides = [])
    {
        $params = [
            'type' => 'custom',
            'allow_parent' => false, // flag to allow nesting or not
            'parent_id' => null,
            'trans' => [
                'label' => [
                    'nl' => 'nieuw label',
                ],
                'url' => [
                    'nl' => 'http://google.com',
                ],
            ],
        ];

        foreach ($overrides as $key => $value) {
            Arr::set($params, $key, $value);
        }

        return $params;
    }
}
