<?php

namespace Chief\Tests\Feature\Articles;

use Chief\Articles\Article;
use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;
use Chief\Users\User;

class UpdateArticleTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /** @test */
    public function it_can_edit_an_article()
    {
        $this->disableExceptionHandling();

        $article = factory(Article::class)->create(['title:nl' => 'titel nl']);

        $response = $this->actingAs(factory(User::class)->create())
            ->put(route('back.articles.update', $article->id), $this->validParams([
                'trans.nl.slug'     => '<b>slug</b>',
                'trans.fr.slug'     => '<b>slugfr</b>',
                'trans.nl.title'    => 'title',
                'trans.fr.title'    => 'titlefr',
            ]));

            $this->assertEquals('title', Article::first()->{'title:nl'});
        $this->assertEquals('titlefr', Article::first()->{'title:fr'});
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