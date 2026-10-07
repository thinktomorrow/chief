<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests\Presentation;

use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Plugins\Audit\Recording\AuditModelDTO;
use Thinktomorrow\Chief\Plugins\Audit\UI\AuditPresets;
use Thinktomorrow\Chief\Plugins\Audit\UI\RecentHistoryWidget;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class PresentationTest extends ChiefTestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), AuditServiceProvider::class];
    }

    public function test_event_and_category_labels_are_configured_by_stored_keys(): void
    {
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', 'view-full-audit');
        History::log(type: 'project.approved', category: 'orders', actorType: 'system', actorSnapshot: ['name' => 'Worker'], summary: 'Approved now');
        History::log(type: 'project.unknown', actorType: 'system', actorSnapshot: ['name' => 'Worker'], summary: 'Old fact');
        config()->set('chief-audit.categories', ['orders' => 'Bestellingen']);
        config()->set('chief-audit.types', ['project.approved' => ['label' => 'Goedkeuring', 'icon' => 'clock', 'color' => 'green']]);

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertSee('Bestellingen')->assertSee('Goedkeuring')->assertSee('text-green-500')
            ->assertSee('Old fact')->assertSee('project.unknown');
    }

    public function test_fixed_filters_only_search_authorized_projection(): void
    {
        $article = $this->setupAndCreateArticle();
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));
        History::log(type: 'project.bulk', actorType: 'system', actorSnapshot: ['name' => 'Hidden actor'], summary: 'Hidden summary', models: [
            new AuditModelDTO('project.hidden', '42', ['name' => 'Hidden order']),
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Visible article']),
        ]);

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index', ['search' => 'Hidden order']))
            ->assertOk()->assertSee('0 resultaten')->assertDontSee('<span>Hidden order</span>', false);
        $this->get(route('chief.audit.index', ['search' => 'Visible article']))
            ->assertOk()->assertSee('1 resultaten')->assertSee('Visible article')
            ->assertDontSee('Hidden summary')->assertDontSee('Hidden actor')->assertDontSee('Hidden order');
    }

    public function test_configured_labels_are_used_in_widget_and_model_history(): void
    {
        $article = $this->setupAndCreateArticle();
        ArticlePageResource::setFieldsDefinition(fn ($model) => AuditPresets::modelHistoryWindow($model));
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'), ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'update'));
        config()->set('chief.widgets', [RecentHistoryWidget::class]);
        config()->set('chief-audit.types', ['project.approved' => ['label' => 'Goedkeuring']]);

        History::log(type: 'project.approved', actorType: 'system', actorSnapshot: ['name' => 'Worker'], models: [
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Visible article']),
        ]);

        $this->actingAs($viewer, 'chief')->get(route('chief.back.dashboard'))->assertOk()->assertSee('Goedkeuring');
        $this->get($this->manager($article)->route('edit', $article))->assertOk()->assertSee('Goedkeuring');
    }
}
