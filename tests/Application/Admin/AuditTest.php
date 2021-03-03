<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin;

use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class AuditTest extends ChiefTestCase
{
    /** @test */
    public function it_logs_edit_events_on_pages()
    {
        $user = $this->admin();
        $article = $this->setupAndCreateArticle();

        $this->actingAs($user, 'chief')
            ->post($this->manager($article)->route('archive', $article));

        $audit = Audit::getAllActivityFor($article);

        $this->assertCount(1, $audit);
        $this->assertEquals('archived', $audit->first()->description);
        $this->assertEquals($user->id, $audit->first()->causer_id);
        $this->assertEquals($article->getMorphClass(), $audit->last()->subject_type);
    }

    /** @test */
    public function it_show_events()
    {
        $this->disableExceptionHandling();
        $article = $this->setupAndCreateArticle();

        $this->asAdmin()->post($this->manager($article)->route('archive', $article));

        $response = $this->asAdmin()->get(route('chief.back.audit.index'));

        $activity = $this->getResponseData($response, 'activity');
        $this->assertCount(1, $activity);
    }

    /** @test */
    public function it_can_show_events_per_user()
    {
        $user = $this->admin();
        $article = $this->setupAndCreateArticle();

        $this->actingAs($user, 'chief')->post($this->manager($article)->route('archive', $article));

        $response = $this->asAdmin()->get(route('chief.back.audit.show', $user->id));

        $activity = $this->getResponseData($response, 'activity');
        $causer = $this->getResponseData($response, 'causer');

        $response->assertViewHas('activity');
        $this->assertEquals($user->name, $causer->name);
    }
}
