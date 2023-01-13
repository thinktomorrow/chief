<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Managers\Register\Register;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class ArchivePageTest extends ChiefTestCase
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
    public function admin_can_view_the_archive_index()
    {
        ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::archived,
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
            'current_state' => PageState::published,
        ]);

        $this->asAdminWithoutRole()->put($this->manager($model)->route('state-update', $model, PageState::KEY, 'archive'))
            ->assertStatus(302);

        $this->assertEquals(PageState::published, $model->fresh()->getState(PageState::KEY));
    }

    /** @test */
    public function an_admin_can_archive_a_page()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::published,
        ]);

        $this->asAdmin()
            ->put($this->manager($model)->route('state-update', $model, PageState::KEY, 'archive'))
            ->assertStatus(302)
            ->assertRedirect($this->manager($model)->route('index'));

        $this->assertEquals(PageState::archived, $model->fresh()->getState(PageState::KEY));
    }

    /** @test */
    public function an_admin_can_unarchive_an_archived_page()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::archived,
        ]);

        $this->asAdmin()
            ->put($this->manager($model)->route('state-update', $model, PageState::KEY, 'unarchive'))
            ->assertStatus(302)
            ->assertRedirect($this->manager->route('index'));

        $this->assertEquals(PageState::draft, $model->fresh()->getState(PageState::KEY));
    }

    /** @test */
    public function it_cannot_archive_a_deleted_page()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::deleted,
        ]);

        $this->asAdmin()
            ->put($this->manager($model)->route('state-update', $model, PageState::KEY, 'archive'))
            ->assertStatus(304);

        $this->assertEquals(PageState::deleted, $model->fresh()->getState(PageState::KEY));
    }

    /** @test */
    public function when_archiving_a_redirect_page_can_be_set()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::published,
        ]);

        $this->updateLinks($model, ['nl' => 'first-nl', 'en' => 'first-en']);

        $redirectModel = ArticlePage::create([
            'title' => 'second article',
            'current_state' => PageState::published,
        ]);

        $this->updateLinks($redirectModel, ['nl' => 'second-nl', 'en' => 'second-en']);

        $this->asAdmin()
            ->put($this->manager($model)->route('state-update', $model, PageState::KEY, 'archive'), [
            'redirect_id' => $redirectModel->modelReference()->getShort(),
        ])->assertStatus(302);

        $this->assertEquals(PageState::archived, $model->fresh()->getState(PageState::KEY));

        $this->assertCount(0, UrlRecord::getByModel($model));
        $this->assertCount(4, UrlRecord::getByModel($redirectModel));

        $this->assertEquals('first-nl', UrlRecord::findRecentRedirect($redirectModel, 'nl')->slug);
        $this->assertEquals('first-en', UrlRecord::findRecentRedirect($redirectModel, 'en')->slug);
    }

    /** @test */
    public function the_archive_index_can_be_visited_when_there_is_an_archived_model()
    {
        $model = ArticlePage::create([PageState::KEY => PageState::archived]);

        auth('chief')->login($this->admin());

        $this->assertTrue($this->manager($model)->can('archive_index'));
    }

    /** @test */
    public function the_archive_index_cannot_be_visited_when_there_are_no_archived_models()
    {
        $model = ArticlePage::create();

        auth('chief')->login($this->admin());

        $this->assertFalse($this->manager($model)->can('archive_index'));
    }

    /** @test */
    public function the_archive_modal_content_can_be_fetched()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::published,
        ]);

        $this->asAdmin()->get($this->manager->route('archive_modal', $model))
            ->assertStatus(200);
    }
}
