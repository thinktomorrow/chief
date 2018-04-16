<?php

namespace Chief\Tests\Feature\Translations;

use Chief\Translations\Translation;
use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;
use Chief\Users\User;
use Thinktomorrow\Squanto\Domain\Page;

class EditTranslationTest extends TestCase
{
    use ChiefDatabaseTransactions;

    private $squantoPage;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
        $this->squantoPage = $this->createSquantoPage();

    }

    /** @test */
    function admin_can_view_the_edit_form()
    {
        $this->disableExceptionHandling();

        $response = $this->actingAs(factory(User::class)->create())->get(route('squanto.edit', $this->squantoPage->id));
        $response->assertStatus(200);
    }

    /** @test */
    function guests_cannot_view_the_edit_form()
    {
        $response = $this->get(route('squanto.edit', $this->squantoPage->id));
        $response->assertStatus(302)->assertRedirect(route('back.login'));
    }

    /** @test */
    function creating_a_new_article()
    {
        $this->disableExceptionHandling();

        $response = $this->actingAs(factory(User::class)->create())
            ->post(route('back.translations.store'), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('back.translations.index'));

        $this->assertCount(1, Translation::all());
        $this->assertNewValues(Translation::first());
    }

    /** @test */
    function only_authenticated_admin_can_edit_a_article()
    {
        $response = $this->post(route('back.translations.store'), $this->validParams());

        $response->assertRedirect(route('back.login'));
        $this->assertCount(0, Translation::all());
    }

    /** @test */
    function when_creating_article_slug_is_required()
    {
        $this->assertValidation(new Translation(), 'trans.nl.slug', $this->validParams(['trans.nl.slug' => '']),
            route('back.translations.index'),
            route('back.translations.store')
        );
    }

    /** @test */
    public function slug_must_be_unique()
    {
        factory(Translation::class)->create(['slug:nl' => 'existing-slug']);

        $this->assertValidation(new Translation(), 'trans.nl.slug', $this->validParams(['trans.nl.slug' => 'existing-slug']),
            route('back.translations.index'),
            route('back.translations.store'),
            1
        );
    }

    private function validParams($overrides = [])
    {
        $params = [
            'trans' => [
                'nl' => [
                    'slug'    => 'new-slug',
                    'title'           => 'new title',
                    'content'         => 'new content in <strong>bold</strong>',
                    'seo_title'       => 'new seo title',
                    'seo_description' => 'new seo description',
                ],
                'fr' => [
                    'slug'  => 'nouveau-slug',
                    'title'           => 'nouveau title',
                    'content'         => 'nouveau content in <strong>bold</strong>',
                    'seo_title'       => 'nouveau seo title',
                    'seo_description' => 'nouveau seo description',
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

    /**
     * @return Page
     */
    private function createSquantoPage(): Page
    {
        $squantoPage = new Page();
        $squantoPage->label = 'squanto page';
        $squantoPage->key = 'squanto-page';
        $squantoPage->save();

        return $squantoPage;
    }
}