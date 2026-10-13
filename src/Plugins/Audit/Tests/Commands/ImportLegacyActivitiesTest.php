<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests\Commands;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditEvent;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class ImportLegacyActivitiesTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('activity_log', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('log_name')->nullable();
            $table->string('event')->nullable();
            $table->text('description');
            $table->nullableMorphs('subject');
            $table->nullableMorphs('causer');
            $table->json('properties')->nullable();
            $table->timestamps();
        });
    }

    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), AuditServiceProvider::class];
    }

    public function test_import_resumes_preserves_source_facts_and_requires_verified_cleanup(): void
    {
        $article = $this->setupAndCreateArticle();
        $this->activity(101, [
            'log_name' => 'chief', 'event' => 'updated', 'description' => 'Changed secret draft',
            'subject_type' => $article->getMorphClass(), 'subject_id' => $article->getKey(),
            'causer_type' => 'chiefuser', 'causer_id' => 987654,
            'properties' => json_encode(['private' => 'legacy secret', 'causer_snapshot' => ['fullname' => 'Old name']], JSON_THROW_ON_ERROR),
            'created_at' => '2020-01-02 03:04:05', 'updated_at' => '2020-01-03 04:05:06',
        ]);
        $this->activity(102, ['description' => 'No known subject', 'subject_type' => 'unknown.type', 'subject_id' => 42]);

        $this->assertSame(0, Artisan::call('chief-audit:import-spatie', ['--batch' => 1]));
        $this->assertDatabaseCount('chief_audit_events', 2);
        $this->assertSame(0, Artisan::call('chief-audit:import-spatie'));
        $this->assertDatabaseCount('chief_audit_events', 2);
        $this->assertDatabaseCount('activity_log', 2);

        $imported = AuditEvent::query()->where('summary', 'Changed secret draft')->firstOrFail();
        $this->assertSame('legacy.spatie', $imported->type);
        $this->assertSame('2020-01-02 03:04:05', $imported->occurred_at->format('Y-m-d H:i:s'));
        $this->assertSame(['name' => 'Old name', 'id' => '987654'], $imported->actor_snapshot);
        $this->assertSame('Old name', $imported->context['legacy']['properties']['causer_snapshot']['fullname']);
        $this->assertSame('chief', $imported->context['legacy']['log_name']);
        $this->assertSame('updated', $imported->context['legacy']['event']);
        $this->assertSame('2020-01-03 04:05:06', $imported->context['legacy']['updated_at']);
        $this->assertSame('legacy secret', $imported->context['legacy']['properties']['private']);
        $this->assertSame('unknown.type', AuditEvent::query()->where('summary', 'No known subject')->firstOrFail()->context['legacy']['subject_type']);
        $this->assertCount(1, $imported->models);

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $limited = $this->fakeUser();
        $limited->givePermissionTo('view-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));
        $this->actingAs($limited, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertSee('Historische activiteit')->assertDontSee('<span>legacy.spatie</span>', false)->assertSee('1 resultaten')
            ->assertDontSee('Changed secret draft')->assertDontSee('legacy secret')->assertDontSee('Old name')->assertDontSee('No known subject');
        $this->get(route('chief.audit.index', ['search' => 'Changed secret draft']))->assertOk()->assertSee('0 resultaten');

        $full = $this->fakeUser();
        $full->givePermissionTo('view-audit', 'view-full-audit');
        $this->actingAs($full, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertSee('Bijgewerkt')->assertSee('Changed secret draft')->assertDontSee('<span>legacy.spatie</span>', false)
            ->assertDontSee('legacy secret')->assertSee('No known subject');
        $this->get(route('chief.audit.event-details', $imported->getKey()))
            ->assertOk()->assertSee('Brongegevens')->assertDontSee('legacy.spatie');

        DB::table('chief_audit_events')->where('id', $imported->getKey())->delete();
        $this->assertSame(1, Artisan::call('chief-audit:import-spatie', ['--cleanup-registry' => true]));
        $this->assertTrue(Schema::hasTable('chief_audit_spatie_imports'));
        $this->assertSame(0, Artisan::call('chief-audit:import-spatie'));
        $this->assertDatabaseCount('chief_audit_events', 2);
        $this->assertSame(0, Artisan::call('chief-audit:import-spatie', ['--cleanup-registry' => true]));
        $this->assertFalse(Schema::hasTable('chief_audit_spatie_imports'));
        $this->assertDatabaseCount('activity_log', 2);
        $this->assertSame(1, Artisan::call('chief-audit:import-spatie'));
        $this->assertDatabaseCount('chief_audit_events', 2);
    }

    public function test_a_failed_row_can_be_corrected_and_retried_without_importing_prior_rows_twice(): void
    {
        $this->activity(201, ['description' => 'Already copied']);
        $this->activity(202, ['description' => 'Invalid timestamp', 'created_at' => null]);

        $this->assertSame(1, Artisan::call('chief-audit:import-spatie', ['--batch' => 1]));
        $this->assertDatabaseCount('chief_audit_events', 1);
        $this->assertSame(1, Artisan::call('chief-audit:import-spatie', ['--cleanup-registry' => true]));

        DB::table('activity_log')->where('id', 202)->update(['created_at' => '2020-01-02 00:00:00']);
        $this->assertSame(0, Artisan::call('chief-audit:import-spatie', ['--batch' => 1]));
        $this->assertDatabaseCount('chief_audit_events', 2);
        $this->assertSame(1, AuditEvent::query()->where('summary', 'Already copied')->count());
    }

    public function test_deleted_subject_and_missing_actor_do_not_gain_current_names_or_related_access(): void
    {
        $article = $this->setupAndCreateArticle();
        $this->activity(103, ['subject_type' => $article::class, 'subject_id' => $article->getKey(), 'causer_type' => 'chiefuser', 'causer_id' => 456, 'description' => 'Deleted model fact']);
        $article->delete();

        $this->assertSame(0, Artisan::call('chief-audit:import-spatie'));
        $event = AuditEvent::query()->firstOrFail();
        $this->assertSame(['name' => 'Onbekende admin', 'id' => '456'], $event->actor_snapshot);
        $this->assertSame('legacy.spatie', $event->type);
        $this->assertSame($article->getMorphClass(), $event->models->firstOrFail()->model_type);
        $this->assertSame('ArticlePage #'.$article->getKey(), $event->model_snapshot['name']);

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));
        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertDontSee('Deleted model fact')->assertSee('0 resultaten');
    }

    public function test_import_uses_source_actor_name_or_current_user_and_existing_resource_title(): void
    {
        $article = $this->setupAndCreateArticle(['title.nl' => 'Historische pagina']);
        $admin = $this->fakeUser();
        $this->activity(301, [
            'event' => 'published', 'description' => 'Page went live',
            'subject_type' => $article::class, 'subject_id' => $article->getKey(),
            'causer_type' => 'chiefuser', 'causer_id' => $admin->id,
            'properties' => json_encode(['causer_snapshot' => ['id' => $admin->id, 'fullname' => 'Naam destijds']], JSON_THROW_ON_ERROR),
        ]);
        $this->activity(302, [
            'event' => 'updated', 'description' => 'Another edit',
            'subject_type' => $article->getMorphClass(), 'subject_id' => $article->getKey(),
            'causer_type' => 'chiefuser', 'causer_id' => $admin->id,
        ]);

        $this->assertSame(0, Artisan::call('chief-audit:import-spatie'));

        $published = AuditEvent::query()->where('summary', 'Page went live')->firstOrFail();
        $this->assertSame(['name' => 'Naam destijds', 'id' => (string) $admin->id], $published->actor_snapshot);
        $this->assertSame($article->title, $published->model_snapshot['name']);
        $this->assertSame($article->title, $published->models->firstOrFail()->model_snapshot['name']);
        $this->assertSame(['name' => $admin->fullname, 'id' => (string) $admin->id], AuditEvent::query()->where('summary', 'Another edit')->firstOrFail()->actor_snapshot);

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', 'view-full-audit');
        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertSee('Gepubliceerd')->assertSee('Bijgewerkt')->assertSee('Naam destijds')
            ->assertSee($article->title)->assertDontSee('<span>legacy.spatie</span>', false)->assertDontSee('Legacy model');
    }

    public function test_legacy_actor_path_does_not_expose_a_deleted_subject_to_its_current_actor(): void
    {
        $article = $this->setupAndCreateArticle();
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));
        $this->activity(104, [
            'subject_type' => $article->getMorphClass(), 'subject_id' => $article->getKey(),
            'causer_type' => 'chiefuser', 'causer_id' => $viewer->id, 'description' => 'Own deleted legacy fact',
        ]);
        $article->delete();

        $this->assertSame(0, Artisan::call('chief-audit:import-spatie'));
        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertDontSee('Own deleted legacy fact')->assertDontSee('legacy.spatie')->assertSee('0 resultaten');
    }

    private function activity(int $id, array $overrides): void
    {
        DB::table('activity_log')->insert(array_merge([
            'id' => $id, 'log_name' => null, 'event' => null, 'description' => 'Legacy event',
            'subject_type' => null, 'subject_id' => null, 'causer_type' => null, 'causer_id' => null,
            'properties' => null, 'created_at' => '2020-01-01 00:00:00', 'updated_at' => '2020-01-01 00:00:00',
        ], $overrides));
    }
}
