<?php

namespace Thinktomorrow\Chief\Tests\Feature\Audit;

use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Thinktomorrow\Chief\Tests\Feature\Pages\PageFormParams;
use Thinktomorrow\Chief\Audit\Audit;

class AuditModelTest extends TestCase
{
    use PageFormParams;

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpChiefEnvironment();

        app(Register::class)->register(PageManager::class, Single::class);
    }

    /** @test */
    public function it_logs_create_events_on_pages()
    {
        $user = $this->developer();

        $response = $this->actingAs($user, 'chief')
            ->post(route('chief.back.managers.store', Single::managedModelKey()), $this->validPageParams());

        $page       = Page::first();
        $activity   = Audit::getAllActivityFor($page);

        $this->assertCount(1, $activity);
        $this->assertEquals('created', $activity->first()->description);
        $this->assertEquals($user->id, $activity->first()->causer_id);
        $this->assertEquals('singles', $activity->first()->subject_type);
    }

    /** @test */
    public function it_logs_edit_events_on_pages()
    {
        $user = $this->developer();

        $this->actingAs($user, 'chief')
            ->post(route('chief.back.managers.store', Single::managedModelKey()), $this->validPageParams());

        $page = Page::first();

        $response = $this->actingAs($user, 'chief')
            ->put(route('chief.back.managers.update', [Single::managedModelKey(), $page->id]), $this->validUpdatePageParams());

        $activity = Audit::getAllActivityFor($page);

        $this->assertCount(2, $activity);
        $this->assertEquals('edited', $activity->last()->description);
        $this->assertEquals($user->id, $activity->last()->causer_id);
        $this->assertEquals($page->getMorphClass(), $activity->last()->subject_type);
    }

    /** @test */
    public function it_logs_delete_events_on_pages()
    {
        $user = $this->developer();

        $this->actingAs($user, 'chief')
            ->post(route('chief.back.managers.store', Single::managedModelKey()), $this->validPageParams(['published' => false]));

        $page = Page::first();

        $response = $this->actingAs($user, 'chief')
             ->delete(route('chief.back.managers.delete', [Single::managedModelKey(), $page->id]), ['deleteconfirmation' => 'DELETE']);

        $activity = Audit::getAllActivityFor($page);

        $this->assertCount(2, $activity);
        $this->assertEquals('deleted', $activity->last()->description);
        $this->assertEquals($user->id, $activity->last()->causer_id);
        $this->assertEquals($page->getMorphClass(), $activity->last()->subject_type);
    }

    /** @test */
    public function it_logs_archive_events_on_pages()
    {
        $page = factory(Page::class)->create(['current_state' => PageState::PUBLISHED])->first();
        $manager = app(Managers::class)->findByKey('singles')->manage($page);

        $user = $this->developer();

        $this->actingAs($user, 'chief')->post($manager->assistant('archive')->route('archive'));

        $activity = Audit::getAllActivityFor($page);

        $this->assertCount(1, $activity);
        $this->assertEquals('archived', $activity->last()->description);
        $this->assertEquals($user->id, $activity->last()->causer_id);
        $this->assertEquals('singles', $activity->last()->subject_type);
    }

    /** @test */
    public function it_logs_create_events_on_other_models()
    {
        $this->markTestIncomplete();
        $user = $this->developer();
        Auth::guard('chief')->login($user);

        $this->actingAs($user, 'chief')
            ->post(route('chief.back.managers.store', Single::managedModelKey()), $this->validPageParams());

        $article = Page::first();
        $activity = $article->activity->first();

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
            ->post(route('chief.back.managers.store', Single::managedModelKey()), $this->validPageParams());

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
            ->post(route('chief.back.managers.store', Single::managedModelKey()), $this->validPageParams());

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
