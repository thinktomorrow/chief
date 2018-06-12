<?php

namespace Thinktomorrow\Chief\Tests\Feature\MenuItems;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Pages\Page;

class UpdateMenuItemTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();
    }

    /** @test */
    public function admin_can_view_the_update_form()
    {
        $menuitem = factory(MenuItem::class)->create();

        $response = $this->asDefaultAdmin()->get(route('chief.back.menu.edit', $menuitem->id));
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
            ->put(route('chief.back.menu.update', $menuitem->id), $this->validParams(['trans.nl.label' => 'foobar']));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menu.index'));

        $this->assertCount(1, MenuItem::all());
        $this->assertNewValues(MenuItem::first(), ['trans.nl.label' => 'foobar']);
    }

    /** @test */
    public function only_authenticated_admin_can_update_a_menuItem()
    {
        $menuitem = factory(MenuItem::class)->create();
        
        $response = $this->put(route('chief.back.menu.update', $menuitem->id), $this->validParams(['trans.nl.label' => 'foobar']));

        $response->assertRedirect(route('chief.back.login'));
        $this->assertNewValues(MenuItem::first());
    }

    /** @test */
    public function editing_a_new_internal_menuItem()
    {
        $page       = factory(Page::class)->create();
        $menuitem   = factory(MenuItem::class)->create(['type' => 'internal', 'page_id' => $page->getRelationId()]);
        
        $response = $this->asDefaultAdmin()
            ->put(route('chief.back.menu.update', $menuitem->id), $this->validParams(['label:nl' => 'foobar']));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menu.index'));

        $this->assertCount(1, MenuItem::all());
        $this->assertNewValues(MenuItem::first(), ['type' => 'internal', 'trans.nl.label' => 'foobar']);
    }

    /** @test */
    public function editing_a_new_custom_menuItem()
    {
        $this->disableExceptionHandling();
        $response = $this->asDefaultAdmin()
            ->put(route('chief.back.menu.update'), $this->validParams(['type' => 'custom', 'trans.nl.url' => 'https://thinktomorrow.be']));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menu.index'));

        $this->assertCount(1, MenuItem::all());
        $this->assertNewValues(MenuItem::first(), ['type' => 'custom']);
    }

    /** @test */
    public function type_custom_makes_url_required()
    {
        $this->assertValidation(new MenuItem(), 'trans.nl.url', $this->validParams(['type' => 'custom', 'trans.nl.url' => '']),
            route('chief.back.menu.index'),
            route('chief.back.menu.update')  
        );
    }

    /** @test */
    public function url_field_should_be_valid_url()
    {
        $this->assertValidation(new MenuItem(), 'trans.nl.url', $this->validParams(['type' => 'custom', 'trans.nl.url' => 'test']),
            route('chief.back.menu.index'),
            route('chief.back.menu.update')  
        );
    }

    /** @test */
    public function type_needs_to_be_custom_or_internal()
    {
        $this->assertValidation(new MenuItem(), 'type', $this->validParams(['type' => 'foobar']),
            route('chief.back.menu.index'),
            route('chief.back.menu.update')  
        );
    }

    /** @test */
    public function label_is_required()
    {
        $this->assertValidation(new MenuItem(), 'trans.nl.label', $this->validParams(['trans.nl.label' => '']),
            route('chief.back.menu.index'),
            route('chief.back.menu.update')  
        );
    }

    /** @test */
    public function type_internal_makes_pageid_required()
    {
        $this->assertValidation(new MenuItem(), 'page_id', $this->validParams(['type' => 'internal', 'page_id' => '']),
            route('chief.back.menu.index'),
            route('chief.back.menu.update')  
        );
    }

    /** @test */
    public function pageid_should_exists_in_db()
    {
        $this->assertValidation(new MenuItem(), 'id', $this->validParams(['type' => 'internal', 'page_id' => Page::class.'@1']),
            route('chief.back.menu.index'),
            route('chief.back.menu.update')  
        );
    }

    private function validParams($overrides = [])
    {
        $params = [
            'type'  => 'custom',
            'trans' => [
                'nl' => [
                    'label' => 'nieuw label',
                    'url'   => 'https://thinktomorrow.be',
                ],
                'en' => [
                    'label' => 'new label',
                    'url'   => 'https://thinktomorrow.be',
                ],
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

        $this->assertEquals($overrides['trans.nl.label'] ?? 'nieuw label', $menuItem->{'label:nl'});
        $this->assertEquals($overrides['trans.nl.url'] ?? 'https://thinktomorrow.be', $menuItem->{'url:nl'});

        $this->assertEquals($overrides['trans.en.label'] ?? 'new label', $menuItem->{'label:en'});
        $this->assertEquals($overrides['trans.en.url'] ?? 'https://thinktomorrow.be', $menuItem->{'url:en'});
    }
}
