<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Livewire;

use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Context\ContextDto;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Fragment\EditFragment;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Hero;

class EditFragmentTest extends ChiefTestCase
{
    private ContextOwner $model;

    private Fragment $fragment;

    private Testable $component;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setUpAndCreateArticle();

        [, $this->fragment] = FragmentTestHelpers::createContextAndAttachFragment($this->model, Hero::class, null, 0, ['title' => 'initial value']);

        $this->component = Livewire::test(EditFragment::class, [
            'context' => ContextDto::fromContext(ContextModel::first(), $this->model->modelReference(), 'ownerLabel', 'ownerAdminUrl'),
            'parentComponentId' => 'xxx',
        ]);
    }

    public function test_component_renders()
    {
        $this->component
            ->call('open', ['fragmentId' => $this->fragment->getFragmentId()])
            ->assertStatus(200)
            ->assertSet('form.title', 'initial value');
    }

    public function test_can_update_and_save_fragment_data()
    {
        $this->component
            ->call('open', ['fragmentId' => $this->fragment->getFragmentId()])
            ->set('form.title', 'Updated title')
            ->call('save')
            ->assertDispatched('fragment-updated-xxx');

        $this->assertDatabaseHas('context_fragments', [
            'id' => $this->fragment->getFragmentId(),
            'data->title' => 'Updated title',
        ]);
    }

    public function test_can_isolate_fragment()
    {
        $this->component
            ->call('open', ['fragmentId' => $this->fragment->getFragmentId()])
            ->call('isolateFragment')
            ->assertDispatched('fragment-isolated-xxx');
    }

    public function test_can_delete_fragment()
    {
        $this->component
            ->call('open', ['fragmentId' => $this->fragment->getFragmentId()])
            ->call('deleteFragment')
            ->assertDispatched('fragment-deleting-xxx');

        // Fragment is not yet deleted - because this is done via Context component
        $this->assertDatabaseHas('context_fragments', [
            'id' => $this->fragment->getFragmentId(),
        ]);
    }

    public function test_can_put_fragment_online()
    {
        $this->component
            ->call('open', ['fragmentId' => $this->fragment->getFragmentId()])
            ->call('putOnline')
            ->assertDispatched('fragment-updated-xxx');

        $this->assertTrue(FragmentModel::find($this->fragment->getFragmentId())->isOnline());
    }

    public function test_can_put_fragment_offline()
    {
        $this->fragment->getFragmentModel()->setOnline();
        $this->fragment->getFragmentModel()->save();

        $this->component
            ->call('open', ['fragmentId' => $this->fragment->getFragmentId()])
            ->call('putOffline')
            ->assertDispatched('fragment-updated-xxx');

        $this->assertTrue(FragmentModel::find($this->fragment->getFragmentId())->isOffline());
    }
}
