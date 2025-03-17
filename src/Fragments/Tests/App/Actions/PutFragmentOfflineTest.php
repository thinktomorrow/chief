<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Fragments\App\Actions\PutFragmentOffline;
use Thinktomorrow\Chief\Fragments\Events\FragmentPutOffline;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class PutFragmentOfflineTest extends ChiefTestCase
{
    use FragmentOnlineAndOfflineHelpers;

    private ArticlePage $owner;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
    }

    public function test_it_can_put_fragment_offline()
    {
        $fragment = $this->prepareOnlineFragment($this->owner);

        app(PutFragmentOffline::class)->handle($fragment->getFragmentId());

        $fragment = FragmentTestHelpers::findFragment($fragment->getFragmentId());
        $this->assertTrue($fragment->getFragmentModel()->isOffline());
    }

    public function test_it_emits_event()
    {
        Event::fake();

        $fragment = $this->prepareOnlineFragment($this->owner);

        app(PutFragmentOffline::class)->handle($fragment->getFragmentId());

        Event::assertDispatched(FragmentPutOffline::class);
    }

    public function test_putting_offline_twice_only_emits_event_once()
    {
        Event::fake();

        $fragment = $this->prepareOnlineFragment($this->owner);

        app(PutFragmentOffline::class)->handle($fragment->getFragmentId());
        app(PutFragmentOffline::class)->handle($fragment->getFragmentId());

        Event::assertDispatchedTimes(FragmentPutOffline::class, 1);
    }

    public function test_putting_offline_when_already_offline_does_not_emit_event()
    {
        Event::fake();

        $fragment = $this->prepareOfflineFragment($this->owner);

        app(PutFragmentOffline::class)->handle($fragment->getFragmentId());

        Event::assertNotDispatched(FragmentPutOffline::class);
    }
}
