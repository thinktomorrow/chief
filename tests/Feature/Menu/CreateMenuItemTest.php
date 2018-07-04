<?php

namespace Thinktomorrow\Chief\Tests\Feature\MenuItems;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Pages\Page;

class CreateMenuItemTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();
        app()->setLocale('nl');
    }

    /** @test */
    public function admin_can_view_the_create_form()
    {
        $response = $this->asDefaultAdmin()->get(route('chief.back.menu.create'));
        $response->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_create_form()
    {
        $response = $this->get(route('chief.back.menu.create'));
        $response->assertStatus(302)->assertRedirect(route('chief.back.login'));
    }

    /** @test */
    public function creating_a_new_menuItem()
    {
        $response = $this->asDefaultAdmin()
            ->post(route('chief.back.menu.store'), $this->validParams(['trans.nl.url'   => 'https://thinktomorrow.be']));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menu.index'));

        $this->assertCount(1, MenuItem::all());
        $this->assertNewValues(MenuItem::first(), ['trans.nl.url' => 'https://thinktomorrow.be']);
    }

    /** @test */
    public function only_authenticated_admin_can_create_a_menuItem()
    {
        $response = $this->post(route('chief.back.menu.store'), $this->validParams(['trans.nl.url'   => 'https://thinktomorrow.be']));

        $response->assertRedirect(route('chief.back.login'));
        $this->assertCount(0, MenuItem::all());
    }

    /** @test */
    public function an_menuitem_can_be_nested()
    {
        $parent = factory(MenuItem::class)->create(['type' => 'custom', 'label:nl' => 'foobar', 'url:nl' => 'http://google.com']);

        $response = $this->asDefaultAdmin()
            ->post(route('chief.back.menu.store'), $this->validParams([
                'allow_parent' => true,
                'parent_id' => $parent->id
            ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menu.index'));

        $this->assertCount(2, MenuItem::all());
        $this->assertCount(1, $parent->fresh()->children);
        $this->assertEquals($parent->id, MenuItem::find(2)->parent->id); // Hardcoded assumption that newly created has id of 2
    }

    /** @test */
    public function creating_a_new_internal_menuItem()
    {
        $page = factory(Page::class)->create();
        $response = $this->asDefaultAdmin()
            ->post(route('chief.back.menu.store'), $this->validParams(['type' => 'internal', 'page_id' => $page->flatReference()->get()]));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menu.index'));

        $this->assertCount(1, MenuItem::all());
        $this->assertNewValues(MenuItem::first(), ['type' => 'internal', 'trans.nl.url' => null]);
    }

    /** @test */
    public function creating_a_new_custom_menuItem()
    {
        $response = $this->asDefaultAdmin()
            ->post(route('chief.back.menu.store'), $this->validParams([
                    'type'              => 'custom',
                    'trans.nl.label'    => 'nieuw label',
                    'trans.nl.url'      => 'https://thinktomorrow.be',
                    'trans.en.label'    => 'new label',
                    'trans.en.url'      => 'https://thinktomorrow.com'
                ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menu.index'));

        $this->assertCount(1, MenuItem::all());
        $this->assertNewValues(MenuItem::first(), [
            'type'              => 'custom',
            'trans.nl.url'      => 'https://thinktomorrow.be',
            'trans.en.label'    => 'new label',
            'trans.en.url'      => 'https://thinktomorrow.com'
        ]);
    }

    /** @test */
    public function create_custom_without_link()
    {
        $response = $this->asDefaultAdmin()
            ->post(route('chief.back.menu.store'), $this->validParams([
                    'type'              => 'custom',
                    'trans.nl.label'    => 'nieuw label',
                    'trans.en.label'    => 'new label',
                ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menu.index'));

        $this->assertCount(1, MenuItem::all());
        $this->assertNewValues(MenuItem::first(), [
            'type'              => 'custom',
            'trans.en.label'    => 'new label',
        ]);
    }

    /** @test */
    public function url_field_is_sanitized_if_scheme_is_missing()
    {
        $this->asDefaultAdmin()
            ->post(route('chief.back.menu.store'), $this->validParams([
                'type'              => 'custom',
                'trans.nl.label'    => 'nieuw label',
                'trans.nl.url'      => 'thinktomorrow.be',
            ]));

        $this->assertEquals('http://thinktomorrow.be', MenuItem::first()->url);
    }

    /** @test */
    public function type_needs_to_be_custom_or_internal()
    {
        $this->assertValidation(new MenuItem(), 'type', $this->validParams(['type' => 'foobar']),
            route('chief.back.menu.index'),
            route('chief.back.menu.store')
        );
    }

    /** @test */
    public function label_is_required()
    {
        $this->assertValidation(new MenuItem(), 'trans.nl.label', $this->validParams(['trans.nl.label' => '']),
            route('chief.back.menu.index'),
            route('chief.back.menu.store')
        );
    }

    /** @test */
    public function type_internal_makes_pageid_required()
    {
        $this->assertValidation(new MenuItem(), 'page_id', $this->validParams(['type' => 'internal', 'page_id' => '']),
            route('chief.back.menu.index'),
            route('chief.back.menu.store')
        );
    }

    /** @test */
    public function pageid_should_exists_in_db()
    {
        // Inside our logic the page should be existing. If not, the creation is aborted but we do not
        // show this response to the interface since this is rather a hack then expected behaviour.
        $this->asDefaultAdmin()
            ->post(route('chief.back.menu.store'), $this->validParams([
                'type' => 'internal',
                'page_id' => Page::class.'@999' // Fake page reference
            ]));

        $this->assertEquals(0, MenuItem::count());
    }

    private function validParams($overrides = [])
    {
        $params = [
            'type'  => 'custom',
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
        $this->assertEquals($overrides['parent_id'] ?? null, $menuItem->parent_id);

        $this->assertEquals($overrides['trans.nl.label'] ?? 'nieuw label', $menuItem->{'label:nl'});
        $this->assertEquals($overrides['trans.nl.url'] ?? 'http://google.com', $menuItem->{'url:nl'});

        $this->assertEquals($overrides['trans.en.label'] ?? '', $menuItem->{'label:en'});
        $this->assertEquals($overrides['trans.en.url'] ?? '', $menuItem->{'url:en'});
    }
}
