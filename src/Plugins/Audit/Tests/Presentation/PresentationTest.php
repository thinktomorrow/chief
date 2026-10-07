<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests\Presentation;

use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Plugins\Audit\Presentation\AuditEventPresentation;
use Thinktomorrow\Chief\Plugins\Audit\Presentation\AuditFilter;
use Thinktomorrow\Chief\Plugins\Audit\Presentation\AuditPresentations;
use Thinktomorrow\Chief\Plugins\Audit\Presentation\AuditType;
use Thinktomorrow\Chief\Plugins\Audit\Reading\VisibleAuditEvent;
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

    public function test_project_registration_and_event_override_are_resolved_at_read_time(): void
    {
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-full-audit');
        $registry = app(AuditPresentations::class);
        History::log(type: 'project.approved', category: 'orders', actorType: 'system', actorSnapshot: ['name' => 'Worker'], summary: 'Approved now');
        History::log(type: 'chief.login', actorType: 'system', actorSnapshot: ['name' => 'Worker'], summary: 'Chief binding replaced');
        History::log(type: 'project.unknown', actorType: 'system', actorSnapshot: ['name' => 'Worker'], summary: 'Old fact');
        History::log(type: 'project.orphaned', actorType: 'system', actorSnapshot: ['name' => 'Worker'], summary: 'Orphaned fact');
        History::log(type: 'project.unbound', actorType: 'system', actorSnapshot: ['name' => 'Worker'], summary: 'Unbound fact');
        $registry->category('orders', 'Bestellingen');
        $registry->type('project.order', OrderType::class);
        $registry->type('project.orphaned', OrderType::class);
        $registry->type('project.unbound', OrderType::class);
        $registry->bind('project.approved', 'project.order', ApprovedPresentation::class);
        $registry->bind('chief.login', 'project.order');
        $registry->bind('project.orphaned', 'project.removed');

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertSee('Old fact')->assertSee('project.unknown')->assertSee('Unbound fact')->assertSee('project.unbound')->assertSee('Orphaned fact')
            ->assertSee('project.orphaned')->assertSee('Bestellingen')->assertSee('Approved now')
            ->assertDontSee('Chief binding replaced');
        $this->get(route('chief.audit.index', ['search' => 'Approved now']))
            ->assertOk()->assertSee('Approved now')->assertSee('Goedkeuring')->assertSee('text-green-500');
        $this->get(route('chief.audit.index', ['search' => 'Chief binding replaced']))
            ->assertOk()->assertSee('Bestelling');
    }

    public function test_project_filters_and_presenters_only_receive_authorized_projection(): void
    {
        $article = $this->setupAndCreateArticle();
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-related-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));
        $registry = app(AuditPresentations::class);
        $registry->type('project.bulk', OrderType::class);
        $registry->bind('project.bulk', 'project.bulk', ApprovedPresentation::class);
        $registry->filter('project_model', VisibleModelFilter::class);
        History::log(type: 'project.bulk', actorType: 'system', actorSnapshot: ['name' => 'Hidden actor'], summary: 'Hidden summary', context: ['secret' => 'Hidden context'], models: [
            new AuditModelDTO('project.hidden', '42', ['name' => 'Hidden order']),
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Visible article']),
        ]);

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index', ['filter' => ['project_model' => 'Hidden order']]))
            ->assertOk()->assertSee('0 resultaten')->assertDontSee('<span>Hidden order</span>', false);
        $this->get(route('chief.audit.index', ['filter' => ['project_model' => 'Visible article']]))
            ->assertOk()->assertSee('1 resultaten')->assertSee('Visible article')->assertSee('Goedkeuring')
            ->assertDontSee('Hidden summary')->assertDontSee('Hidden actor')->assertDontSee('Hidden order')->assertDontSee('Hidden context');
        $this->get(route('chief.audit.index', ['filter' => ['not_registered' => 'anything']]))->assertSessionHasErrors('filter');
    }

    public function test_project_presentations_are_used_in_widget_and_model_history(): void
    {
        $article = $this->setupAndCreateArticle();
        ArticlePageResource::setFieldsDefinition(fn ($model) => AuditPresets::modelHistoryWindow($model));
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-related-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'), ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'update'));
        app(AuditPresentations::class)->type('project.order', OrderType::class);
        app(AuditPresentations::class)->bind('project.approved', 'project.order', ApprovedPresentation::class);
        config()->set('chief.widgets', [RecentHistoryWidget::class]);

        History::log(type: 'project.approved', actorType: 'system', actorSnapshot: ['name' => 'Worker'], models: [
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Visible article']),
        ]);

        $this->actingAs($viewer, 'chief')->get(route('chief.back.dashboard'))->assertOk()->assertSee('Goedkeuring');
        $this->get($this->manager($article)->route('edit', $article))->assertOk()->assertSee('Goedkeuring');
    }
}

final class OrderType implements AuditType
{
    public function label(): string
    {
        return 'Bestelling';
    }

    public function icon(): string
    {
        return 'clock';
    }

    public function color(): string
    {
        return 'blue';
    }

    public function priority(): string
    {
        return 'secondary';
    }
}

final class ApprovedPresentation implements AuditEventPresentation
{
    public function present(VisibleAuditEvent $event, AuditType $default): AuditType
    {
        if (in_array('Hidden order', $event->modelNames, true) || $event->summary === 'Hidden summary') {
            throw new \RuntimeException('Unprojected input');
        }

        return new ApprovedType;
    }
}

final class ApprovedType implements AuditType
{
    public function label(): string
    {
        return 'Goedkeuring';
    }

    public function icon(): string
    {
        return 'clock';
    }

    public function color(): string
    {
        return 'green';
    }

    public function priority(): string
    {
        return 'primary';
    }
}

final class VisibleModelFilter implements AuditFilter
{
    public function label(): string
    {
        return 'Projectmodel';
    }

    public function matches(VisibleAuditEvent $event, string $value): bool
    {
        return in_array($value, $event->modelNames, true);
    }
}
