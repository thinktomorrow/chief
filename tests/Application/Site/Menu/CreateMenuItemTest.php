<?php

namespace Thinktomorrow\Chief\Tests\Application\Site\Menu;

use Thinktomorrow\Url\Url;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Site\Menu\Events\MenuItemCreated;

class CreateMenuItemTest extends ChiefTestCase
{
    /** @test */
    public function admin_can_view_the_create_form()
    {
        $this->disableExceptionHandling();
        $response = $this->asAdmin()->get(route('chief.back.menuitem.create', 'main'));
        $response->assertViewIs('chief::admin.menu.create')
                 ->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_create_form()
    {
        $response = $this->get(route('chief.back.menuitem.create', 'main'));
        $response->assertStatus(302)
                 ->assertRedirect(route('chief.back.login'));
    }

    /** @test */
    public function only_authenticated_admin_can_create_a_menu_item()
    {
        $response = $this->post(route('chief.back.menuitem.store'), $this->validParams(['trans.nl.url' => 'https://thinktomorrow.be']));

        $response->assertRedirect(route('chief.back.login'));
        $this->assertCount(0, MenuItem::all());
    }

    /** @test */
    public function creating_a_new_menu_item()
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

        $this->assertEquals('https://thinktomorrow.be', $item->getAdminUrlLabel('nl'));
        $this->assertEquals('https://thinktomorrow.co.uk', $item->getAdminUrlLabel('en'));
    }

    /** @test */
    public function creating_a_new_menu_item_emits_event()
    {
        Event::fake();

        $this->asAdmin()->post(route('chief.back.menuitem.store'), $this->validParams());

        Event::assertDispatched(MenuItemCreated::class);
    }

    /** @test */
    public function creating_a_new_internal_menu_item()
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

        $this->assertEquals('foobar-nl', $item->getUrl('nl'));
        $this->assertEquals('foobar-en', $item->getUrl('en'));

        $this->assertEquals('label one', $item->getLabel('nl'));
        $this->assertEquals('label two', $item->getLabel('en'));

        $this->assertEquals('artikel pagetitle nl', $item->getAdminUrlLabel('nl'));
        $this->assertEquals('artikel pagetitle en', $item->getAdminUrlLabel('en'));
    }

    /** @test */
    public function a_menuitem_can_be_nested()
    {
        $parent = MenuItem::create(['type' => 'custom', 'label' => ['nl' => 'foobar'], 'url' => ['nl' => 'http://google.com']]);

        $response = $this->asAdmin()
            ->post(route('chief.back.menuitem.store'), $this->validParams([
                'allow_parent' => true,
                'parent_id' => $parent->id,
            ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menus.show', 'main'));

        $this->assertCount(1, $parent->fresh()->children);
        $this->assertEquals($parent->id, MenuItem::find(2)->parent->id); // Hardcoded assumption that newly created has id of 2
    }

    /** @test */
    public function url_field_is_sanitized_if_scheme_is_missing()
    {
        $this->asAdmin()
            ->post(route('chief.back.menuitem.store'), $this->validParams([
                'type' => 'custom',
                'trans.nl.url' => 'thinktomorrow.be',
            ]));

        $this->assertEquals('https://thinktomorrow.be', MenuItem::first()->url);
    }

    /** @test */
    public function label_is_required()
    {
        $this->assertValidation(new MenuItem(), 'trans.nl.label', $this->validParams(['trans.nl.label' => '']), route('chief.back.menus.show', 'main'), route('chief.back.menuitem.store'));
    }

    /** @test */
    public function type_internal_makes_owner_required()
    {
        $this->assertValidation(new MenuItem(), 'owner_reference', $this->validParams(['type' => 'internal', 'owner_reference' => '']), route('chief.back.menus.show', 'main'), route('chief.back.menuitem.store'));
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
