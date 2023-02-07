<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin;

use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class AuditTest extends ChiefTestCase
{
    /** @test */
    public function it_logs_edit_events_on_pages()
    {
        $this->disableExceptionHandling();
        $user = $this->admin();
        $article = $this->setupAndCreateArticle();

        $this->actingAs($user, 'chief')->put($this->manager($article)->route('state-update', $article, PageState::KEY, 'archive'));

        $audit = Audit::getAllActivityFor($article);

        $this->assertCount(1, $audit);
        $this->assertEquals('archived', $audit->first()->description);
        $this->assertEquals($user->id, $audit->first()->causer_id);
        $this->assertEquals($article->getMorphClass(), $audit->last()->subject_type);
    }

    /** @test */
    public function it_show_events()
    {
        $article = $this->setupAndCreateArticle();

        $this->asAdmin()->put($this->manager($article)->route('state-update', $article, PageState::KEY, 'archive'));

        $response = $this->asAdmin()->get(route('chief.back.audit.index'));
        $response->assertSuccessful();

        $this->assertCount(1, $response->viewData('audit'));
    }

    /** @test */
    public function it_can_show_events_per_user()
    {
        $user = $this->admin();
        $article = $this->setupAndCreateArticle();

        $this->actingAs($user, 'chief')->put($this->manager($article)->route('state-update', $article, PageState::KEY, 'archive'));

        $response = $this->actingAs($user, 'chief')->get(route('chief.back.audit.show', $user->id));
        $response->assertSuccessful();

        $causer = $response->viewData('causer');
        $this->assertEquals($user->name, $causer->name);
    }
}
