<?php

namespace Thinktomorrow\Chief\Tests\Feature\Audit;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
use Thinktomorrow\Chief\Common\Audit\Audit;

class AuditTest extends TestCase
{
    use ChiefDatabaseTransactions, PageFormParams;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
        $this->setUpDefaultAuthorization();
    }

    /** @test */
    public function it_logs_create_events_on_pages()
    {
        $user = $this->developer();

        $this->actingAs($user, 'chief')
            ->post(route('chief.back.pages.store', 'statics'), $this->validPageParams());
        
        $page       = Page::first();
        $activity   = Audit::getActivityFor($page);

        $this->assertCount(1, $activity);
        $this->assertEquals('created', $activity->first()->description);
        $this->assertEquals($user->id, $activity->first()->causer_id);
        $this->assertEquals(get_class($page), $activity->first()->subject_type);
    }

    /** @test */
    public function it_logs_edit_events_on_pages()
    {
        $user = $this->developer();

        $this->actingAs($user, 'chief')
            ->post(route('chief.back.pages.store', 'statics'), $this->validPageParams());
        
        $page = Page::first();

        $this->actingAs($user, 'chief')
            ->put(route('chief.back.pages.update', $page->id), $this->validUpdatePageParams());

        $activity = Audit::getActivityFor($page);

        $this->assertCount(2, $activity);
        $this->assertEquals('edited', $activity->last()->description);
        $this->assertEquals($user->id, $activity->last()->causer_id);
        $this->assertEquals(get_class($page), $activity->last()->subject_type);
    }

    /** @test */
    public function it_logs_delete_events_on_pages()
    {
        $user = $this->developer();

        $this->actingAs($user, 'chief')
            ->post(route('chief.back.pages.store', 'statics'), $this->validPageParams(['published' => false]));
        
        $page = Page::first();

        $this->actingAs($user, 'chief')
             ->delete(route('chief.back.pages.destroy', $page->id), ['deleteconfirmation' => 'DELETE']);

        $activity = Audit::getActivityFor($page);

        $this->assertCount(2, $activity);
        $this->assertEquals('deleted', $activity->last()->description);
        $this->assertEquals($user->id, $activity->last()->causer_id);
        $this->assertEquals(get_class($page), $activity->last()->subject_type);
    }

    /** @test */
    public function it_logs_archive_events_on_pages()
    {
        $this->disableExceptionHandling();
        $user = $this->developer();

        $page = factory(Page::class)->create(['published' => true]);

        $this->actingAs($user, 'chief')
             ->delete(route('chief.back.pages.destroy', Page::first()->id));

        $activity = Audit::getActivityFor($page);

        $this->assertCount(1, $activity);
        $this->assertEquals('archived', $activity->last()->description);
        $this->assertEquals($user->id, $activity->last()->causer_id);
        $this->assertEquals(get_class($page), $activity->last()->subject_type);
    }

    /** @test */
    public function it_logs_create_events_on_other_models()
    {
        $this->markTestIncomplete();
        $user = $this->developer();
        Auth::guard('chief')->login($user);

        $this->actingAs($user, 'chief')
            ->post(route('chief.back.pages.store', 'statics'), $this->validPageParams());
        
        $activity = Page::ignoreCollection()->first();

        $this->assertCount(1, $article->activity);
        $this->assertEquals('created', $activity->description);
        $this->assertEquals($user->id, $activity->causer_id);
        $this->assertEquals(get_class($article), $activity->subject_type);
    }

    /** @test */
    public function it_show_events()
    {
        $user = $this->developer();

        $this->actingAs($user, 'chief')
            ->post(route('chief.back.pages.store', 'statics'), $this->validPageParams());

        $response = $this->actingAs($user, 'chief')
            ->get(route('chief.back.audit.index'));

        $activity = $this->getResponseData($response, 'activity');

        $this->assertCount(1, $activity);
    }

    /** @test */
    public function it_shows_events_sorted_by_timestamp()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_can_show_events_per_user()
    {
        $user = $this->developer();

        $this->actingAs($user, 'chief')
            ->post(route('chief.back.pages.store', 'statics'), $this->validPageParams());

        $response = $this->actingAs($user, 'chief')
            ->get(route('chief.back.audit.show', $user->id));

        $activity   = $this->getResponseData($response, 'activity');
        $causer     = $this->getResponseData($response, 'causer');

        $response->assertViewHas('activity');
        $this->assertEquals($user->name, $causer->name);
    }

    /** @test */
    public function it_can_show_events_per_model()
    {
        $this->markTestIncomplete();
    }
}

class ArticleFake extends Page
{
}