<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Livewire;

use Livewire\Livewire;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Context\Context;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Context\ContextDto;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Hero;

class ContextTest extends ChiefTestCase
{
    private ContextOwner $model;

    private Fragment $fragment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setUpAndCreateArticle();

        [, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($this->model, Hero::class, null, 0, ['title' => 'Test title']);
        $this->fragment = $fragment;
    }

    private function mountComponent()
    {
        $component = Livewire::test(Context::class, [
            'context' => ContextDto::fromContext(
                ContextModel::first(),
                $this->model->modelReference(),
                'ownerLabel',
                'ownerAdminUrl'
            ),
            'scopedLocale' => 'nl',
            'model' => $this->model,
        ]);

        $this->componentId = $component->instance()->getId();

        return $component;
    }

    public function test_component_renders()
    {
        $component = $this->mountComponent();

        $component->assertStatus(200);
    }

    public function test_it_can_handle_fragment_deleting_event()
    {
        $component = $this->mountComponent();

        $component->call('onFragmentDeleting', $this->fragment->getFragmentId(), ContextModel::first()->id, null)
            ->assertStatus(200);

        $this->assertDatabaseMissing('context_fragments', [
            'id' => $this->fragment->id,
        ]);
    }

    public function test_it_can_reorder_fragments()
    {
        // Add a second fragment
        [, $fragment2] = FragmentTestHelpers::createContextAndAttachFragment($this->model, Hero::class, null, 1, ['title' => 'Second fragment']);

        $component = $this->mountComponent();

        $component->call('reorder', [
            $fragment2->getFragmentId(),
            $this->fragment->getFragmentId(),
        ])->assertStatus(200);

        $this->assertDatabaseHas('context_fragment_tree', [
            'child_id' => $this->fragment->getFragmentId(),
            'order' => 1,
        ]);

        $this->assertDatabaseHas('context_fragment_tree', [
            'child_id' => $fragment2->getFragmentId(),
            'order' => 0,
        ]);
    }
}
