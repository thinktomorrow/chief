<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Managers\Register\Register;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class PublishPageTest extends ChiefTestCase
{
    /** @var Manager */
    private $manager;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();

        app(Register::class)->resource(ArticlePageResource::class, PageManager::class);
        $this->manager = $this->manager(ArticlePage::class);
    }

    /** @test */
    public function an_admin_can_only_publish_a_page_with_the_proper_permissions()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::draft,
        ]);

        $this->asAdminWithoutRole()->put($this->manager($model)->route('state-update', $model, PageState::KEY, 'publish'))
            ->assertStatus(302);

        $this->assertEquals(PageState::draft, $model->fresh()->getState(PageState::KEY));
    }

    /** @test */
    public function an_admin_can_publish_a_page()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::draft,
        ]);

        $this->asAdmin()->put($this->manager($model)->route('state-update', $model, PageState::KEY, 'publish'), [], [
            'Accept' => 'application/json',
        ])
            ->assertSuccessful();

        $this->assertEquals(PageState::published, $model->fresh()->getState(PageState::KEY));
    }

    /** @test */
    public function an_admin_can_unpublish_an_published_page()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::published,
        ]);

        $this->asAdmin()->put($this->manager($model)->route('state-update', $model, PageState::KEY, 'unpublish'), [], [
            'Accept' => 'application/json',
        ])
            ->assertSuccessful();

        $this->assertEquals(PageState::draft, $model->fresh()->getState(PageState::KEY));
    }

    /** @test */
    public function it_cannot_publish_a_deleted_page()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::deleted,
        ]);

        $this->asAdmin()->put($this->manager($model)->route('state-update', $model, PageState::KEY, 'publish'), [], [
            'Accept' => 'application/json',
        ])
            ->assertStatus(304);

        $this->assertEquals(PageState::deleted, $model->fresh()->getState(PageState::KEY));
    }
}
