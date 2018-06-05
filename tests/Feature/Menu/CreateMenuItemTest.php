<?php

namespace Thinktomorrow\Chief\Tests\Feature\MenuItems;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Menu\MenuItem;

class CreateMenuItemTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();
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
        $this->markTestIncomplete();
        $response = $this->asDefaultAdmin()
            ->post(route('chief.back.menu.store'), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.menu.index'));

        $this->assertCount(1, MenuItem::all());
        $this->assertNewValues(MenuItem::first());
    }

    /** @test */
    public function only_authenticated_admin_can_create_a_menuItem()
    {
        $this->markTestIncomplete();
        
        $response = $this->post(route('chief.back.menu.store'), $this->validParams());

        $response->assertRedirect(route('chief.back.login'));
        $this->assertCount(0, MenuItem::all());
    }

    private function validParams($overrides = [])
    {
        $params = [
            'trans' => [
                'nl' => [
                    'slug'              => 'new-slug',
                    'title'             => 'new title',
                    'content'           => 'new content in <strong>bold</strong>',
                    'seo_title'         => 'new seo title',
                    'seo_description'   => 'new seo description',
                ],
                'en' => [
                    'slug'              => 'nouveau-slug',
                    'title'             => 'nouveau title',
                    'content'           => 'nouveau content in <strong>bold</strong>',
                    'seo_title'         => 'nouveau seo title',
                    'seo_description'   => 'nouveau seo description',
                ],
            ],
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }


    private function assertNewValues($MenuItem)
    {
        $this->assertEquals('new-title', $MenuItem->{'slug:nl'});
        $this->assertEquals('new title', $MenuItem->{'title:nl'});
        $this->assertEquals('new content in <strong>bold</strong>', $MenuItem->{'content:nl'});
        $this->assertEquals('new seo title', $MenuItem->{'seo_title:nl'});
        $this->assertEquals('new seo description', $MenuItem->{'seo_description:nl'});

        $this->assertEquals('nouveau-title', $MenuItem->{'slug:en'});
        $this->assertEquals('nouveau title', $MenuItem->{'title:en'});
        $this->assertEquals('nouveau content in <strong>bold</strong>', $MenuItem->{'content:en'});
        $this->assertEquals('nouveau seo title', $MenuItem->{'seo_title:en'});
        $this->assertEquals('nouveau seo description', $MenuItem->{'seo_description:en'});
    }
}
