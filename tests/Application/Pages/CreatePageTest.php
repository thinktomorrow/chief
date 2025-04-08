<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

final class CreatePageTest extends ChiefTestCase
{
    /** @var Manager */
    private $manager;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class, PageManager::class);

        $this->manager = $this->manager(ArticlePage::class);
    }

    public function test_it_can_visit_the_create_page()
    {
        $this->disableExceptionHandling();
        $this->asAdmin()->get($this->manager->route('create'))
            ->assertStatus(200);
    }

    public function test_guests_cannot_view_the_create_form()
    {
        $this->get($this->manager->route('create'))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }

    public function test_it_can_create_a_page()
    {
        $this->asAdmin()->post($this->manager->route('store'), [
            'title' => 'new title',
            'custom' => 'custom value',
            'trans' => [
                'nl' => [
                    'content_trans' => 'nl content',
                ],
            ],
        ]);

        $this->assertEquals(1, ArticlePage::count());

        $article = ArticlePage::first();
        $this->assertEquals('new title', $article->title);
        $this->assertEquals('custom value', $article->custom);
        $this->assertEquals('nl content', $article->content_trans);
    }

    public function test_it_emits_an_model_created_event()
    {
        Event::fake();

        $this->asAdmin()->post($this->manager->route('store'), [
            'title' => 'new title',
            'custom' => 'custom value',
            'trans' => [
                'nl' => [
                    'content_trans' => 'nl content',
                ],
            ],
        ]);

        Event::assertDispatched(ManagedModelCreated::class);
    }

    public function test_when_creating_a_page_an_url_is_automatically_set_based_on_the_title()
    {
        $this->asAdmin()->post($this->manager->route('store'), [
            'title' => 'new title',
            'custom' => 'custom value',
            'trans' => [
                'nl' => [
                    'content_trans' => 'nl content',
                ],
            ],
        ]);

        $article = ArticlePage::first();

        $this->assertEquals(2, UrlRecord::count());
        $this->assertEquals('custom-value', UrlRecord::findByModel($article, 'nl')->slug);
        $this->assertEquals('custom-value', UrlRecord::findByModel($article, 'en')->slug);
    }
}
