<?php

namespace Chief\Tests\Feature\Pages;

use Chief\Pages\Page;
use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;
use Chief\Users\User;

class UpdatePageTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /** @test */
    public function it_can_edit_an_Page()
    {
        $this->disableExceptionHandling();

        $page = factory(Page::class)->create(['title:nl' => 'titel nl']);

        $response = $this->actingAs(factory(User::class)->make(), 'admin')
            ->put(route('back.pages.update', $page->id), $this->validParams([
                'trans.nl.slug'     => '<b>slug</b>',
                'trans.fr.slug'     => '<b>slugfr</b>',
                'trans.nl.title'    => 'title',
                'trans.fr.title'    => 'titlefr',
            ]));

        $this->assertEquals('title', Page::first()->{'title:nl'});
        $this->assertEquals('titlefr', Page::first()->{'title:fr'});
    }

    private function validParams($overrides = [])
    {
        $params = [
            'trans' => [
                'nl' => [
                    'slug' => 'new-slug',
                    'title' => 'new title',
                    'content' => 'new content in <strong>bold</strong>',
                    'seo_title' => 'new seo title',
                    'seo_description' => 'new seo description',
                ],
                'fr' => [
                    'slug' => 'nouveau-slug',
                    'title' => 'nouveau title',
                    'content' => 'nouveau content in <strong>bold</strong>',
                    'seo_title' => 'nouveau seo title',
                    'seo_description' => 'nouveau seo description',
                ],
            ],
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }
}