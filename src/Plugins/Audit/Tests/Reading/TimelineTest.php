<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests\Reading;

use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Plugins\Audit\Recording\AuditModelDTO;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class TimelineTest extends ChiefTestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), AuditServiceProvider::class];
    }

    public function test_day_boundaries_and_period_filters_use_the_admin_timezone(): void
    {
        $viewer = $this->fakeUser();
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer->givePermissionTo('view-audit', 'view-full-audit');
        config()->set('chief.audit.timezone', 'Europe/Brussels');

        $this->log('Earlier timezone entry', '2026-01-01T22:30:00Z');
        $this->log('Later timezone entry', '2026-01-01T23:30:00Z');

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertSeeInOrder(['02/01/2026', 'Later timezone entry', '01/01/2026', 'Earlier timezone entry']);
        $this->get(route('chief.audit.index', ['from' => '2026-01-02', 'to' => '2026-01-02']))
            ->assertOk()->assertSee('Later timezone entry')->assertDontSee('Earlier timezone entry')->assertSee('1 resultaten');
    }

    public function test_secondary_events_follow_primary_page_window_and_filtered_results_include_all_priorities(): void
    {
        $viewer = $this->fakeUser();
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer->givePermissionTo('view-audit', 'view-full-audit');
        config()->set('chief.audit.types.project.secondary', ['priority' => 'secondary', 'label' => 'Bijzaak', 'icon' => 'clock', 'color' => 'blue']);

        $this->log('Old primary entry', '2026-01-01T10:00:00Z');
        $this->log('Middle secondary entry', '2026-01-01T11:00:00Z', 'project.secondary');
        $this->log('New primary entry', '2026-01-01T12:00:00Z');
        $this->log('Latest secondary entry', '2026-01-01T13:00:00Z', 'project.secondary');

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index', ['per_page' => 1]))
            ->assertOk()->assertSee('New primary entry')->assertDontSee('Middle secondary entry')->assertDontSee('Latest secondary entry')->assertSee('2 resultaten');
        $this->get(route('chief.audit.index', ['per_page' => 2, 'show_all' => 1]))
            ->assertOk()->assertSeeInOrder(['New primary entry', 'Middle secondary entry', 'Old primary entry'])->assertDontSee('Latest secondary entry');
        $this->get(route('chief.audit.index', ['search' => 'Latest secondary entry', 'per_page' => 1]))
            ->assertOk()->assertSee('Latest secondary entry')->assertSee('1 resultaten')->assertSee('Bijzaak');
        $this->get(route('chief.audit.index', ['category' => 'general', 'per_page' => 1]))
            ->assertOk()->assertSee('Latest secondary entry')->assertSee('4 resultaten');
    }

    public function test_show_all_can_page_a_timeline_with_only_secondary_events(): void
    {
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', 'view-full-audit');
        config()->set('chief.audit.types.project.secondary', ['priority' => 'secondary']);
        $this->log('Secondary-only event', '2026-01-01T13:00:00Z', 'project.secondary');

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertSee('0 resultaten')->assertDontSee('Secondary-only event');
        $this->get(route('chief.audit.index', ['show_all' => 1]))
            ->assertOk()->assertSee('1 resultaten')->assertSee('Secondary-only event');
    }

    public function test_search_ignores_rich_context_even_with_full_audit_access(): void
    {
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', 'view-full-audit');
        History::log(type: 'project.context', actorType: 'system', actorSnapshot: ['name' => 'Scheduler'], summary: 'Safe entry', context: ['content' => 'Private needle']);

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index', ['search' => 'Private needle']))
            ->assertOk()->assertSee('0 resultaten')->assertDontSee('<span>project.context</span>', false);
        $this->get(route('chief.audit.index', ['search' => 'Scheduler']))
            ->assertOk()->assertSee('Safe entry')->assertSee('1 resultaten');
    }

    public function test_safe_filters_options_and_details_do_not_expose_hidden_bulk_content(): void
    {
        $article = $this->setupAndCreateArticle();
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));
        $event = History::log(type: 'project.bulk', actorType: 'system', actorSnapshot: ['name' => 'Secret actor', 'id' => 'secret-actor-123'], summary: 'Secret summary', category: 'content', outcome: 'success', context: ['secret' => 'Secret context'], models: [
            new AuditModelDTO('project.hidden', '1', ['name' => 'Secret model']),
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Visible model']),
        ]);

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertSee('Visible model')->assertDontSee('Secret model')->assertDontSee('Secret actor')->assertDontSee('Secret summary')
            ->assertDontSee('project.hidden')->assertDontSee('secret-actor-123')->assertDontSee('Secret context');
        $this->get(route('chief.audit.index', ['search' => 'Secret actor']))->assertOk()->assertSee('0 resultaten');
        $this->get(route('chief.audit.index', ['actor' => 'secret-actor-123']))->assertOk()->assertSee('0 resultaten');
        $this->get(route('chief.audit.index', ['model_type' => 'project.hidden']))->assertOk()->assertSee('0 resultaten');
        $this->get(route('chief.audit.index', ['model_type' => $article->getMorphClass(), 'model_id' => (string) $article->getKey()]))
            ->assertOk()->assertSee('1 resultaten');
        $this->get(route('chief.audit.event-details', $event->getKey()))->assertNotFound();
    }

    public function test_only_additional_content_opens_details_and_recorded_time_is_read_on_demand(): void
    {
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', 'view-full-audit');
        $this->travelTo('2026-01-03 12:00:00');
        $plain = History::log(type: 'project.plain', actorType: 'system', actorSnapshot: ['name' => 'System']);
        $delayed = History::log(type: 'project.delayed', actorType: 'system', actorSnapshot: ['name' => 'System'], occurredAt: '2026-01-02T12:00:00Z');

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertDontSee(route('chief.audit.event-details', $plain->getKey()))
            ->assertSee(route('chief.audit.event-details', $delayed->getKey()));
        $this->get(route('chief.audit.event-details', $plain->getKey()))->assertNotFound();
        $this->get(route('chief.audit.event-details', $delayed->getKey()))
            ->assertOk()->assertSee('Geregistreerd: 03/01/2026 12:00');
    }

    public function test_paging_across_batches_counts_only_projected_matches(): void
    {
        $article = $this->setupAndCreateArticle();
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));

        for ($i = 0; $i < 205; $i++) {
            History::log(type: 'project.bulk', actorType: 'system', actorSnapshot: ['name' => 'Scheduler'], summary: 'Private '.$i, models: [
                new AuditModelDTO('project.hidden', (string) $i, ['name' => 'Private model']),
            ]);
        }
        History::log(type: 'project.bulk', actorType: 'system', actorSnapshot: ['name' => 'Scheduler'], summary: 'Safe first', models: [
            new AuditModelDTO($article->getMorphClass(), (string) $article->id, ['name' => 'Visible']),
        ]);
        History::log(type: 'project.bulk', actorType: 'system', actorSnapshot: ['name' => 'Scheduler'], summary: 'Safe second', models: [
            new AuditModelDTO($article->getMorphClass(), (string) $article->id, ['name' => 'Visible']),
        ]);

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index', ['page' => 2, 'per_page' => 1]))
            ->assertOk()->assertSee('2 resultaten')->assertSee('Safe first')->assertDontSee('Safe second')->assertDontSee('Private model');
        $this->get(route('chief.audit.index', ['search' => 'Private', 'per_page' => 1]))
            ->assertOk()->assertSee('0 resultaten')->assertDontSee('Private model');
    }

    public function test_visible_model_context_opens_details_without_exposing_hidden_link_context(): void
    {
        $article = $this->setupAndCreateArticle();
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));
        $event = History::log(type: 'project.bulk', actorType: 'system', actorSnapshot: ['name' => 'Scheduler'], models: [
            new AuditModelDTO('project.hidden', '42', ['name' => 'Hidden'], ['note' => 'Private detail']),
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Allowed'], ['note' => 'Approved detail']),
        ]);
        $links = $event->models()->orderBy('id')->get();
        $hidden = route('chief.audit.details', [$event->getKey(), $links[0]->getKey()]);
        $allowed = route('chief.audit.details', [$event->getKey(), $links[1]->getKey()]);

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertSee($allowed)->assertDontSee($hidden)->assertDontSee('Private detail');
        $this->get($hidden)->assertNotFound();
        $this->get($allowed)->assertOk()->assertSee('Approved detail')->assertDontSee('Private detail');
    }

    private function log(string $summary, string $occurredAt, string $type = 'project.primary'): void
    {
        History::log(type: $type, actorType: 'system', actorSnapshot: ['name' => 'Scheduler'], summary: $summary, occurredAt: $occurredAt);
    }
}
