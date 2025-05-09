<?php

namespace Thinktomorrow\Chief\Menu\Tests\App\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Menu\Events\MenuItemDeleted;
use Thinktomorrow\Chief\Menu\Events\MenuItemUpdated;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class MenuItemControllerTest extends ChiefTestCase
{
    public function test_creating_a_new_menu_item()
    {
        $menu = Menu::create(['type' => 'main']);

        $response = $this->asAdmin()
            ->post(route('chief.back.menuitem.store', $menu->id), $this->validParams([
                'trans' => [
                    'nl' => [
                        'label' => 'label one',
                        'url' => 'https://thinktomorrow.be',
                    ],
                    'en' => [
                        'label' => 'label two',
                        'url' => 'https://thinktomorrow.co.uk',
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
                'trans.nl.label' => 'label one',
                'trans.en.label' => 'label two',
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
                'trans.nl.label' => 'foobar',
                'trans.nl.url' => 'https://thinktomorrow.be',
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
                'trans.nl.url' => 'contact',
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
                'nl' => [
                    'label' => 'nieuw label',
                    'url' => 'http://google.com',
                ],
            ],
        ];

        foreach ($overrides as $key => $value) {
            Arr::set($params, $key, $value);
        }

        return $params;
    }
}
