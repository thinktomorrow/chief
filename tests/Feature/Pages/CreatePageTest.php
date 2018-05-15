<?php

namespace Chief\Tests\Feature\Pages;

use Chief\Pages\Page;
use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;
use Chief\Users\User;
use Chief\Pages\Application\CreatePage;

class CreatePageTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /** @test */
    function admin_can_view_the_create_form()
    {
        $response = $this->asDefaultAdmin()->get(route('back.pages.create'));
        $response->assertStatus(200);
    }

    /** @test */
    function guests_cannot_view_the_create_form()
    {
        $response = $this->get(route('back.pages.create'));
        $response->assertStatus(302)->assertRedirect(route('back.login'));
    }

    /** @test */
    function creating_a_new_page()
    {
        $response = $this->asDefaultAdmin()
            ->post(route('back.pages.store'), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('back.pages.index'));

        $this->assertCount(1, Page::all());
        $this->assertNewValues(Page::first());
    }

    /** @test */
    function only_authenticated_admin_can_create_a_page()
    {
        $response = $this->post(route('back.pages.store'), $this->validParams());

        $response->assertRedirect(route('back.login'));
        $this->assertCount(0, Page::all());
    }

    /** @test */
    function when_creating_page_title_is_required()
    {
        $this->assertValidation(new Page(), 'trans.nl.title', $this->validParams(['trans.nl.title' => '']),
            route('back.pages.index'),
            route('back.pages.store')
        );
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $page = factory(Page::class)->create([
                'title:nl'  => 'titel nl',
                'slug:nl'   => 'foobarnl'
            ]);

        $this->assertCount(1, Page::all());

        $response = $this->asDefaultAdmin()
            ->post(route('back.pages.store'), $this->validParams([
                    'title:nl'  => 'foobarnl',
                    'title:fr'  => 'foobarfr',
                ])
            );

        $response->assertStatus(302);

        $pages = Page::all();
        $this->assertCount(2, $pages);
        $this->assertNotEquals($pages->first()->slug, $pages->last()->slug);
    }

    /** @test */
    public function it_can_remove_a_page()
    {
        $response = $this->asDefaultAdmin()
            ->post(route('back.pages.store'), $this->validParams());

        $this->assertCount(1, Page::all());

        $this->actingAs(factory(User::class)->make())
            ->delete(route('back.pages.destroy', Page::first()->id), $this->validParams());

        $this->assertCount(0, Page::all());
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
                'fr' => [
                    'slug'              => 'nouveau-slug',
                    'title'             => 'nouveau title',
                    'content'           => 'nouveau content in <strong>bold</strong>',
                    'seo_title'         => 'nouveau seo title',
                    'seo_description'   => 'nouveau seo description',
                ],
            ],
        ];

        foreach ($overrides as $key => $value){
            array_set($params,  $key, $value);
        }

        return $params;
    }


    private function assertNewValues($page)
    {
        $this->assertEquals('new-title', $page->{'slug:nl'});
        $this->assertEquals('new title', $page->{'title:nl'});
        $this->assertEquals('new content in <strong>bold</strong>', $page->{'content:nl'});
        $this->assertEquals('new seo title', $page->{'seo_title:nl'});
        $this->assertEquals('new seo description', $page->{'seo_description:nl'});

        $this->assertEquals('nouveau-title', $page->{'slug:fr'});
        $this->assertEquals('nouveau title', $page->{'title:fr'});
        $this->assertEquals('nouveau content in <strong>bold</strong>', $page->{'content:fr'});
        $this->assertEquals('nouveau seo title', $page->{'seo_title:fr'});
        $this->assertEquals('nouveau seo description', $page->{'seo_description:fr'});
    }
}