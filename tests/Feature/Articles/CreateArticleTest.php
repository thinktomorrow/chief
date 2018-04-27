<?php

namespace Chief\Tests\Feature\Articles;

use Chief\Articles\Article;
use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;
use Chief\Users\User;
use Chief\Articles\Application\CreateArticle;

class CreateArticleTest extends TestCase
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
        $response = $this->actingAs(factory(User::class)->create())->get(route('back.articles.create'));
        $response->assertStatus(200);
    }

    /** @test */
    function guests_cannot_view_the_create_form()
    {
        $response = $this->get(route('back.articles.create'));
        $response->assertStatus(302)->assertRedirect(route('back.login'));
    }

    /** @test */
    function creating_a_new_article()
    {
        $this->disableExceptionHandling();

        $response = $this->actingAs(factory(User::class)->create())
            ->post(route('back.articles.store'), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('back.articles.index'));

        $this->assertCount(1, Article::all());
        $this->assertNewValues(Article::first());
    }

    /** @test */
    function only_authenticated_admin_can_create_a_article()
    {
        $response = $this->post(route('back.articles.store'), $this->validParams());

        $response->assertRedirect(route('back.login'));
        $this->assertCount(0, Article::all());
    }

    /** @test */
    function when_creating_article_slug_is_required()
    {
        $this->assertValidation(new Article(), 'trans.nl.slug', $this->validParams(['trans.nl.slug' => '']),
            route('back.articles.index'),
            route('back.articles.store')
        );
    }

    /** @test */
    public function when_creating_article_slug_will_be_stripped_of_html()
    {
        $response = $this->actingAs(factory(User::class)->create())
            ->post(route('back.articles.store'), $this->validParams([
                'trans.nl.slug' => '<b>slug</b>',
                'trans.fr.slug' => '<b>slugfr</b>',
                ]));

        $this->assertEquals('slug', Article::first()->{'slug:nl'});
        $this->assertEquals('slugfr', Article::first()->{'slug:fr'});
    }

    /** @test */
    function when_creating_article_title_is_required()
    {
        $this->assertValidation(new Article(), 'trans.nl.title', $this->validParams(['trans.nl.title' => '']),
            route('back.articles.index'),
            route('back.articles.store')
        );
    }

    /** @test */
    public function slug_must_be_unique()
    {
        factory(Article::class)->create(['slug:nl' => 'existing-slug']);

        $this->assertValidation(new Article(), 'trans.nl.slug', $this->validParams(['trans.nl.slug' => 'existing-slug']),
            route('back.articles.index'),
            route('back.articles.store'),
            1
        );
    }

    /** @test */
    public function it_can_remove_an_article()
    {
        $response = $this->actingAs(factory(User::class)->create())
            ->post(route('back.articles.store'), $this->validParams());

        $this->actingAs(factory(User::class)->create())
            ->delete(route('back.articles.destroy', Article::first()->id), $this->validParams());

        $this->assertCount(0, Article::all());
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


    private function assertNewValues($article)
    {
        $this->assertEquals('new-slug', $article->{'slug:nl'});
        $this->assertEquals('new title', $article->{'title:nl'});
        $this->assertEquals('new content in <strong>bold</strong>', $article->{'content:nl'});
        $this->assertEquals('new seo title', $article->{'seo_title:nl'});
        $this->assertEquals('new seo description', $article->{'seo_description:nl'});

        $this->assertEquals('nouveau-slug', $article->{'slug:fr'});
        $this->assertEquals('nouveau title', $article->{'title:fr'});
        $this->assertEquals('nouveau content in <strong>bold</strong>', $article->{'content:fr'});
        $this->assertEquals('nouveau seo title', $article->{'seo_title:fr'});
        $this->assertEquals('nouveau seo description', $article->{'seo_description:fr'});
    }
}