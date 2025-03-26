<?php

namespace Thinktomorrow\Chief\Menu\Tests\App\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Menu\Events\MenuItemDeleted;
use Thinktomorrow\Chief\Menu\Events\MenuItemUpdated;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class MenuItemControllerTest extends ChiefTestCase
{
    public function test_creating_a_new_menu_item()
    {
        $response = $this->asAdmin()
            ->post(route('chief.back.menuitem.store'), $this->validParams([
                'trans.nl.url' => 'https://thinktomorrow.be',
                'trans.nl.label' => 'label one',
                'trans.en.url' => 'https://thinktomorrow.co.uk',
                'trans.en.label' => 'label two',
            ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menus.show', 'main'));

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
        $page = $this->setupAndCreateArticle(['custom.nl' => 'artikel pagetitle nl', 'custom.en' => 'artikel pagetitle en']);
        $this->updateLinks($page, ['nl' => 'foobar-nl', 'en' => 'foobar-en']);

        $this->asAdmin()
            ->post(route('chief.back.menuitem.store'), $this->validParams([
                'type' => 'internal',
                'owner_reference' => $page->modelReference()->getShort(),
                'trans.nl.label' => 'label one',
                'trans.en.label' => 'label two',
            ]))
            ->assertStatus(302);

        $item = MenuItem::first();

        $this->assertEquals($page->modelReference(), $item->owner->modelReference());

        $this->assertEquals('/foobar-nl', $item->getUrl('nl'));
        $this->assertEquals('/foobar-en', $item->getUrl('en'));

        $this->assertEquals('label one', $item->getLabel('nl'));
        $this->assertEquals('label two', $item->getLabel('en'));

        $this->assertEquals('artikel pagetitle nl', $item->getOwnerLabel('nl'));
        $this->assertEquals('artikel pagetitle en', $item->getOwnerLabel('en'));
    }

    public function test_editing_a_new_menu_item()
    {
        $menuitem = MenuItem::create(['menu_type' => 'main']);

        $response = $this->asAdmin()
            ->put(route('chief.back.menuitem.update', $menuitem->id), $this->validParams([
                'trans.nl.label' => 'foobar',
                'trans.nl.url' => 'https://thinktomorrow.be',
            ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menus.show', $menuitem->menu_type));

        $item = MenuItem::first();
        $this->assertEquals('foobar', $item->label);
        $this->assertEquals('https://thinktomorrow.be', $item->url);
    }

    public function test_only_authenticated_admin_can_update_a_menu_item()
    {
        $menuitem = MenuItem::create(['label' => ['nl' => 'existing label']]);

        $this->put(route('chief.back.menuitem.update', $menuitem->id), $this->validParams(['trans.nl.label' => 'foobar']))
            ->assertRedirect(route('chief.back.login'));

        $this->assertEquals('existing label', MenuItem::first()->label);
    }

    public function test_updating_a_new_menu_item_emits_event()
    {
        Event::fake();

        $menuitem = MenuItem::create();

        $this->asAdmin()->put(route('chief.back.menuitem.update', $menuitem->id), $this->validParams());

        Event::assertDispatched(MenuItemUpdated::class);
    }

    public function test_editing_an_internal_menu_item()
    {
        $page = $this->setupAndCreateArticle(['custom.nl' => 'artikel pagetitle nl', 'custom.en' => 'artikel pagetitle en']);
        $this->updateLinks($page, ['nl' => 'foobar-nl', 'en' => 'foobar-en']);

        $menuitem = MenuItem::create();

        $this->asAdmin()
            ->put(route('chief.back.menuitem.update', $menuitem->id), $this->validParams([
                'type' => 'internal',
                'owner_reference' => $page->modelReference()->getShort(),
            ]))->assertStatus(302);

        $item = MenuItem::first();

        $this->assertEquals($page->modelReference(), $item->owner->modelReference());

        $this->assertEquals('/foobar-nl', $item->getUrl('nl'));
        $this->assertEquals('/foobar-en', $item->getUrl('en'));

        $this->assertEquals('artikel pagetitle nl', $item->getOwnerLabel('nl'));
        $this->assertEquals('artikel pagetitle en', $item->getOwnerLabel('en'));
    }

    public function test_using_homepage_as_link_gives_slash_as_link_entry()
    {
        $page = $this->setupAndCreateArticle(['custom.nl' => 'artikel pagetitle nl', 'custom.en' => 'artikel pagetitle en']);
        $this->updateLinks($page, ['nl' => '/', 'en' => '/en']);

        $menuitem = MenuItem::create();

        $this->asAdmin()
            ->put(route('chief.back.menuitem.update', $menuitem->id), $this->validParams([
                'trans' => [],
                'type' => 'internal',
                'owner_reference' => $page->modelReference()->getShort(),
            ]))->assertStatus(302);

        $item = MenuItem::first();

        $this->assertEquals('/', $item->getUrl('nl'));
        $this->assertEquals('/en', $item->getUrl('en'));
    }

    public function test_a_relative_url_is_sanitized_to_proper_relative_url()
    {
        $menuitem = MenuItem::create();

        $this->asAdmin()
            ->put(route('chief.back.menuitem.update', $menuitem->id), $this->validParams([
                'type' => 'custom',
                'trans.nl.url' => 'contact',
            ]));

        $this->assertEquals('/contact', $menuitem->fresh()->url);
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

    public function test_deleting_a_new_menu_item_emits_event()
    {
        Event::fake();

        $menuitem = MenuItem::create();

        $this->asAdmin()->delete(route('chief.back.menuitem.destroy', $menuitem->id));

        Event::assertDispatched(MenuItemDeleted::class);
    }

    public function test_label_is_required()
    {
        $this->assertValidation(new MenuItem, 'trans.nl.label', $this->validParams(['trans.nl.label' => '']), route('chief.back.menus.show', 'main'), route('chief.back.menuitem.store'));
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
