<?php

namespace Chief\Tests\Feature\Pages;

use Chief\Pages\Page;
use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;

class UpdatePageTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /** @test */
    public function it_can_edit_a_page()
    {
        $page = factory(Page::class)->create(['title:nl' => 'titel nl']);

        $response = $this->asDefaultAdmin()
            ->put(route('back.pages.update', $page->id), $this->validParams([
                'trans.nl.slug'     => '<b>slug</b>',
                'trans.en.slug'     => '<b>slugen</b>',
                'trans.nl.title'    => 'title',
                'trans.en.title'    => 'titleen',
            ]));

        $this->assertEquals('title', Page::first()->{'title:nl'});
        $this->assertEquals('titleen', Page::first()->{'title:en'});
    }

    /** @test */
    public function it_can_update_the_page_relations()
    {
        $this->disableExceptionHandling();

        $page = factory(Page::class)->create();
        $otherPage = factory(Page::class)->create();

        $this->asAdmin()
            ->put(route('back.pages.update', $page->id), $this->validParams([
                'relations' => [
                    $otherPage->getRelationId()
                ]
            ]));

        $this->assertCount(1, $page->children());
        $this->assertEquals($otherPage->id, $page->children()->first()->id);
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
                'en' => [
                    'slug' => 'nouveau-slug',
                    'title' => 'nouveau title',
                    'content' => 'nouveau content in <strong>bold</strong>',
                    'seo_title' => 'nouveau seo title',
                    'seo_description' => 'nouveau seo description',
                ],
            ],
            'relations' => [],
        ];

        foreach ($overrides as $key => $value) {
            array_set($params, $key, $value);
        }

        return $params;
    }
}