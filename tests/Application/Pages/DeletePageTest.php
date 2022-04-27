<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Managers\Register\Register;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class DeletePageTest extends ChiefTestCase
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
    public function a_deleted_model_cannot_be_edited()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::deleted,
        ]);

        $this->asAdmin()->get($this->manager->route('edit', $model))
             ->assertStatus(302);
    }

    /** @test */
    public function an_admin_can_only_delete_a_page_with_the_proper_permissions()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::draft,
        ]);

        $this->asAdminWithoutRole()
            ->put($this->manager($model)->route('state-update', $model, PageState::KEY, 'delete'))
            ->assertStatus(302);

        $this->assertEquals(PageState::draft, $model->fresh()->getState(PageState::KEY));
        $this->assertFalse($model->fresh()->trashed());
    }

    /** @test */
    public function an_admin_can_delete_a_page()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::draft,
        ]);

        $this->asAdmin()->put($this->manager($model)->route('state-update', $model, PageState::KEY, 'delete'))
            ->assertStatus(302)
            ->assertRedirect($this->manager->route('index'));

        $this->assertEquals(PageState::deleted, $model->fresh()->getState(PageState::KEY));
        $this->assertTrue($model->fresh()->trashed());
    }

    /** @test */
    public function an_admin_cannot_delete_a_published_page()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::published,
        ]);

        $this->asAdmin()->put($this->manager($model)->route('state-update', $model, PageState::KEY, 'delete'))
            ->assertStatus(304);

        $this->assertEquals(PageState::published, $model->fresh()->getState(PageState::KEY));
        $this->assertFalse($model->fresh()->trashed());
    }

    /** @test */
    public function deleting_a_page_also_unlinks_any_assets()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
        ]);

        app(AddAsset::class)->add($model, UploadedFile::fake()->image('image.png'), 'image', 'nl');

        $this->asAdmin()->put($this->manager($model)->route('state-update', $model, PageState::KEY, 'delete'));

        $this->assertCount(0, $model->fresh()->assets());

        // Assert asset itself is still present
        $this->assertCount(1, Asset::all());
    }

    /** @test */
    public function deleting_a_page_also_deletes_the_context()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
        ]);

        $this->setupAndCreateQuote($model);
        $this->assertFragmentCount($model, 1);

        $this->asAdmin()->put($this->manager($model)->route('state-update', $model, PageState::KEY, 'delete'));

        $this->assertFragmentCount($model, 0);
    }
}
