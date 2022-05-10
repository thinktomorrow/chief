<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Forms\Events\FormUpdated;
use Thinktomorrow\Chief\Fragments\Events\FragmentAdded;
use Thinktomorrow\Chief\ManagedModels\Events\PageChanged;
use Thinktomorrow\Chief\Fragments\Events\FragmentUpdated;
use Thinktomorrow\Chief\Fragments\Events\FragmentDetached;
use Thinktomorrow\Chief\Fragments\Events\FragmentDuplicated;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUpdated;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelDeleted;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUrlUpdated;

class PageChangedEventTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setupAndCreateArticle();
        $this->fragment = $this->setupAndCreateQuote($this->model);
    }

    /** @test */
    public function it_can_be_triggered_when_model_is_updated()
    {
        Event::fakeExcept(ManagedModelUpdated::class);

        event(new ManagedModelUpdated($this->model->modelReference()));

        Event::assertDispatched(PageChanged::class);
    }

    /** @test */
    public function it_can_be_triggered_when_form_is_updated()
    {
        Event::fakeExcept(FormUpdated::class);

        event(new FormUpdated($this->model->modelReference(), 'xxx'));

        Event::assertDispatched(PageChanged::class);
    }

    /** @test */
    public function it_can_be_triggered_when_fragments_are_updated()
    {
        Event::fakeExcept(FragmentUpdated::class);

        event(new FragmentUpdated($this->fragment->fragmentModel()->id));

        Event::assertDispatched(PageChanged::class);
    }

    /** @test */
    public function it_can_be_triggered_when_fragments_is_detached()
    {
        Event::fakeExcept(FragmentDetached::class);

        event(new FragmentDetached($this->fragment->fragmentModel()->id, 123));

        Event::assertDispatched(PageChanged::class);
    }

    /** @test */
    public function it_can_be_triggered_when_fragment_is_added()
    {
        Event::fakeExcept(FragmentAdded::class);

        event(new FragmentAdded($this->fragment->fragmentModel()->id, 123));

        Event::assertDispatched(PageChanged::class);
    }

    /** @test */
    public function it_can_be_triggered_when_fragment_is_duplicated()
    {
        Event::fakeExcept(FragmentDuplicated::class);

        event(new FragmentDuplicated($this->fragment->fragmentModel()->id, 123));

        Event::assertDispatched(PageChanged::class);
    }

    /** @test */
    public function it_can_be_triggered_when_url_is_updated()
    {
        Event::fakeExcept(ManagedModelUrlUpdated::class);

        event(new ManagedModelUrlUpdated($this->model->modelReference()));

        Event::assertDispatched(PageChanged::class);
    }

    /** @test */
    public function it_can_be_triggered_when_state_is_updated()
    {
        Event::fake();

        $this->asAdmin()->put($this->manager($this->model)->route('state-update', $this->model, PageState::KEY, 'archive'));

        Event::assertDispatched(PageChanged::class);
    }

    /** @test */
    public function it_can_be_triggered_when_model_is_deleted()
    {
        Event::fakeExcept(ManagedModelDeleted::class);

        event(new ManagedModelDeleted($this->model->modelReference()));

        Event::assertDispatched(PageChanged::class);
    }
}
