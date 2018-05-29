<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\TestCase;

class CreatePageTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();
    }

    /** @test */
    function admin_can_view_the_create_form()
    {
        $response = $this->asDefaultAdmin()->get(route('chief.back.pages.create'));
        $response->assertStatus(200);
    }

    /** @test */
    function guests_cannot_view_the_create_form()
    {
        $response = $this->get(route('chief.back.pages.create'));
        $response->assertStatus(302)->assertRedirect(route('chief.back.login'));
    }

    /** @test */
    function creating_a_new_page()
    {
        $response = $this->asDefaultAdmin()
            ->post(route('chief.back.pages.store'), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('chief.back.pages.index'));

        $this->assertCount(1, Page::all());
        $this->assertNewValues(Page::first());
    }

    /** @test */
    function only_authenticated_admin_can_create_a_page()
    {
        $response = $this->post(route('chief.back.pages.store'), $this->validParams());

        $response->assertRedirect(route('chief.back.login'));
        $this->assertCount(0, Page::all());
    }

    /** @test */
    function when_creating_page_title_is_required()
    {
        $this->assertValidation(new Page(), 'trans.nl.title', $this->validParams(['trans.nl.title' => '']),
            route('chief.back.pages.index'),
            route('chief.back.pages.store')
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
            ->post(route('chief.back.pages.store'), $this->validParams([
                    'title:nl'  => 'foobarnl',
                    'title:en'  => 'foobaren',
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
        factory(Page::class)->create(['published' => false]);
        $this->assertCount(1, Page::all());

        $this->asAdmin()
             ->delete(route('chief.back.pages.destroy', Page::first()->id), ['deleteconfirmation' => 'DELETE']);

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
                'en' => [
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

        $this->assertEquals('nouveau-title', $page->{'slug:en'});
        $this->assertEquals('nouveau title', $page->{'title:en'});
        $this->assertEquals('nouveau content in <strong>bold</strong>', $page->{'content:en'});
        $this->assertEquals('nouveau seo title', $page->{'seo_title:en'});
        $this->assertEquals('nouveau seo description', $page->{'seo_description:en'});
    }
}