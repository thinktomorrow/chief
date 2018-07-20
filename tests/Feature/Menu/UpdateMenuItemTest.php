<?php

namespace Thinktomorrow\Chief\Tests\Feature\MenuItems;

use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Users\User;

class UpdateMenuItemTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        app()->setLocale('nl');
    }

    /** @test */
    public function admin_can_view_the_update_form()
    {
        $menuitem = factory(MenuItem::class)->create();

        $response = $this->asAdminWithoutRole()->get(route('chief.back.menu.edit', $menuitem->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_update_form()
    {
        $menuitem = factory(MenuItem::class)->create();

        $response = $this->get(route('chief.back.menu.edit', $menuitem->id));
        $response->assertStatus(302)->assertRedirect(route('chief.back.login'));
    }

    /** @test */
    public function editing_a_new_menuItem()
    {
        $menuitem = factory(MenuItem::class)->create();

        $response = $this->asAdmin()
            ->put(route('chief.back.menu.update', $menuitem->id), $this->validParams(['trans.nl.label' => 'foobar', 'trans.nl.url' => 'https://thinktomorrow.be']));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menu.index'));

        $this->assertCount(1, MenuItem::all());
        $this->assertNewValues(MenuItem::first(), ['trans.nl.label' => 'foobar', 'trans.nl.url' => 'https://thinktomorrow.be']);
    }

    /** @test */
    public function only_authenticated_admin_can_update_a_menuItem()
    {
        $menuitem = factory(MenuItem::class)->create();

        $response = $this->put(route('chief.back.menu.update', $menuitem->id), $this->validParams(['trans.nl.label' => 'foobar']));

        $response->assertRedirect(route('chief.back.login'));
        $this->assertNewValues(MenuItem::first(), ['trans.nl.label' => 'nieuw label', 'trans.nl.url' => null]);
    }

    /** @test */
    public function editing_an_internal_menuItem()
    {
        $page       = factory(Page::class)->create();
        $newpage    = factory(Page::class)->create();
        $menuitem   = factory(MenuItem::class)->create(['type' => 'internal', 'page_id' => $page->flatReference()->get()]);

        $response = $this->asAdminWithoutRole()
            ->put(route('chief.back.menu.update', $menuitem->id), $this->validParams([
                'type' => 'internal',
                'page_id' => $newpage->flatReference()->get(),
                'trans.nl.label' => 'foobar',
            ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menu.index'));

        $this->assertCount(1, MenuItem::all());
        $this->assertNewValues(MenuItem::first(), ['type' => 'internal', 'trans.nl.label' => 'foobar', 'page_id' => $newpage->id]);
    }

    /** @test */
    public function editing_a_custom_menuItem()
    {
        $this->disableExceptionHandling();
        $menuitem   = factory(MenuItem::class)->create(['type' => 'custom', 'url:nl' => 'http://google.com']);

        $response = $this->asAdminWithoutRole()
            ->put(route('chief.back.menu.update', $menuitem->id), $this->validParams(['type' => 'custom', 'trans.nl.url' => 'https://thinktomorrow.be']));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menu.index'));

        $this->assertCount(1, MenuItem::all());
        $this->assertNewValues(MenuItem::first(), ['type' => 'custom', 'trans.nl.url' => 'https://thinktomorrow.be']);
    }

    /** @test */
    public function a_menuitem_can_be_nested()
    {
        // Reference here cause we need it twice
        $defaultAdmin = factory(User::class)->make();

        $parent = factory(MenuItem::class)->create(['type' => 'custom', 'label:nl' => 'foobar', 'url:nl' => 'http://google.com']);
        $child = factory(MenuItem::class)->create(['type' => 'custom', 'label:nl' => 'foobar', 'url:nl' => 'http://google.com']);

        $response = $this->actingAs($defaultAdmin, 'chief')
            ->put(route('chief.back.menu.update', $child->id), $this->validParams([
                'allow_parent' => true,
                'parent_id' => $parent->id
            ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menu.index'));

        $this->assertCount(1, $parent->fresh()->children);
        $this->assertEquals($parent->id, MenuItem::find(2)->parent->id); // Hardcoded assumption that newly created has id of 2

        // If item can be set to top level again
        $this->actingAs($defaultAdmin, 'chief')
            ->put(route('chief.back.menu.update', $child->id), $this->validParams([
                'allow_parent' => false,
            ]));

        $this->assertCount(0, $parent->fresh()->children);
        $this->assertNull(MenuItem::find(2)->parent_id); // Hardcoded assumption that newly created has id of 2
    }

    /** @test */
    public function a_menuitem_can_be_sorted()
    {
        // Reference here cause we need it twice
        $defaultAdmin = factory(User::class)->make();

        $secondItem = factory(MenuItem::class)->create(['type' => 'custom', 'label:nl' => 'foobar', 'url:nl' => 'http://google.com', 'order' => 2]);
        $firstItem = factory(MenuItem::class)->create(['type' => 'custom', 'label:nl' => 'foobar', 'url:nl' => 'http://google.com', 'order' => 1]);
        $thirdItem = factory(MenuItem::class)->create(['type' => 'custom', 'label:nl' => 'foobar', 'url:nl' => 'http://google.com', 'order' => 3]);

        $response = $this->actingAs($defaultAdmin, 'chief')
            ->put(route('chief.back.menu.update', $secondItem->id), $this->validParams([
                'order' => 1,
            ]));

        $items = MenuItem::all();
        $this->assertCount(3, $items);
        $this->assertEquals($secondItem->id, $items[0]->id);
        $this->assertEquals($firstItem->id, $items[1]->id);
        $this->assertEquals($thirdItem->id, $items[2]->id);
    }

    /** @test */
    public function url_field_should_be_valid_url()
    {
        $menuitem   = factory(MenuItem::class)->create(['type' => 'custom', 'url:nl' => 'http://google.com']);

        $this->asAdminWithoutRole()
            ->put(route('chief.back.menu.update', $menuitem->id), $this->validParams([
                'trans.nl.label'      => 'new label',
                'trans.nl.url'      => 'thinktomorrow.be',
            ]));

        $this->assertEquals('http://thinktomorrow.be', $menuitem->fresh()->url);
    }

    /** @test */
    public function label_is_required()
    {
        $menuitem   = factory(MenuItem::class)->create(['type' => 'custom', 'label:nl' => 'foobar', 'url:nl' => 'http://google.com']);

        $this->assertValidation(new MenuItem(), 'trans.nl.label', $this->validParams(['trans.nl.label' => '', 'url:nl' => 'http://google.com']),
            route('chief.back.menu.index'),
            route('chief.back.menu.update', $menuitem->id),
            1,
            'put'
        );
    }

    /** @test */
    public function type_internal_makes_pageid_required()
    {
        $page       = factory(Page::class)->create();
        $menuitem   = factory(MenuItem::class)->create(['type' => 'internal', 'page_id' => $page->flatReference()->get()]);

        $this->assertValidation(new MenuItem(), 'page_id', $this->validParams(['type' => 'internal', 'page_id' => '']),
            route('chief.back.menu.index'),
            route('chief.back.menu.update', $menuitem->id),
            1,
            'put'
        );
    }

    /** @test */
    public function pageid_should_exists_in_db()
    {
        $page       = factory(Page::class)->create();
        $menuitem   = factory(MenuItem::class)->create(['type' => 'internal', 'page_id' => $page->id, 'label:nl' => 'nieuw label']);

        // Inside our logic the page should be existing. If not, the creation is aborted but we do not
        // show this response to the interface since this is rather a hack then expected behaviour.
        $this->asAdminWithoutRole()
            ->put(route('chief.back.menu.update', $menuitem->id), $this->validParams([
                'type' => 'internal',
                'trans.nl.label' => 'updated label',
                'page_id' => Single::class.'@999' // Fake page reference
            ]));

        // Assert our values are still the same
        $this->assertNewValues($menuitem->fresh(), ['type' => 'internal', 'page_id' => $page->id, 'trans.nl.url' => null]);
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
                ]
            ],
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }


    private function assertNewValues($menuItem, $overrides = [])
    {
        $this->assertEquals($overrides['type'] ?? 'custom', $menuItem->{'type'});
        $this->assertEquals($overrides['page_id'] ?? '', $menuItem->{'page_id'});

        $this->assertEquals($overrides['trans.nl.label'] ?? 'nieuw label', $menuItem->{'label:nl'});
        $this->assertEquals(array_key_exists('trans.nl.url', $overrides) ? $overrides['trans.nl.url'] :  'http://google.com', $menuItem->{'url:nl'});

        $this->assertEquals($overrides['trans.en.label'] ?? '', $menuItem->{'label:en'});
        $this->assertEquals($overrides['trans.en.url'] ?? '', $menuItem->{'url:en'});
    }
}
