<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Fragments\Actions\PutFragmentOnline;
use Thinktomorrow\Chief\Fragments\Events\FragmentPutOnline;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class PutFragmentOnlineTest extends ChiefTestCase
{
    use FragmentOnlineAndOfflineHelpers;

    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
    }

    public function test_it_can_put_fragment_online()
    {
        $fragment = $this->prepareOfflineFragment($this->owner);

        app(PutFragmentOnline::class)->handle($fragment->getFragmentId());

        $fragment = FragmentTestAssist::findFragment($fragment->getFragmentId());
        $this->assertTrue($fragment->fragmentModel()->isOnline());
    }

    public function test_it_emits_event()
    {
        Event::fake();

        $fragment = $this->prepareOfflineFragment($this->owner);

        app(PutFragmentOnline::class)->handle($fragment->getFragmentId());

        Event::assertDispatched(FragmentPutOnline::class);
    }

    public function test_putting_online_twice_only_emits_event_once()
    {
        Event::fake();

        $fragment = $this->prepareOfflineFragment($this->owner);

        app(PutFragmentOnline::class)->handle($fragment->getFragmentId());
        app(PutFragmentOnline::class)->handle($fragment->getFragmentId());

        Event::assertDispatchedTimes(FragmentPutOnline::class, 1);
    }

    public function test_putting_online_when_already_online_does_not_emit_event()
    {
        Event::fake();

        $fragment = $this->prepareOnlineFragment($this->owner);

        app(PutFragmentOnline::class)->handle($fragment->getFragmentId());

        Event::assertNotDispatched(FragmentPutOnline::class);
    }

}
