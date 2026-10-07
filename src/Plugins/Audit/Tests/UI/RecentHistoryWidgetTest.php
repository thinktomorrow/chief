<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests\UI;

use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Plugins\Audit\Recording\AuditModelDTO;
use Thinktomorrow\Chief\Plugins\Audit\Recording\RichData;
use Thinktomorrow\Chief\Plugins\Audit\UI\RecentHistoryWidget;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class RecentHistoryWidgetTest extends ChiefTestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), AuditServiceProvider::class];
    }

    public function test_widget_is_opt_in_and_requires_audit_permission(): void
    {
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        History::log(type: 'project.visible', actorType: 'system', actorSnapshot: ['name' => 'System'], summary: 'Recent fact');

        $this->actingAs($viewer, 'chief')->get(route('chief.back.dashboard'))->assertOk()->assertDontSee('Recent fact');
        config()->set('chief.widgets', [RecentHistoryWidget::class]);
        $this->get(route('chief.back.dashboard'))->assertOk()->assertDontSee('Historiek')->assertDontSee('Recent fact');

        $viewer->givePermissionTo('view-full-audit');
        $this->get(route('chief.back.dashboard'))->assertOk()->assertSee('Recent fact');
    }

    public function test_selection_filters_after_projection_and_limit_and_clickthrough_preserves_filters(): void
    {
        $article = $this->setupAndCreateArticle();
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-related-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));
        config()->set('chief.widgets', [RecentHistoryWidget::class]);
        config()->set('chief.audit.widget', ['filters' => [
            'type' => ['project.order', 'project.other'], 'category' => 'content', 'actor' => 'system-1',
            'model_type' => $article->getMorphClass(), 'model_id' => (string) $article->getKey(),
            'from' => '2026-01-01', 'to' => '2026-01-31',
        ], 'limit' => 1]);

        History::log(type: 'project.order', actorType: 'system', actorSnapshot: ['id' => 'system-1', 'name' => 'Secret actor'], category: 'content', summary: 'Hidden shared content', occurredAt: '2026-01-20T12:00:00Z', models: [
            new AuditModelDTO('project.hidden', '1', ['name' => 'Hidden model']),
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Allowed model']),
        ]);
        History::log(type: 'project.order', actorType: 'system', actorSnapshot: ['id' => 'system-1', 'name' => 'Scheduler'], category: 'content', summary: 'Newest allowed', occurredAt: '2026-01-19T12:00:00Z', context: ['secret' => 'Rich private content'], models: [new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Allowed model'])]);
        History::log(type: 'project.other', actorType: 'system', actorSnapshot: ['id' => 'system-1', 'name' => 'Scheduler'], category: 'content', summary: 'Older allowed', occurredAt: '2026-01-18T12:00:00Z', models: [new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Allowed model'])]);
        History::log(type: 'project.order', actorType: 'system', actorSnapshot: ['id' => 'system-1', 'name' => 'Scheduler'], category: 'other', summary: 'Wrong category', occurredAt: '2026-01-21T12:00:00Z', models: [new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Allowed model'])]);

        $filters = config('chief.audit.widget.filters');
        $url = route('chief.audit.index', $filters);
        $this->actingAs($viewer, 'chief')->get(route('chief.back.dashboard'))
            ->assertOk()->assertSee('Allowed model')->assertSee('Historiek')->assertSee($url)
            ->assertDontSee('Older allowed')->assertSee('Newest allowed')->assertDontSee('Hidden shared content')
            ->assertDontSee('Hidden model')->assertDontSee('Secret actor')->assertDontSee('Rich private content')
            ->assertDontSee('Wrong category')->assertDontSee('limit=1');
        $this->get($url)->assertOk()->assertSee('2 resultaten')->assertSee('Older allowed')->assertDontSee('Hidden shared content');
    }

    public function test_widget_is_neutrally_empty_when_no_authorized_events_match(): void
    {
        $this->setupAndCreateArticle();
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-related-audit');
        config()->set('chief.widgets', [RecentHistoryWidget::class]);
        History::log(type: 'project.private', actorType: 'system', actorSnapshot: ['name' => 'Private actor'], summary: 'Private history');

        $this->actingAs($viewer, 'chief')->get(route('chief.back.dashboard'))
            ->assertOk()->assertSee('Geen historiek.')->assertDontSee('Private history')->assertDontSee('Private actor');
    }

    public function test_widget_defaults_to_primary_events_and_links_to_only_authorized_details(): void
    {
        $article = $this->setupAndCreateArticle();
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-related-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));
        config()->set('chief.widgets', [RecentHistoryWidget::class]);
        config()->set('chief.audit.types.project.secondary.priority', 'secondary');

        History::log(type: 'project.secondary', actorType: 'system', actorSnapshot: ['name' => 'System'], summary: 'Secondary event', models: [new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Article'])]);
        $event = History::log(type: 'project.bulk', actorType: 'system', actorSnapshot: ['name' => 'System'], summary: 'Hidden bulk summary', models: [
            new AuditModelDTO('project.hidden', '42', ['name' => 'Hidden'], ['note' => 'Hidden details']),
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Allowed'], ['note' => 'Allowed details']),
        ]);
        $links = $event->models()->orderBy('id')->get();
        $hidden = route('chief.audit.details', [$event->getKey(), $links[0]->getKey()]);
        $allowed = route('chief.audit.details', [$event->getKey(), $links[1]->getKey()]);

        $this->actingAs($viewer, 'chief')->get(route('chief.back.dashboard'))
            ->assertOk()->assertSee($allowed)->assertDontSee($hidden)->assertSee('Allowed')
            ->assertDontSee('Secondary event')->assertDontSee('Hidden bulk summary')->assertDontSee('Hidden details')
            ->assertDontSee('Allowed details');
        $this->get($allowed)->assertOk()->assertSee('Allowed details')->assertDontSee('Hidden details');
        $this->get($hidden)->assertNotFound();
    }

    public function test_widget_links_to_authorized_rich_data_when_it_is_the_only_detail(): void
    {
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-full-audit');
        config()->set('chief.widgets', [RecentHistoryWidget::class]);
        $event = History::log(type: 'project.mail', actorType: 'system', actorSnapshot: ['name' => 'Worker'], richData: [RichData::text('Historical body')]);

        $this->actingAs($viewer, 'chief')->get(route('chief.back.dashboard'))
            ->assertOk()->assertSee(route('chief.audit.rich-data', $event->getKey()))->assertDontSee('Historical body');
        $this->get(route('chief.audit.rich-data', $event->getKey()))->assertOk()->assertSee('Historical body');
    }
}
