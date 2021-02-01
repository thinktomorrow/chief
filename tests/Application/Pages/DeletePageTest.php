<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Managers\Register\Register;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\ManagedModels\States\PageState;

final class DeletePageTest extends ChiefTestCase
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
    public function a_deleted_model_cannot_be_edited()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::DELETED,
        ]);

        $this->asAdmin()->get($this->manager->route('edit', $model))
             ->assertStatus(302);
    }

    /** @test */
    public function an_admin_can_only_delete_a_page_with_the_proper_permissions()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::DRAFT,
        ]);

        $this->asAdminWithoutRole()->delete($this->manager->route('delete', $model))
            ->assertStatus(302);

        $this->assertEquals(PageState::DRAFT, $model->fresh()->stateOf(PageState::KEY));
        $this->assertFalse($model->fresh()->trashed());
    }

    /** @test */
    public function an_admin_can_delete_a_page()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::DRAFT,
        ]);

        $this->asAdmin()->delete($this->manager->route('delete', $model), [
            'deleteconfirmation' => 'DELETE',
        ])
            ->assertStatus(302)
            ->assertRedirect($this->manager->route('index'));

        $this->assertEquals(PageState::DELETED, $model->fresh()->stateOf(PageState::KEY));
        $this->assertTrue($model->fresh()->trashed());
    }

    /** @test */
    public function an_admin_cannot_delete_a_published_page()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
            'current_state' => PageState::PUBLISHED,
        ]);

        $this->asAdmin()->delete($this->manager->route('delete', $model))
            ->assertStatus(302);

        $this->assertEquals(PageState::PUBLISHED, $model->fresh()->stateOf(PageState::KEY));
        $this->assertFalse($model->fresh()->trashed());
    }

    /** @test */
    public function deleting_a_page_also_unlinks_any_assets()
    {
        $model = ArticlePage::create([
            'title' => 'first article',
        ]);

        app(AddAsset::class)->add($model, UploadedFile::fake()->image('image.png'), 'image', 'nl');

        $this->asAdmin()->delete($this->manager->route('delete', $model), [
            'deleteconfirmation' => 'DELETE',
        ]);

        $this->assertCount(0, $model->fresh()->assets());

        // Assert asset itself is still present
        $this->assertCount(1, Asset::all());
    }
}
