<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUpdated;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Presets\FragmentManager;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

final class EditPageTest extends ChiefTestCase
{
    /** @var Manager */
    private $manager;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->model(ArticlePage::class, PageManager::class);

        Quote::migrateUp();
        chiefRegister()->model(Quote::class, FragmentManager::class);
        chiefRegister()->model(SnippetStub::class, FragmentManager::class);

        $this->manager = $this->manager(ArticlePage::managedModelKey());

        $this->asAdmin()->post($this->manager->route('store'), [
            'title' => 'new title',
            'custom' => 'custom value',
            'trans' => [
                'nl' => [
                    'content_trans' => 'nl content',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_can_visit_the_edit_page()
    {
        $this->asAdmin()->get($this->manager->route('edit', ArticlePage::first()))
                        ->assertStatus(200);
    }

    /** @test */
    public function it_can_update_a_page()
    {
        $this->asAdmin()->put($this->manager->route('update', ArticlePage::first()), [
            'title' => 'updated title',
            'custom' => 'updated custom value',
            'trans' => [
                'nl' => [
                    'content_trans' => 'updated nl content',
                ],
            ],
        ]);

        $this->assertEquals(1, ArticlePage::count());

        $article = ArticlePage::first();
        $this->assertEquals('updated title', $article->title);
        $this->assertEquals('updated custom value', $article->custom);
        $this->assertEquals('updated nl content', $article->content_trans);
    }

    /** @test */
    public function it_emits_an_model_updated_event()
    {
        Event::fake();

        $this->asAdmin()->put($this->manager->route('update', ArticlePage::first()), [
            'title' => 'updated title',
            'custom' => 'updated custom value',
            'trans' => [
                'nl' => [
                    'content_trans' => 'updated nl content',
                ],
            ],
        ]);

        Event::assertDispatched(ManagedModelUpdated::class);
    }
}
