<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests\App;

use Illuminate\Support\Facades\DB;
use RuntimeException;
use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Admin\Users\Application\DeleteUser;
use Thinktomorrow\Chief\Admin\Users\Application\DisableUser;
use Thinktomorrow\Chief\Admin\Users\Application\EnableUser;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\ManagedModels\Actions\DeleteModel;
use Thinktomorrow\Chief\ManagedModels\Actions\Duplicate\DuplicatePage;
use Thinktomorrow\Chief\ManagedModels\States\Actions\UpdateState;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Models\App\Actions\CreateModel;
use Thinktomorrow\Chief\Models\App\Actions\ModelApplication;
use Thinktomorrow\Chief\Models\App\Actions\UpdateModel;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class ChiefActionsTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class);
    }

    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), AuditServiceProvider::class];
    }

    public function test_state_transition_is_recorded_once_with_historical_actor_and_model(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin, 'chief');
        $page = ArticlePage::create(['current_state' => PageState::published->getValueAsString()]);

        app(UpdateState::class)->handle(ArticlePageResource::resourceKey(), $page->modelReference(), PageState::KEY, 'unpublish');

        $this->assertDatabaseCount('chief_audit_events', 1);
        $this->assertDatabaseHas('chief_audit_events', ['type' => 'chief.model.unpublished', 'actor_type' => 'admin', 'model_id' => (string) $page->id]);
        $this->assertSame($admin->fullname, json_decode(DB::table('chief_audit_events')->first()->actor_snapshot, true)['name']);

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $admin->givePermissionTo('view-full-audit');
        $originalName = $admin->fullname;
        $admin->update(['firstname' => 'Renamed']);
        $this->get(route('chief.audit.index'))->assertSuccessful()
            ->assertSee('chief.model.unpublished')->assertSee($originalName);
    }

    public function test_deleting_a_model_records_its_snapshot_once(): void
    {
        $page = ArticlePage::create();
        app(DeleteModel::class)->handle($page);

        $this->assertDatabaseCount('chief_audit_events', 1);
        $this->assertDatabaseHas('chief_audit_events', ['type' => 'chief.model.deleted', 'model_id' => (string) $page->id]);
        $this->assertDatabaseCount('chief_audit_event_models', 1);
    }

    public function test_state_delete_does_not_double_log_the_deletion(): void
    {
        $page = ArticlePage::create();

        app(UpdateState::class)->handle(ArticlePageResource::resourceKey(), $page->modelReference(), PageState::KEY, 'delete');

        $this->assertDatabaseCount('chief_audit_events', 1);
        $this->assertDatabaseHas('chief_audit_events', ['type' => 'chief.model.deleted', 'model_id' => (string) $page->id]);
    }

    public function test_duplicate_records_both_source_and_copy_once(): void
    {
        $page = ArticlePage::create();
        $copy = app(DuplicatePage::class)->handle($page, 'order');

        $this->assertDatabaseCount('chief_audit_events', 1);
        $this->assertDatabaseHas('chief_audit_events', ['type' => 'chief.model.duplicated', 'model_id' => (string) $copy->id]);
        $this->assertDatabaseCount('chief_audit_event_models', 2);
    }

    public function test_a_rolled_back_action_has_no_history(): void
    {
        $page = ArticlePage::create(['current_state' => PageState::published->getValueAsString()]);

        try {
            DB::transaction(function () use ($page): void {
                app(UpdateState::class)->handle(ArticlePageResource::resourceKey(), $page->modelReference(), PageState::KEY, 'unpublish');
                throw new RuntimeException('rollback');
            });
        } catch (RuntimeException $exception) {
            $this->assertSame('rollback', $exception->getMessage());
        }

        $this->assertDatabaseCount('chief_audit_events', 0);
        $this->assertSame(PageState::published, $page->fresh()->getState(PageState::KEY));
    }

    public function test_user_management_actions_each_record_one_event(): void
    {
        $user = $this->fakeUser();

        app(DisableUser::class)->handle($user);
        app(EnableUser::class)->handle($user);
        app(DeleteUser::class)->handle($user);

        $this->assertDatabaseCount('chief_audit_events', 3);
        foreach (['disabled', 'enabled', 'deleted'] as $action) {
            $this->assertDatabaseHas('chief_audit_events', ['type' => 'chief.user.'.$action, 'model_id' => (string) $user->id]);
        }
    }

    public function test_real_create_and_edit_each_record_one_historical_model_event_and_rollback_together(): void
    {
        ArticlePageResource::setFieldsDefinition(fn () => [Form::make('main')->items([Text::make('title_trans')->locales()->required()])]);
        $admin = $this->admin();
        $this->actingAs($admin, 'chief');
        $modelId = app(ModelApplication::class)->create(new CreateModel(ArticlePage::class, ['nl'], ['title_trans' => ['nl' => 'First']], []));

        $created = DB::table('chief_audit_events')->first();
        $this->assertDatabaseCount('chief_audit_events', 1);
        $this->assertSame('chief.model.created', $created->type);
        $this->assertSame((string) $modelId, $created->model_id);
        $this->assertSame($admin->fullname, json_decode($created->actor_snapshot, true)['name']);

        $admin->update(['firstname' => 'Renamed']);
        app(ModelApplication::class)->updateModel(new UpdateModel(ArticlePage::findOrFail($modelId)->modelReference(), ['nl'], ['title_trans' => ['nl' => 'Second']], []));
        $this->assertDatabaseCount('chief_audit_events', 2);
        $this->assertDatabaseHas('chief_audit_events', ['type' => 'chief.model.updated', 'model_id' => (string) $modelId]);
        $this->assertSame($admin->fullname, json_decode(DB::table('chief_audit_events')->where('type', 'chief.model.updated')->first()->actor_snapshot, true)['name']);
        $this->assertNotSame($admin->fullname, json_decode($created->actor_snapshot, true)['name']);

        try {
            DB::transaction(function () use ($modelId): void {
                app(ModelApplication::class)->updateModel(new UpdateModel(ArticlePage::findOrFail($modelId)->modelReference(), ['nl'], ['title_trans' => ['nl' => 'Rolled back']], []));
                throw new RuntimeException('rollback');
            });
        } catch (RuntimeException $exception) {
            $this->assertSame('rollback', $exception->getMessage());
        }

        $this->assertDatabaseCount('chief_audit_events', 2);
    }
}
