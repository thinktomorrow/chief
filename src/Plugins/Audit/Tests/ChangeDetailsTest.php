<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Forms\Tests\TestSupport\ModelWithAstrotomicTranslations;
use Thinktomorrow\Chief\Forms\Tests\TestSupport\ModelWithAstrotomicTranslationsTranslation;
use Thinktomorrow\Chief\Plugins\Audit\AuditModelDTO;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class ChangeDetailsTest extends ChiefTestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), AuditServiceProvider::class];
    }

    public function test_changes_are_opt_in_even_for_unguarded_models_and_can_be_selected_per_call(): void
    {
        $model = new ChangeDetailsModel;
        $model->setRawAttributes(['title' => 'Before', 'password' => 'old'], true);
        $model->title = 'After';
        $model->password = 'new';

        $this->log($model, new AuditModelDTO('article', '1', ['name' => 'Article']));
        $this->assertNull(DB::table('chief_audit_event_models')->first()->changes);

        $this->log($model, (new AuditModelDTO('article', '2', ['name' => 'Article']))->withChangesFrom($model, ['*']));

        $this->assertSame([
            'title' => ['before' => 'Before', 'after' => 'After'],
        ], json_decode(DB::table('chief_audit_event_models')->where('model_id', '2')->value('changes'), true));
    }

    public function test_default_selection_can_be_overridden_and_exclusions_apply_to_every_nested_segment(): void
    {
        $model = new ChangeDetailsModel;
        $model->setRawAttributes(['title' => 'First', 'data' => '{"public":{"label":"Old","API_TOKEN":"old"},"private":{"password":"old"}}'], true);
        $model->title = 'Second';
        $model->data = ['public' => ['label' => 'New', 'API_TOKEN' => 'new'], 'private' => ['password' => 'new']];

        $this->log($model, (new AuditModelDTO('article', '1', ['name' => 'Article']))->withChangesFrom($model));
        $this->assertSame(['title' => ['before' => 'First', 'after' => 'Second']], json_decode(DB::table('chief_audit_event_models')->first()->changes, true));

        $this->log($model, (new AuditModelDTO('article', '2', ['name' => 'Article']))->withChangesFrom($model, ['*'], ['label']));
        $this->assertSame(['title' => ['before' => 'First', 'after' => 'Second']], json_decode(DB::table('chief_audit_event_models')->where('model_id', '2')->value('changes'), true));

        $this->log($model, (new AuditModelDTO('article', '3', ['name' => 'Article']))->withChangesFrom($model, ['*']));
        $this->assertSame([
            'title' => ['before' => 'First', 'after' => 'Second'],
            'data.public.label' => ['before' => 'Old', 'after' => 'New'],
        ], json_decode(DB::table('chief_audit_event_models')->where('model_id', '3')->value('changes'), true));
    }

    public function test_casts_translations_and_missing_values_are_captured_without_accessors_or_locale(): void
    {
        $model = new ChangeDetailsModel;
        $model->setRawAttributes(['title' => 'Old', 'data' => '{"nl":{"title":"Oud"},"en":{"title":"Old"}}', 'count' => '2', 'note' => null], true);
        $model->data = ['nl' => ['title' => 'Nieuw'], 'en' => ['title' => 'Old'], 'fr' => ['title' => null]];
        $model->count = 3;
        $model->note = null;
        $model->new_field = null;
        app()->setLocale('en');

        $this->log($model, (new AuditModelDTO('article', '1', ['name' => 'Article']))->withChangesFrom($model, ['title', 'data.*.title', 'count', 'note', 'new_field']));

        $this->assertSame([
            'data.nl.title' => ['before' => 'Oud', 'after' => 'Nieuw'],
            'count' => ['before' => 2, 'after' => 3],
            'data.fr.title' => ['before' => ['missing' => true], 'after' => null],
            'new_field' => ['before' => ['missing' => true], 'after' => null],
        ], json_decode(DB::table('chief_audit_event_models')->first()->changes, true));

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->admin();
        $viewer->givePermissionTo('view-audit', 'view-full-audit');
        $this->actingAs($viewer, 'chief')->get(route('chief.audit.details', [
            DB::table('chief_audit_events')->value('id'), DB::table('chief_audit_event_models')->value('id'),
        ]))->assertOk()->assertSee('Ontbreekt')->assertSee('null');
    }

    public function test_only_full_audit_admins_can_read_model_specific_changes(): void
    {
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $model = new ChangeDetailsModel;
        $model->setRawAttributes(['title' => 'Old'], true);
        $model->title = 'New';
        $this->log($model, (new AuditModelDTO('article', '1', ['name' => 'Article']))->withChangesFrom($model));

        $eventId = DB::table('chief_audit_events')->value('id');
        $linkId = DB::table('chief_audit_event_models')->value('id');
        $this->assertNotNull(DB::table('chief_audit_event_models')->value('changes'));
        $viewer = $this->admin();
        $viewer->givePermissionTo('view-audit');

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.details', [$eventId, $linkId]))->assertRedirect(route('chief.back.dashboard'));
        $viewer->givePermissionTo('view-full-audit');
        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertSee(route('chief.audit.details', [$eventId, $linkId]));
        $this->actingAs($viewer, 'chief')->get(route('chief.audit.details', [$eventId, $linkId]))
            ->assertOk()->assertSee('Old')->assertSee('New');
        $this->get(route('chief.audit.details', [$eventId + 1, $linkId]))->assertNotFound();
    }

    public function test_long_text_stores_change_context_instead_of_entire_content(): void
    {
        $model = new ChangeDetailsModel;
        $model->setRawAttributes(['body' => str_repeat('a', 600).'OLD'.str_repeat('z', 600)], true);
        $model->body = str_repeat('a', 600).'NEW'.str_repeat('z', 600);

        $this->log($model, (new AuditModelDTO('article', '1', ['name' => 'Article']))->withChangesFrom($model, ['body']));

        $changes = json_decode(DB::table('chief_audit_event_models')->value('changes'), true);
        $this->assertArrayHasKey('body', $changes);
        $this->assertStringContainsString('OLD', $changes['body']['before']['excerpt']);
        $this->assertStringContainsString('NEW', $changes['body']['after']['excerpt']);
        $this->assertLessThan(200, strlen($changes['body']['before']['excerpt']));
    }

    public function test_captured_changes_do_not_follow_later_model_mutations(): void
    {
        $model = new ChangeDetailsModel;
        $model->setRawAttributes(['title' => 'Old'], true);
        $model->title = 'Captured';
        $link = (new AuditModelDTO('article', '1', ['name' => 'Article']))->withChangesFrom($model);
        $model->title = 'Later';

        $this->log($model, $link);

        $this->assertSame(['title' => ['before' => 'Old', 'after' => 'Captured']], json_decode(DB::table('chief_audit_event_models')->value('changes'), true));
    }

    public function test_loaded_translation_rows_are_compared_by_locale_without_using_the_current_locale(): void
    {
        $model = new ModelWithAstrotomicTranslations;
        $nl = new ModelWithAstrotomicTranslationsTranslation;
        $nl->setRawAttributes(['locale' => 'nl', 'title_trans' => 'Oud', 'api_token' => 'old'], true);
        $nl->title_trans = 'Nieuw';
        $nl->api_token = 'new';
        $en = new ModelWithAstrotomicTranslationsTranslation;
        $en->setRawAttributes(['locale' => 'en', 'title_trans' => 'Unchanged'], true);
        $model->setRelation('translations', collect([$nl, $en]));
        app()->setLocale('en');

        History::log(type: 'project.translation.changed', actorType: 'system', actorSnapshot: ['name' => 'System'], models: [
            (new AuditModelDTO('article', '1', ['name' => 'Article']))->withChangesFrom($model, ['translations.*.*']),
        ]);

        $this->assertSame(['translations.nl.title_trans' => ['before' => 'Oud', 'after' => 'Nieuw']], json_decode(DB::table('chief_audit_event_models')->value('changes'), true));
    }

    public function test_whole_long_addition_keeps_only_a_marked_beginning(): void
    {
        $model = new ChangeDetailsModel;
        $model->setRawAttributes([], true);
        $model->body = str_repeat('start', 120).str_repeat('private-end', 120);

        $this->log($model, (new AuditModelDTO('article', '1', ['name' => 'Article']))->withChangesFrom($model, ['body']));

        $changes = json_decode(DB::table('chief_audit_event_models')->value('changes'), true);
        $this->assertSame(['missing' => true], $changes['body']['before']);
        $this->assertStringStartsWith('Begin:', $changes['body']['after']['excerpt']);
        $this->assertStringNotContainsString('private-end', $changes['body']['after']['excerpt']);
    }

    private function log(ChangeDetailsModel $model, AuditModelDTO $link): void
    {
        History::log(type: 'project.article.updated', actorType: 'system', actorSnapshot: ['name' => 'System'], models: [$link]);
    }
}

class ChangeDetailsModel extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['data' => 'array', 'count' => 'integer'];
    }

    public function getTitleAttribute(): string
    {
        return 'accessor value';
    }

    public function auditChangePaths(): array
    {
        return ['title'];
    }

    public function auditChangeExclusions(): array
    {
        return ['private'];
    }
}
