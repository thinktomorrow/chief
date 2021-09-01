<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

final class CreatePageTest extends ChiefTestCase
{
    /** @var Manager */
    private $manager;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->model(ArticlePage::class, PageManager::class);

        $this->manager = $this->manager(ArticlePage::managedModelKey());
    }

    /** @test */
    public function it_can_visit_the_create_page()
    {
        $this->asAdmin()->get($this->manager->route('create'))
             ->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_page()
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

    /** @test */
    public function it_emits_an_modelCreated_event()
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

    /** @test */
    public function when_creating_a_page_an_url_is_automatically_set_based_on_the_title()
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
        $this->assertEquals('new-title', UrlRecord::findByModel($article, 'nl')->slug);
        $this->assertEquals('new-title', UrlRecord::findByModel($article, 'en')->slug);
    }
}
