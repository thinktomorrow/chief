<?php

namespace Thinktomorrow\Chief\Tests\Application\Site\Menu;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

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
    public function only_authenticated_admin_can_create_a_menuItem()
    {
        $response = $this->post(route('chief.back.menuitem.store'), $this->validParams(['trans.nl.url' => 'https://thinktomorrow.be']));

        $response->assertRedirect(route('chief.back.login'));
        $this->assertCount(0, MenuItem::all());
    }

    /** @test */
    public function creating_a_new_menuItem()
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

        $this->assertEquals('https://thinktomorrow.be', $item->url('nl'));
        $this->assertEquals('https://thinktomorrow.co.uk', $item->url('en'));

        app()->setLocale('nl');
        $this->assertEquals('label one', $item->label);

        app()->setLocale('en');
        $this->assertEquals('label two', $item->label);
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
    public function creating_a_new_internal_menuItem()
    {
        ArticlePage::migrateUp();
        $page = ArticlePage::create();

        $this->asAdmin()
            ->post(route('chief.back.menuitem.store'), $this->validParams(['type' => 'internal', 'owner_reference' => $page->modelReference()->getShort()]))
            ->assertStatus(302);

        $this->assertEquals($page->modelReference(), MenuItem::first()->owner->modelReference());
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
