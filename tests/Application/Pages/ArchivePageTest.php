<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Managers\Register\Register;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

final class ArchivePageTest extends ChiefTestCase
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
    public function admin_can_view_the_archive_index()
    {
        ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::ARCHIVED,
        ]);

        $response = $this->asAdmin()->get($this->manager->route('archive_index'));
        $response->assertStatus(200)
                 ->assertViewCount('models', 1);
    }

    /** @test */
    public function an_admin_can_only_archive_a_page_with_the_proper_permissions()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::PUBLISHED,
        ]);

        $this->asAdminWithoutRole()->post($this->manager->route('archive', $model))
            ->assertStatus(302);

        $this->assertEquals(PageState::PUBLISHED, $model->fresh()->stateOf(PageState::KEY));
    }

    /** @test */
    public function an_admin_can_archive_a_page()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::PUBLISHED,
        ]);

        $this->asAdmin()->post($this->manager->route('archive', $model))
            ->assertStatus(302)
            ->assertRedirect($this->manager->route('index'));

        $this->assertEquals(PageState::ARCHIVED, $model->fresh()->stateOf(PageState::KEY));
    }

    /** @test */
    public function an_admin_can_unarchive_an_archived_page()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::ARCHIVED,
        ]);

        $this->asAdmin()->post($this->manager->route('unarchive', $model))
            ->assertStatus(302)
            ->assertRedirect($this->manager->route('index'));

        $this->assertEquals(PageState::DRAFT, $model->fresh()->stateOf(PageState::KEY));
    }

    /** @test */
    public function it_cannot_archive_a_deleted_page()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::DELETED,
        ]);

        $this->asAdmin()->post($this->manager->route('archive', $model))
            ->assertStatus(302);

        $this->assertEquals(PageState::DELETED, $model->fresh()->stateOf(PageState::KEY));
    }

    /** @test */
    public function when_archiving_a_redirect_page_can_be_set()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::PUBLISHED,
        ]);

        $this->updateLinks($model, ['nl' => 'first-nl', 'en' => 'first-en']);

        $redirectModel = ArticlePage::create([
            'title' => 'second article',
            'current_state' => PageState::PUBLISHED,
        ]);

        $this->updateLinks($redirectModel, ['nl' => 'second-nl', 'en' => 'second-en']);

        $this->asAdmin()->post($this->manager->route('archive', $model), [
            'redirect_id' => $redirectModel->modelReference()->get(),
        ])->assertStatus(302);

        $this->assertEquals(PageState::ARCHIVED, $model->fresh()->stateOf(PageState::KEY));

        $this->assertCount(0, UrlRecord::getByModel($model));
        $this->assertCount(4, UrlRecord::getByModel($redirectModel));

        $this->assertEquals('first-nl', UrlRecord::findRecentRedirect($redirectModel, 'nl')->slug);
        $this->assertEquals('first-en', UrlRecord::findRecentRedirect($redirectModel, 'en')->slug);
    }
}
