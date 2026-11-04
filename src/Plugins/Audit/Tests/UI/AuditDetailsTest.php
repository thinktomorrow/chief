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

final class AuditDetailsTest extends ChiefTestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), AuditServiceProvider::class];
    }

    public function test_event_model_and_rich_details_open_and_close_without_a_new_page(): void
    {
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', 'view-full-audit');
        $event = History::log(type: 'project.changed', actorType: 'system', actorSnapshot: ['name' => 'Worker'], context: ['reference' => 'Private reference'], models: [
            (new AuditModelDTO('project.article', '1', ['name' => 'Article']))->withChanges(['title' => ['before' => 'Old', 'after' => 'New']]),
        ], richData: [RichData::text('Historical body')]);
        $modelId = (string) $event->models()->firstOrFail()->getKey();

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertSee('chief-audit::details')->assertSee("kind: 'event'", false)->assertSee("kind: 'model'", false)->assertSee("kind: 'rich'", false)
            ->assertDontSee('Private reference')->assertDontSee('Historical body');

        Livewire::test(AuditDetails::class)->assertSet('isOpen', false)
            ->dispatch('audit:show-detail', kind: 'event', eventId: (string) $event->getKey())->assertSet('isOpen', true)->assertSee('Private reference')
            ->call('open', 'model', (string) $event->getKey(), $modelId)->assertSee('Old')->assertSee('New')->assertDontSee('Private reference')
            ->call('open', 'rich', (string) $event->getKey())->assertSee('Historical body')->assertDontSee('Old')
            ->call('close')->assertSet('isOpen', false)->assertDontSee('Historical body');

        $auditUrl = route('chief.audit.index').'/'.$event->getKey();
        $this->get($auditUrl.'/details')->assertNotFound();
        $this->get($auditUrl.'/'.$modelId)->assertNotFound();
        $this->get($auditUrl.'/rich')->assertNotFound();
    }

    public function test_detail_actions_recheck_access_for_each_request(): void
    {
        $article = $this->setupAndCreateArticle();
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));
        $event = History::log(type: 'project.bulk', actorType: 'system', actorSnapshot: ['name' => 'Secret actor'], context: ['secret' => 'Secret context'], models: [
            new AuditModelDTO('project.hidden', '42', ['name' => 'Hidden'], ['note' => 'Private detail']),
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Allowed'], ['note' => 'Approved detail']),
        ], richData: [RichData::text('Secret content', model: 0), RichData::text('Allowed content', model: 1)]);
        $links = $event->models()->orderBy('id')->get();

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertSee("modelId: '".$links[1]->getKey()."'", false)->assertDontSee("modelId: '".$links[0]->getKey()."'", false);

        Livewire::test(AuditDetails::class)->call('open', 'model', (string) $event->getKey(), (string) $links[1]->getKey())
            ->assertSee('Approved detail')->assertDontSee('Private detail');
        Livewire::test(AuditDetails::class)->call('open', 'model', (string) $event->getKey(), (string) $links[0]->getKey())->assertNotFound();
        Livewire::test(AuditDetails::class)->call('open', 'event', (string) $event->getKey())->assertNotFound();
        Livewire::test(AuditDetails::class)->call('open', 'rich', (string) $event->getKey())
            ->assertSee('Allowed content')->assertDontSee('Secret content');

        $article->delete();
        Livewire::test(AuditDetails::class)->call('open', 'model', (string) $event->getKey(), (string) $links[1]->getKey())->assertNotFound();
    }
}
