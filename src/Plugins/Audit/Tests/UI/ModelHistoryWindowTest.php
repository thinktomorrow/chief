<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests\UI;

use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Plugins\Audit\Recording\AuditModelDTO;
use Thinktomorrow\Chief\Plugins\Audit\UI\AuditPresets;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class ModelHistoryWindowTest extends ChiefTestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), AuditServiceProvider::class];
    }

    public function test_model_aside_shows_recent_primaries_and_expands_all_history_on_the_same_page(): void
    {
        $article = $this->setupAndCreateArticle();
        ArticlePageResource::setFieldsDefinition(fn ($model) => AuditPresets::modelHistoryWindow($model));
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', 'view-full-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'update'));
        config()->set('chief-audit.types', ['project.secondary' => ['priority' => 'secondary']]);

        for ($i = 1; $i <= 6; $i++) {
            $this->log($article, 'Primary '.$i, '2026-01-0'.$i.'T12:00:00Z');
        }
        $this->log($article, 'Secondary event', '2026-01-04T13:00:00Z', 'project.secondary');

        $url = $this->manager($article)->route('edit', $article);
        $this->actingAs($viewer, 'chief')->get($url)->assertOk()
            ->assertSee('Historiek')->assertSee('Primary 6')->assertSee('Primary 2')
            ->assertDontSee('Primary 1')->assertDontSee('Secondary event')->assertSee('Toon alle historiek');

        $this->get($url.'?audit_history=all')->assertOk()
            ->assertSeeInOrder(['Primary 6', 'Primary 5', 'Secondary event', 'Primary 4', 'Primary 3', 'Primary 2', 'Primary 1'])
            ->assertSee('Minder historiek')->assertDontSee('Toon alle historiek');
    }

    public function test_aside_is_hidden_without_audit_right_and_empty_state_is_neutral(): void
    {
        $article = $this->setupAndCreateArticle();
        ArticlePageResource::setFieldsDefinition(fn ($model) => AuditPresets::modelHistoryWindow($model));
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo(ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'update'));
        $url = $this->manager($article)->route('edit', $article);

        $this->log($article, 'Hidden history', '2026-01-01T12:00:00Z');
        $this->actingAs($viewer, 'chief')->get($url)->assertOk()->assertDontSee('Historiek')->assertDontSee('Hidden history');

        $viewer->givePermissionTo('view-audit');
        $this->get($url)->assertOk()->assertSee('Geen historiek.')->assertDontSee('Hidden history')->assertDontSee('Toon alle historiek');
    }

    public function test_full_right_alone_does_not_show_model_history(): void
    {
        $article = $this->setupAndCreateArticle();
        ArticlePageResource::setFieldsDefinition(fn ($model) => AuditPresets::modelHistoryWindow($model));
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-full-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'update'));
        $this->log($article, 'Hidden history', '2026-01-01T12:00:00Z');

        $url = $this->manager($article)->route('edit', $article);
        $this->actingAs($viewer, 'chief')->get($url)->assertOk()->assertDontSee('Historiek')->assertDontSee('Hidden history');

        $viewer->givePermissionTo('view-audit');
        $this->get($url)->assertOk()->assertSee('Hidden history');
    }

    public function test_related_access_only_shows_projected_links_and_own_actor_path_does_not_reveal_hidden_details(): void
    {
        $article = $this->setupAndCreateArticle();
        ArticlePageResource::setFieldsDefinition(fn ($model) => AuditPresets::modelHistoryWindow($model));
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'), ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'update'));

        History::log(type: 'project.bulk', actorType: 'system', actorSnapshot: ['name' => 'Secret actor'], summary: 'Secret summary', models: [
            new AuditModelDTO('project.hidden', '12', ['name' => 'Secret model']),
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Allowed model']),
        ]);
        $this->log($article, 'Safe history', '2026-01-01T12:00:00Z');
        History::log(type: 'project.own', actorType: 'admin', actorSnapshot: ['id' => (string) $viewer->id, 'name' => 'Own actor'], summary: 'Own private event', models: [
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Private model']),
        ]);

        $url = $this->manager($article)->route('edit', $article);
        $this->actingAs($viewer, 'chief')->get($url.'?audit_history=all')->assertOk()
            ->assertSee('Safe history')->assertSee('Allowed model')->assertDontSee('Secret summary')
            ->assertDontSee('Secret actor')->assertDontSee('Secret model');

        $viewer->revokePermissionTo(ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));
        $this->get($url.'?audit_history=all')->assertOk()->assertSee('Geen historiek.')
            ->assertDontSee('Safe history')->assertDontSee('Allowed model')->assertDontSee('Own private event')->assertDontSee('Private model');
    }

    private function log($article, string $summary, string $occurredAt, string $type = 'project.primary'): void
    {
        History::log(type: $type, actorType: 'system', actorSnapshot: ['name' => 'Scheduler'], summary: $summary, occurredAt: $occurredAt, models: [
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Article snapshot']),
        ]);
    }
}
