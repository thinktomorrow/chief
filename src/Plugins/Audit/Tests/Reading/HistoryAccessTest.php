<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests\Reading;

use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditEvent;
use Thinktomorrow\Chief\Plugins\Audit\Recording\AuditModelDTO;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class HistoryAccessTest extends ChiefTestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), AuditServiceProvider::class];
    }

    public function test_full_right_with_basic_access_shows_model_free_and_deleted_model_history(): void
    {
        $article = $this->setupAndCreateArticle();
        History::log(type: 'project.export', actorType: 'system', actorSnapshot: ['name' => 'System'], summary: 'Free event');
        $this->logArticle($article, 'Deleted history');
        $article->delete();

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', 'view-full-audit');

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertSuccessful()->assertSee('Free event')->assertSee('Deleted history')
            ->assertSee('href="'.route('chief.audit.index').'"', false);
    }

    public function test_related_right_requires_a_current_resource_and_view_permission(): void
    {
        $article = $this->setupAndCreateArticle();
        $this->logArticle($article, 'Visible history');
        History::log(type: 'project.export', actorType: 'system', actorSnapshot: ['name' => 'System'], summary: 'Free event');
        History::log(type: 'project.unknown', actorType: 'system', actorSnapshot: ['name' => 'System'], summary: 'Unknown resource', models: [new AuditModelDTO('project.unknown', '12', ['name' => 'Unknown model'])]);

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit');

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertSuccessful()->assertSee('Geen historiek.')
            ->assertDontSee('Visible history')->assertDontSee('Free event')->assertDontSee('Unknown resource');

        $viewer->givePermissionTo(ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));
        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertSuccessful()->assertSee('Visible history')->assertSee('Article snapshot')
            ->assertDontSee('Free event')->assertDontSee('Unknown resource');

        $article->delete();
        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertSuccessful()->assertDontSee('Visible history')->assertDontSee('Article snapshot');
    }

    public function test_own_model_linked_event_remains_recognizable_without_hidden_model_details(): void
    {
        $article = $this->setupAndCreateArticle();
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit');

        History::log(type: 'project.own', actorType: 'admin', actorSnapshot: ['id' => (string) $viewer->id, 'name' => 'Actor'], summary: 'Own action', models: [new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Hidden article'])]);
        History::log(type: 'project.other', actorType: 'admin', actorSnapshot: ['id' => '999999', 'name' => 'Other'], summary: 'Other action', models: [new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Other hidden article'])]);
        History::log(type: 'project.own-free', actorType: 'admin', actorSnapshot: ['id' => (string) $viewer->id, 'name' => 'Actor'], summary: 'Own free');

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertSuccessful()->assertSee('project.own')->assertDontSee('Own action')->assertDontSee('Hidden article')
            ->assertDontSee('Other action')->assertDontSee('Own free');
    }

    public function test_a_visible_related_model_does_not_expose_an_inaccessible_primary_model(): void
    {
        $article = $this->setupAndCreateArticle();
        History::log(type: 'project.bulk', actorType: 'system', actorSnapshot: ['name' => 'System'], summary: 'Neutral bulk action', models: [
            new AuditModelDTO('project.unknown', '12', ['name' => 'Hidden primary']),
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Visible related']),
        ]);

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertSuccessful()->assertDontSee('Neutral bulk action')->assertSee('Visible related')
            ->assertDontSee('Hidden primary');
    }

    public function test_inaccessible_events_do_not_consume_the_related_timeline_page(): void
    {
        $article = $this->setupAndCreateArticle();
        $this->logArticle($article, 'Visible older event');

        for ($i = 0; $i < 51; $i++) {
            History::log(type: 'project.export', actorType: 'system', actorSnapshot: ['name' => 'System'], summary: 'Hidden export');
        }

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertSuccessful()->assertSee('Visible older event')->assertDontSee('Hidden export');
    }

    public function test_related_details_only_show_changes_for_currently_accessible_links(): void
    {
        $article = $this->setupAndCreateArticle();
        $article->order = 27;
        $event = History::log(type: 'project.bulk', actorType: 'system', actorSnapshot: ['name' => 'System'], summary: 'Bulk action', models: [
            (new AuditModelDTO('project.unknown', '12', ['name' => 'Hidden model']))->withChangesFrom($article, ['order']),
            (new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Visible model']))->withChangesFrom($article, ['order']),
        ]);

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));
        $links = AuditEvent::query()->findOrFail($event->getKey())->models()->orderBy('id')->get();
        $hiddenUrl = route('chief.audit.details', [$event->getKey(), $links[0]->getKey()]);
        $visibleUrl = route('chief.audit.details', [$event->getKey(), $links[1]->getKey()]);

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertSee($visibleUrl)->assertDontSee($hiddenUrl)->assertDontSee('Hidden model');
        $this->get($hiddenUrl)->assertNotFound();
        $this->get($visibleUrl)->assertOk()->assertSee('Visible model')->assertSee('27');

        $article->delete();
        $this->get($visibleUrl)->assertNotFound();
    }

    public function test_partial_bulk_projection_filters_search_counts_and_pages_without_exposing_shared_text(): void
    {
        $article = $this->setupAndCreateArticle();
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));

        History::log(type: 'project.bulk', actorType: 'system', actorSnapshot: ['name' => 'System'], summary: 'Secret order and two records', context: ['secret' => 'Secret context'], models: [
            new AuditModelDTO('project.hidden', '1', ['name' => 'Secret order'], ['secret' => 'Hidden context']),
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Visible article'], ['note' => 'Visible context']),
        ]);
        History::log(type: 'project.visible', actorType: 'system', actorSnapshot: ['name' => 'System'], summary: 'Visible later', models: [
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Second article']),
        ]);

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index', ['search' => 'Secret order']))
            ->assertOk()->assertDontSee('<span>project.bulk</span>', false)->assertSee('0 resultaten');
        $this->get(route('chief.audit.index', ['model_type' => 'project.hidden']))
            ->assertOk()->assertDontSee('<span>project.bulk</span>', false)->assertDontSee('<span>project.visible</span>', false);
        $this->get(route('chief.audit.index', ['per_page' => 1]))
            ->assertOk()->assertSee('Visible later')->assertDontSee('Secret order')->assertDontSee('Secret context')
            ->assertDontSee('<span>project.bulk</span>', false)->assertSee('2 resultaten');
        $this->get(route('chief.audit.index', ['per_page' => 1, 'page' => 2]))
            ->assertOk()->assertSee('project.bulk')->assertSee('Visible article')
            ->assertDontSee('Secret order')->assertDontSee('Secret context')->assertDontSee('Hidden context')
            ->assertDontSee('two records')->assertSee('2 resultaten');
        $this->get(route('chief.audit.index', ['search' => 'Visible article']))
            ->assertOk()->assertSee('project.bulk')->assertSee('1 resultaten');
        $this->get(route('chief.audit.index', ['model_type' => $article->getMorphClass()]))
            ->assertOk()->assertSee('project.bulk')->assertSee('2 resultaten')
            ->assertDontSee('project.hidden');

        $fullViewer = $this->fakeUser();
        $fullViewer->givePermissionTo('view-audit', 'view-full-audit');
        $this->actingAs($fullViewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertSee('Secret order and two records')->assertSee('Secret order')
            ->assertSee('Visible article');
        $this->assertDatabaseHas('chief_audit_events', ['type' => 'project.bulk', 'summary' => 'Secret order and two records']);
    }

    public function test_actor_only_path_never_exposes_model_bearing_actor_or_shared_context(): void
    {
        $article = $this->setupAndCreateArticle();
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit');
        History::log(type: 'project.actor', actorType: 'admin', actorSnapshot: ['id' => (string) $viewer->id, 'name' => 'Secret model actor'], summary: 'Secret summary', context: ['name' => 'Secret context'], models: [
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Secret model']),
        ]);

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertSee('project.actor')->assertDontSee('Secret model actor')
            ->assertDontSee('Secret summary')->assertDontSee('Secret model');
        $this->get(route('chief.audit.index', ['search' => 'Secret model']))
            ->assertOk()->assertSee('0 resultaten')->assertDontSee('<span>project.actor</span>', false);
    }

    public function test_full_right_without_basic_access_grants_neither_page_nor_navigation(): void
    {
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-full-audit');

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))->assertForbidden();
        $this->actingAs($viewer, 'chief')->get(route('chief.back.dashboard'))
            ->assertSuccessful()->assertDontSee('href="'.route('chief.audit.index').'"', false);
    }

    public function test_basic_access_shows_navigation_and_only_permitted_history(): void
    {
        $article = $this->setupAndCreateArticle();
        $this->logArticle($article, 'Visible history');
        History::log(type: 'project.export', actorType: 'system', actorSnapshot: ['name' => 'System'], summary: 'Private export');

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertSee('Visible history')->assertDontSee('Private export');
        $this->get(route('chief.back.dashboard'))
            ->assertOk()->assertSee('href="'.route('chief.audit.index').'"', false);
    }

    private function logArticle(ArticlePage $article, string $summary): void
    {
        History::log(type: 'project.article', actorType: 'system', actorSnapshot: ['name' => 'System'], summary: $summary, models: [new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Article snapshot'])]);
    }
}
