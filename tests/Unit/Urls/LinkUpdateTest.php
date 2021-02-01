<?php

namespace Thinktomorrow\Chief\Tests\Unit\Urls;

use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageWithBaseSegments;
use Thinktomorrow\Chief\Managers\Register\Register;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;

class LinkUpdateTest extends ChiefTestCase
{
    /** @var Manager */
    private $manager;

    use PageFormParams;

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpChiefEnvironment();

        ArticlePage::migrateUp();

        app(Register::class)->model(ArticlePage::class, PageManager::class);
        $this->manager = app(Registry::class)->manager(ArticlePage::managedModelKey());
    }

    /** @test */
    function it_automatically_adds_an_url_on_creation()
    {
        $model = ArticlePage::create();

        $this->asAdmin()->put(route('chief.back.links.update'), [
            'modelClass' => get_class($model),
            'modelId' => $model->id,
            'links' => [
                'nl' => 'foobar',
            ],
        ]);

        $this->assertEquals($model->getMorphClass(), UrlRecord::findBySlug('foobar', 'nl')->model_type);
        $this->assertEquals($model->id, UrlRecord::findBySlug('foobar', 'nl')->model_id);
    }

    /** @test */
    function it_adds_a_slug_for_each_locale()
    {
        $model = ArticlePage::create();

        $this->updateLinks($model, [
            'nl' => 'foobar-nl',
            'fr' => 'foobar-fr',
        ]);

        $this->assertEquals('foobar-nl', UrlRecord::findBySlug('foobar-nl', 'nl')->slug);
        $this->assertEquals('foobar-fr', UrlRecord::findBySlug('foobar-fr', 'fr')->slug);
    }

    /** @test */
    function it_cannot_add_same_url_for_specific_locale_twice()
    {
        $model = ArticlePage::create();

        $this->updateLinks($model, ['nl' => 'foobar']);

        $this->assertTrue(UrlRecord::exists('foobar', 'nl'));

        $model2 = ArticlePage::create();
        $response = $this->updateLinks($model2, ['nl' => 'foobar']);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('links');
        $this->assertCount(1, UrlRecord::all());
    }

    /** @test */
    function it_can_use_same_url_for_same_model()
    {
        $model = ArticlePage::create();

        $this->updateLinks($model, ['nl' => 'foobar']);
        $response = $this->updateLinks($model, ['nl' => 'foobar']);

        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();

        $this->assertEquals(1, UrlRecord::count());
        // Assert url is updated
        $this->assertEquals('foobar', UrlRecord::findByModel($model, 'nl')->slug);
        $this->assertFalse(UrlRecord::findByModel($model, 'nl')->isRedirect());
    }

    /** @test */
    function when_updating_an_url_it_keeps_the_old_url_as_redirect()
    {
        $model = ArticlePage::create();

        $this->updateLinks($model, ['nl' => 'foobar']);
        $this->updateLinks($model, ['nl' => 'foobar-updated']);

        $this->assertEquals(2, UrlRecord::count());
        $this->assertEquals('foobar-updated', UrlRecord::findByModel($model, 'nl')->slug);
        $this->assertFalse(UrlRecord::findByModel($model, 'nl')->isRedirect());

        // Assert old one is now set as redirect
        $this->assertTrue(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->get(route('pages.show','foobar'))->assertRedirect('foobar-updated');
    }

    /** @test */
    function updating_to_same_url_as_a_redirect_one_of_same_model_will_remove_redirect()
    {
        $model = ArticlePage::create();

        $this->updateLinks($model, ['nl' => 'foobar']);
        $this->updateLinks($model, ['nl' => 'foobar-updated']);

        $this->assertTrue(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->assertFalse(UrlRecord::findBySlug('foobar-updated', 'nl')->isRedirect());

        // Update back to original value
        $response = $this->updateLinks($model, ['nl' => 'foobar']);

        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();

        $this->assertCount(2,UrlRecord::all());
        $this->assertFalse(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->assertTrue(UrlRecord::findBySlug('foobar-updated', 'nl')->isRedirect());
    }

    /** @test */
    function updating_to_an_already_existing_url_will_fail()
    {
        $model = ArticlePage::create();
        $this->updateLinks($model, ['nl' => 'foobar']);
        $this->updateLinks($model, ['nl' => 'foobar-updated']);

        $this->assertTrue(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->assertFalse(UrlRecord::findBySlug('foobar-updated', 'nl')->isRedirect());

        $model2 = ArticlePage::create();
        $response = $this->updateLinks($model2, ['nl' => 'foobar-updated']);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('links');

        $this->assertCount(2,UrlRecord::all());

        // verify url points to model
        $this->assertEquals($model->id, UrlRecord::findBySlug('foobar', 'nl')->model_id);
        $this->assertTrue(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
    }

    /** @test */
    function updating_to_same_url_as_a_redirect_of_different_model_will_remove_redirect()
    {
        $model = ArticlePage::create();
        $this->updateLinks($model, ['nl' => 'foobar']);
        $this->updateLinks($model, ['nl' => 'foobar-updated']);

        $this->assertTrue(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->assertFalse(UrlRecord::findBySlug('foobar-updated', 'nl')->isRedirect());

        $model = ArticlePage::create();
        $response = $this->updateLinks($model, ['nl' => 'foobar']);

        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();

        $this->assertCount(2,UrlRecord::all());

        // verify url points to new model
        $this->assertFalse(UrlRecord::findBySlug('foobar', 'nl')->isRedirect());
        $this->assertFalse(UrlRecord::findBySlug('foobar-updated', 'nl')->isRedirect());
    }

    /** @test */
    function updating_to_empty_url_will_remove_record_and_all_redirects()
    {
        $model = ArticlePage::create();
        $this->updateLinks($model, ['nl' => 'foobar']);
        $this->updateLinks($model, ['nl' => '']);

        $this->assertCount(0,UrlRecord::all());
    }

    /** @test */
    function updating_to_same_url_will_keep_records_as_they_were()
    {
        $model = ArticlePage::create();
        $this->updateLinks($model, ['nl' => 'foobar']);
        $this->updateLinks($model, ['nl' => 'foobar-updated']);

        $record = UrlRecord::findBySlug('foobar-updated', 'nl');

        $this->updateLinks($model, ['nl' => 'foobar-updated']);

        $this->assertCount(2,UrlRecord::all());

        // assert the record in db is still the same row
        $this->assertEquals($record->id, UrlRecord::findBySlug('foobar-updated', 'nl')->id);
    }

    /** @test */
    function it_can_update_to_root_slug()
    {
        $model = ArticlePage::create();
        $this->updateLinks($model, ['nl' => 'foobar']);
        $this->updateLinks($model, ['nl' => '/']);

        $this->assertCount(2,UrlRecord::all());
        $this->assertNotNull(UrlRecord::findBySlug('/', 'nl'));
    }

    /** @test */
    function it_can_store_the_archived_url_as_redirect()
    {
        $model = ArticlePage::create();
        $this->updateLinks($model, ['nl' => 'foobar']);
        $this->updateLinks($model, ['nl' => 'foobar-updated']);

        $model2 = ArticlePage::create();
        $this->updateLinks($model2, ['nl' => 'foobar-2']);
        $this->updateLinks($model2, ['nl' => 'foobar-updated-2']);

        $response = $this->asAdmin()
            ->post($this->manager->route('archive', $model), [
                'redirect_id' => $model2->modelReference()->get(),
            ]);

        $this->assertEquals($model2->id, UrlRecord::findBySlug('foobar-updated', 'nl')->redirectTo()->model_id);
        $this->assertEquals($model2->id, UrlRecord::findBySlug('foobar-updated-2', 'nl')->model_id);
    }

    /** @test */
    function the_updated_slug_is_prepended_with_the_basesegment()
    {
        app(Register::class)->model(ArticlePageWithBaseSegments::class, PageManager::class);
        $model = ArticlePageWithBaseSegments::create();

        $this->updateLinks($model, ['nl' => 'foobar']);

        UrlRecord::findBySlug('artikels/foobar', 'nl');
    }

    /** @test */
    function baseurlsegment_is_taken_into_account_for_uniqueness_check()
    {
        // Todo: if we register after the first updateLink requests, these routes do not seem to be found... something cached??
        app(Register::class)->model(ArticlePageWithBaseSegments::class, PageManager::class);

        $model = ArticlePage::create();
        $model2 = ArticlePageWithBaseSegments::create();

        $this->updateLinks($model, ['nl' => 'foobar']);
        $this->updateLinks($model, ['nl' => 'foobar-updated']);

        $response = $this->updateLinks($model2, ['nl' => 'foobar-updated']);

        $response->assertStatus(200);
        $response->assertSessionHasNoErrors('links');
        $this->assertStringEndsWith('/artikels/foobar-updated', $model2->url());
    }

}
