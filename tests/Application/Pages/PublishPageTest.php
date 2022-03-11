<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Managers\Register\Register;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

final class PublishPageTest extends ChiefTestCase
{
    /** @var Manager */
    private $manager;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();

        app(Register::class)->model(ArticlePage::class, PageManager::class);
        $this->manager = app(Registry::class)->manager(ArticlePage::managedModelKey());
    }

    /** @test */
    public function an_admin_can_only_publish_a_page_with_the_proper_permissions()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::DRAFT,
        ]);

        $this->asAdminWithoutRole()->post($this->manager->route('publish', $model))
            ->assertStatus(302);

        $this->assertEquals(PageState::DRAFT, $model->fresh()->stateOf(PageState::KEY));
    }

    /** @test */
    public function an_admin_can_publish_a_page()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::DRAFT,
        ]);

        $this->asAdmin()->post($this->manager->route('publish', $model))
            ->assertStatus(302)
            ->assertRedirect($this->manager->route('index'));

        $this->assertEquals(PageState::PUBLISHED, $model->fresh()->stateOf(PageState::KEY));
    }

    /** @test */
    public function an_admin_can_unpublish_an_published_page()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::PUBLISHED,
        ]);

        $this->asAdmin()->post($this->manager->route('unpublish', $model))
            ->assertStatus(302)
            ->assertRedirect($this->manager->route('index'));

        $this->assertEquals(PageState::DRAFT, $model->fresh()->stateOf(PageState::KEY));
    }

    /** @test */
    public function it_cannot_publish_a_deleted_page()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::DELETED,
        ]);

        $this->asAdmin()->post($this->manager->route('publish', $model))
            ->assertStatus(302);

        $this->assertEquals(PageState::DELETED, $model->fresh()->stateOf(PageState::KEY));
    }
}
