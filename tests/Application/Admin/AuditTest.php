<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin;

use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class AuditTest extends ChiefTestCase
{
    public function test_it_logs_edit_events_on_pages()
    {
        $user = $this->admin();
        $article = $this->setupAndCreateArticle();

        $this->actingAs($user, 'chief');
        $article->getStateConfig('current_state')->emitEvent($article, 'archive', []);

        $audit = Audit::getAllActivityFor($article);

        $this->assertCount(1, $audit);
        $this->assertEquals('archived', $audit->first()->description);
        $this->assertEquals($user->id, $audit->first()->causer_id);
        $this->assertEquals($article->getMorphClass(), $audit->last()->subject_type);
    }

    public function test_it_show_events()
    {
        $article = $this->setupAndCreateArticle();

        $article->getStateConfig('current_state')->emitEvent($article, 'archive', []);

        $response = $this->asAdmin()->get(route('chief.back.audit.index'));
        $response->assertSuccessful();

        $this->assertCount(1, $response->viewData('audit'));
    }

    public function test_it_can_show_events_per_user()
    {
        $user = $this->admin();
        $article = $this->setupAndCreateArticle();

        $this->actingAs($user, 'chief');
        $article->getStateConfig('current_state')->emitEvent($article, 'archive', []);

        $response = $this->get(route('chief.back.audit.show', $user->id));
        $response->assertSuccessful();

        $causerSnapshot = $response->viewData('causerSnapshot');
        $this->assertEquals($user->fullname, $causerSnapshot['fullname']);
    }

    public function test_it_uses_an_immutable_causer_snapshot(): void
    {
        $user = $this->admin();
        $originalName = $user->fullname;
        $article = $this->setupAndCreateArticle();

        $this->actingAs($user, 'chief');
        $article->getStateConfig('current_state')->emitEvent($article, 'archive', []);

        $user->update(['firstname' => 'Changed']);

        $audit = Audit::firstOrFail();

        $this->assertSame($originalName, $audit->causerName());
    }

    public function test_it_can_show_audit_for_a_deleted_user(): void
    {
        $user = $this->admin();
        $userId = $user->id;
        $userName = $user->fullname;
        $article = $this->setupAndCreateArticle();

        $this->actingAs($user, 'chief');
        $article->getStateConfig('current_state')->emitEvent($article, 'archive', []);
        $user->delete();

        $response = $this->asAdmin()->get(route('chief.back.audit.show', $userId));

        $response->assertSuccessful()->assertSee($userName);
        $this->assertSame($userName, $response->viewData('causerSnapshot')['fullname']);
    }
}
