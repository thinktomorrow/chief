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
    public function creating_a_new_internal_menuItem()
    {
        $page = factory(Page::class)->create();
        $response = $this->asDefaultAdmin()
            ->post(route('chief.back.menu.store'), $this->validParams(['type' => 'internal', 'page_id' => $page->getRelationId()]));

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menu.index'));

        $this->assertCount(1, MenuItem::all());
        $this->assertNewValues(MenuItem::first(), ['type' => 'internal']);
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
    public function type_custom_makes_url_required()
    {
        $this->assertValidation(new MenuItem(), 'trans.nl.url', $this->validParams(['type' => 'custom', 'trans.nl.url' => '']),
            route('chief.back.menu.index'),
            route('chief.back.menu.store')  
        );
    }

    /** @test */
    public function url_field_should_be_valid_url()
    {
        $this->assertValidation(new MenuItem(), 'trans.nl.url', $this->validParams(['type' => 'custom', 'trans.nl.url' => 'test']),
            route('chief.back.menu.index'),
            route('chief.back.menu.store')  
        );
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
        $this->assertValidation(new MenuItem(), 'id', $this->validParams(['type' => 'internal', 'page_id' => Page::class.'@1']),
            route('chief.back.menu.index'),
            route('chief.back.menu.store')  
        );
    }

    private function validParams($overrides = [])
    {
        $params = [
            'type'  => 'custom',
            'trans' => [
                'nl' => [
                    'label' => 'nieuw label',
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

        $this->assertEquals($overrides['trans.nl.label'] ?? 'nieuw label', $menuItem->{'label:nl'});
        $this->assertEquals($overrides['trans.nl.url'] ?? '', $menuItem->{'url:nl'});

        $this->assertEquals($overrides['trans.en.label'] ?? '', $menuItem->{'label:en'});
        $this->assertEquals($overrides['trans.en.url'] ?? '', $menuItem->{'url:en'});
    }
}
