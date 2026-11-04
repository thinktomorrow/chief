<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests\UI;

use Livewire\Livewire;
use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Plugins\Audit\Recording\AuditModelDTO;
use Thinktomorrow\Chief\Plugins\Audit\Recording\RichData;
use Thinktomorrow\Chief\Plugins\Audit\UI\AuditDetails;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class IndexTimelineTest extends ChiefTestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), AuditServiceProvider::class];
    }

    public function test_index_shows_a_timeline_for_model_free_events_with_its_own_filters_and_pagination(): void
    {
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', 'view-full-audit');
        config()->set('chief-audit.types', ['project.export' => ['label' => 'Export', 'icon' => 'clock', 'color' => 'green']]);
        config()->set('chief-audit.model_context', ['reference' => 'Referentie']);
        $this->travelTo('2026-01-03 12:00:00');

        $event = History::log(type: 'project.export', actorType: 'system', actorSnapshot: ['name' => 'Scheduler'], summary: 'Export klaar', context: ['reference' => 'ABC-12']);
        History::log(type: 'project.export', actorType: 'system', actorSnapshot: ['name' => 'Scheduler'], summary: 'Oudere export', occurredAt: '2026-01-02T12:00:00Z');

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertSee('2 resultaten')->assertSee('Alles tonen')
            ->assertSee('name="search"', false)->assertSee('name="model_id"', false)
            ->assertSee('bg-green-50')->assertSee('rounded-full')->assertSee('bg-grey-100 absolute')
            ->assertSee('Export klaar')->assertSee('Export')->assertSee('Referentie: ABC-12')
            ->assertSee('door Scheduler')->assertSee('03/01/26 12:00')->assertSee('datetime="2026-01-03T12:00:00+00:00"', false)
            ->assertSee("kind: 'event'", false)->assertSee('Oudere export');

        $this->get(route('chief.audit.index', ['per_page' => 1]))
            ->assertOk()->assertSee('Export klaar')->assertDontSee('Oudere export')->assertSee('page=2');
        $this->get(route('chief.audit.index', ['per_page' => 1, 'page' => 2]))
            ->assertOk()->assertSee('Oudere export')->assertDontSee('Export klaar');
        $this->get(route('chief.audit.index', ['search' => 'Export klaar']))
            ->assertOk()->assertSee('1 resultaten')->assertSee('value="Export klaar"', false)->assertDontSee('Oudere export');
        Livewire::test(AuditDetails::class)->call('open', 'event', (string) $event->getKey())->assertSee('ABC-12');
    }

    public function test_index_shows_each_permitted_model_and_only_links_to_projected_details_and_content(): void
    {
        $article = $this->setupAndCreateArticle();
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        config()->set('chief-audit.field_labels', ['title' => 'Titel']);
        config()->set('chief-audit.model_context', ['reference' => 'Referentie']);

        $event = History::log(type: 'project.bulk', actorType: 'admin', actorSnapshot: ['name' => 'Secret actor'], summary: 'Secret shared summary', context: ['reference' => 'Secret reference'], models: [
            (new AuditModelDTO('project.hidden', '42', ['name' => 'Hidden model']))->withChanges(['secret_field' => ['before' => 'Secret old', 'after' => 'Secret new']]),
            (new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Visible model']))->withChanges(['title' => ['before' => 'Oud', 'after' => 'Nieuw']]),
            new AuditModelDTO('project.extra', '13', ['name' => 'Third model'], ['note' => 'Third detail']),
        ], richData: [RichData::text('Hidden content', model: 0), RichData::text('Visible content', model: 1)]);
        $links = $event->models()->orderBy('id')->get();
        $hidden = "modelId: '".$links[0]->getKey()."'";
        $visible = "modelId: '".$links[1]->getKey()."'";
        $third = "modelId: '".$links[2]->getKey()."'";

        $full = $this->fakeUser();
        $full->givePermissionTo('view-audit', 'view-full-audit');
        $this->actingAs($full, 'chief')->get(route('chief.audit.index'))->assertOk()
            ->assertSeeInOrder(['Hidden model', 'Visible model', 'Third model'])
            ->assertSee('Secret shared summary')->assertSee('Referentie: Secret reference')->assertSee('door Secret actor')
            ->assertSee('Titel')->assertSee('Oud')->assertSee('Nieuw')
            ->assertSee($hidden, false)->assertSee($visible, false)->assertSee($third, false)->assertSee("kind: 'rich'", false)->assertSee("kind: 'event'", false);
        Livewire::test(AuditDetails::class)->call('open', 'model', (string) $event->getKey(), (string) $links[0]->getKey())->assertSee('Secret old');
        Livewire::test(AuditDetails::class)->call('open', 'model', (string) $event->getKey(), (string) $links[2]->getKey())->assertSee('Third detail');
        Livewire::test(AuditDetails::class)->call('open', 'event', (string) $event->getKey())->assertSee('Secret reference');

        $limited = $this->fakeUser();
        $limited->givePermissionTo('view-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));
        $this->actingAs($limited, 'chief')->get(route('chief.audit.index'))->assertOk()
            ->assertSee('Visible model')->assertSee('Titel')->assertSee('Oud')->assertSee('Nieuw')
            ->assertSee($visible, false)->assertSee("kind: 'rich'", false)
            ->assertDontSee($hidden, false)->assertDontSee($third, false)->assertDontSee("kind: 'event'", false)
            ->assertDontSee('Hidden model')->assertDontSee('Third model')->assertDontSee('Secret old')
            ->assertDontSee('Secret shared summary')->assertDontSee('Secret reference')->assertDontSee('Secret actor');
        Livewire::test(AuditDetails::class)->call('open', 'model', (string) $event->getKey(), (string) $links[0]->getKey())->assertNotFound();
        Livewire::test(AuditDetails::class)->call('open', 'model', (string) $event->getKey(), (string) $links[2]->getKey())->assertNotFound();
        Livewire::test(AuditDetails::class)->call('open', 'event', (string) $event->getKey())->assertNotFound();
        Livewire::test(AuditDetails::class)->call('open', 'model', (string) $event->getKey(), (string) $links[1]->getKey())->assertSee('Oud')->assertSee('Nieuw');
        Livewire::test(AuditDetails::class)->call('open', 'rich', (string) $event->getKey())->assertSee('Visible content')->assertDontSee('Hidden content');
    }
}
