<?php

namespace Thinktomorrow\Chief\Tests\Application\Site\Menu;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class UpdateMenuItemTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        app()->setLocale('nl');
    }

    /** @test */
    public function admin_can_view_the_update_form()
    {
        $this->disableExceptionHandling();
        $menuitem = MenuItem::create();

        $response = $this->asAdmin()->get(route('chief.back.menuitem.edit', $menuitem->id));
        $response->assertViewIs('chief::admin.menu.edit')
            ->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_update_form()
    {
        $menuitem = MenuItem::create();

        $response = $this->get(route('chief.back.menuitem.edit', $menuitem->id));
        $response->assertStatus(302)->assertRedirect(route('chief.back.login'));
    }

    /** @test */
    public function editing_a_new_menuItem()
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

    /** @test */
    public function only_authenticated_admin_can_update_a_menuItem()
    {
        $menuitem = MenuItem::create(['label' => ['nl' => 'existing label']]);

        $this->put(route('chief.back.menuitem.update', $menuitem->id), $this->validParams(['trans.nl.label' => 'foobar']))
            ->assertRedirect(route('chief.back.login'));

        $this->assertEquals('existing label', MenuItem::first()->label);
    }

    /** @test */
    public function editing_an_internal_menuItem()
    {
        ArticlePage::migrateUp();

        $page = ArticlePage::create();
        $menuitem = MenuItem::create();

        $this->asAdmin()
            ->put(route('chief.back.menuitem.update', $menuitem->id), $this->validParams([
                'type' => 'internal',
                'owner_reference' => $page->modelReference()->getShort(),
            ]))->assertStatus(302);

        $this->assertEquals($page->modelReference(), $menuitem->fresh()->owner->modelReference());
    }

    /** @test */
    public function a_relative_url_is_sanitized_to_proper_relative_url()
    {
        $menuitem = MenuItem::create();

        $this->asAdmin()
            ->put(route('chief.back.menuitem.update', $menuitem->id), $this->validParams([
                'type' => 'custom',
                'trans.nl.url' => 'contact',
            ]));

        $this->assertEquals('/contact', $menuitem->fresh()->url);
    }

    /** @test */
    public function a_menuitem_can_be_nested()
    {
        $parent = MenuItem::create();
        $child = MenuItem::create();

        $this->asAdmin()
            ->put(route('chief.back.menuitem.update', $child->id), $this->validParams([
                'allow_parent' => true,
                'parent_id' => $parent->id,
            ]))->assertStatus(302);

        $this->assertCount(1, $parent->fresh()->children);
        $this->assertEquals($parent->id, MenuItem::find(2)->parent->id); // Hardcoded assumption that newly created has id of 2

        // If item can be set to top level again
        $this->asAdmin()
            ->put(route('chief.back.menuitem.update', $child->id), $this->validParams([
                'allow_parent' => false,
            ]));

        $this->assertCount(0, $parent->fresh()->children);
        $this->assertNull(MenuItem::find(2)->parent_id); // Hardcoded assumption that newly created has id of 2
    }

    /** @test */
    public function a_menuitem_can_be_sorted()
    {
        $secondItem = MenuItem::create(['type' => 'custom', 'order' => 2]);
        $firstItem = MenuItem::create(['type' => 'custom', 'order' => 1]);
        $thirdItem = MenuItem::create(['type' => 'custom', 'order' => 3]);

        $this->asAdmin()
            ->put(route('chief.back.menuitem.update', $secondItem->id), $this->validParams([
                'order' => 1,
            ]));

        $items = MenuItem::all();
        $this->assertCount(3, $items);
        $this->assertEquals($secondItem->id, $items[0]->id);
        $this->assertEquals($firstItem->id, $items[1]->id);
        $this->assertEquals($thirdItem->id, $items[2]->id);
    }

    /** @test */
    public function url_field_is_sanitized_to_valid_url()
    {
        $menuitem = MenuItem::create(['type' => 'custom', 'url' => ['nl' => 'http://google.com']]);

        $this->asAdmin()
            ->put(route('chief.back.menuitem.update', $menuitem->id), $this->validParams([
                'trans.nl.label' => 'new label',
                'trans.nl.url' => 'thinktomorrow.be',
            ]));

        $this->assertEquals('https://thinktomorrow.be', $menuitem->fresh()->url);
    }

    private function validParams($overrides = [])
    {
        $params = [
            'type' => 'custom',
            'allow_parent' => false,      // flag to allow nesting or not
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
