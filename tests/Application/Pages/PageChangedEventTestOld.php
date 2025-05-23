<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Forms\Events\FormUpdated;
use Thinktomorrow\Chief\Fragments\Events\FragmentAttached;
use Thinktomorrow\Chief\Fragments\Events\FragmentDetached;
use Thinktomorrow\Chief\Fragments\Events\FragmentDuplicated;
use Thinktomorrow\Chief\Fragments\Events\FragmentsReordered;
use Thinktomorrow\Chief\Fragments\Events\FragmentUpdated;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelDeleted;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUpdated;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUrlUpdated;
use Thinktomorrow\Chief\ManagedModels\Events\PageChanged;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class PageChangedEventTestOld extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setupAndCreateArticle();
        $this->fragment = $this->setupAndCreateQuote($this->model);
    }

    public function test_it_can_be_triggered_when_model_is_updated()
    {
        Event::fakeExcept(ManagedModelUpdated::class);

        event(new ManagedModelUpdated($this->model->modelReference()));

        Event::assertDispatched(PageChanged::class);
    }

    public function test_it_can_be_triggered_when_form_is_updated()
    {
        Event::fakeExcept(FormUpdated::class);

        event(new FormUpdated($this->model->modelReference(), 'xxx'));

        Event::assertDispatched(PageChanged::class);
    }

    public function test_it_can_be_triggered_when_fragments_are_updated()
    {
        Event::fakeExcept(FragmentUpdated::class);

        event(new FragmentUpdated($this->fragment->getFragmentModel()->id));

        Event::assertDispatched(PageChanged::class);
    }

    public function test_it_can_be_triggered_when_fragments_is_detached()
    {
        Event::fakeExcept(FragmentDetached::class);

        event(new FragmentDetached($this->fragment->getFragmentModel()->id, 123));

        Event::assertDispatched(PageChanged::class);
    }

    public function test_it_can_be_triggered_when_fragment_is_added()
    {
        Event::fakeExcept(FragmentAttached::class);

        event(new FragmentAttached($this->fragment->getFragmentModel()->id, 123));

        Event::assertDispatched(PageChanged::class);
    }

    public function test_it_can_be_triggered_when_fragment_is_duplicated()
    {
        Event::fakeExcept(FragmentDuplicated::class);

        event(new FragmentDuplicated($this->fragment->getFragmentModel()->id, '123'));

        Event::assertDispatched(PageChanged::class);
    }

    public function test_it_can_be_triggered_when_url_is_updated()
    {
        Event::fakeExcept(ManagedModelUrlUpdated::class);

        event(new ManagedModelUrlUpdated($this->model->modelReference()));

        Event::assertDispatched(PageChanged::class);
    }

    public function test_it_can_be_triggered_when_state_is_updated()
    {
        Event::fake();

        $this->asAdmin()->put($this->manager($this->model)->route('state-update', $this->model, PageState::KEY, 'archive'));

        Event::assertDispatched(PageChanged::class);
    }

    public function test_it_can_be_triggered_when_model_is_deleted()
    {
        Event::fakeExcept(ManagedModelDeleted::class);

        event(new ManagedModelDeleted($this->model->modelReference()));

        Event::assertDispatched(PageChanged::class);
    }

    public function test_it_can_be_triggered_when_fragments_are_reordered()
    {
        Event::fakeExcept(FragmentsReordered::class);

        event(new FragmentsReordered(ContextModel::ownedBy($this->model)->id));

        Event::assertDispatched(PageChanged::class);
    }
}
