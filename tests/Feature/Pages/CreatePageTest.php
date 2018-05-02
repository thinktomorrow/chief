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
        $response = $this->actingAs(factory(User::class)->make())->get(route('back.pages.create'));
        $response->assertStatus(200);
    }

    /** @test */
    function guests_cannot_view_the_create_form()
    {
        $response = $this->get(route('back.pages.create'));
        $response->assertStatus(302)->assertRedirect(route('back.login'));
    }

    /** @test */
    function creating_a_new_Page()
    {
        $this->disableExceptionHandling();

        $response = $this->actingAs(factory(User::class)->make())
            ->post(route('back.pages.store'), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('back.pages.index'));

        $this->assertCount(1, Page::all());
        $this->assertNewValues(Page::first());
    }

    /** @test */
    function only_authenticated_admin_can_create_a_Page()
    {
        $response = $this->post(route('back.pages.store'), $this->validParams());

        $response->assertRedirect(route('back.login'));
        $this->assertCount(0, Page::all());
    }

    /** @test */
    function when_creating_Page_slug_is_required()
    {
        $this->assertValidation(new Page(), 'trans.nl.slug', $this->validParams(['trans.nl.slug' => '']),
            route('back.pages.index'),
            route('back.pages.store')
        );
    }

    /** @test */
    public function when_creating_Page_slug_will_be_stripped_of_html()
    {
        $response = $this->actingAs(factory(User::class)->make())
            ->post(route('back.pages.store'), $this->validParams([
                'trans.nl.slug' => '<b>slug</b>',
                'trans.fr.slug' => '<b>slugfr</b>',
                ]));

        $this->assertEquals('slug', Page::first()->{'slug:nl'});
        $this->assertEquals('slugfr', Page::first()->{'slug:fr'});
    }

    /** @test */
    function when_creating_Page_title_is_required()
    {
        $this->assertValidation(new Page(), 'trans.nl.title', $this->validParams(['trans.nl.title' => '']),
            route('back.pages.index'),
            route('back.pages.store')
        );
    }

    /** @test */
    public function slug_must_be_unique()
    {
        factory(Page::class)->create(['slug:nl' => 'existing-slug']);

        $this->assertValidation(new Page(), 'trans.nl.slug', $this->validParams(['trans.nl.slug' => 'existing-slug']),
            route('back.pages.index'),
            route('back.pages.store'),
            1
        );
    }

    /** @test */
    public function it_can_remove_an_Page()
    {
        $response = $this->actingAs(factory(User::class)->make())
            ->post(route('back.pages.store'), $this->validParams());

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


    private function assertNewValues($Page)
    {
        $this->assertEquals('new-slug', $Page->{'slug:nl'});
        $this->assertEquals('new title', $Page->{'title:nl'});
        $this->assertEquals('new content in <strong>bold</strong>', $Page->{'content:nl'});
        $this->assertEquals('new seo title', $Page->{'seo_title:nl'});
        $this->assertEquals('new seo description', $Page->{'seo_description:nl'});

        $this->assertEquals('nouveau-slug', $Page->{'slug:fr'});
        $this->assertEquals('nouveau title', $Page->{'title:fr'});
        $this->assertEquals('nouveau content in <strong>bold</strong>', $Page->{'content:fr'});
        $this->assertEquals('nouveau seo title', $Page->{'seo_title:fr'});
        $this->assertEquals('nouveau seo description', $Page->{'seo_description:fr'});
    }
}