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

        $article->getStateConfig('current_state')->emitEvent($article, 'archive', []);

        $response = $this->actingAs($user, 'chief')->get(route('chief.back.audit.show', $user->id));
        $response->assertSuccessful();

        $causer = $response->viewData('causer');
        $this->assertEquals($user->name, $causer->name);
    }
}
