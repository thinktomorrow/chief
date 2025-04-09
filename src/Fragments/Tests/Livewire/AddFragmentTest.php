<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Fragments\UI\Livewire\AddFragment;
use Thinktomorrow\Chief\Fragments\UI\Livewire\ContextDto;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Hero;

class AddFragmentTest extends ChiefTestCase
{
    private ContextOwner $model;

    private ContextModel $context;

    private Fragment $fragment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setUpAndCreateArticle();
    }

    private function mountComponent(array $params = []): Testable
    {
        [$this->context, $this->fragment] = FragmentTestHelpers::createContextAndAttachFragment($this->model, Hero::class, null, 0, []);

        return Livewire::test(AddFragment::class, array_merge([
            'context' => ContextDto::fromContext(
                ContextModel::first(),
                $this->model->modelReference(),
                'ownerLabel',
                'ownerAdminUrl'
            ),
            'parentComponentId' => 'test-parent',
        ], $params));
    }

    public function test_component_renders()
    {
        $this->mountComponent()
            ->assertStatus(200);
    }

    public function test_it_can_open_and_close()
    {
        $component = $this->mountComponent();

        $component->call('open', ['parentId' => null, 'order' => 1])
            ->assertSet('isOpen', true);

        $component->call('close')
            ->assertSet('isOpen', false)
            ->assertSet('parentId', null)
            ->assertSet('insertAfterOrder', null);
    }

    public function test_it_can_attach_fragment()
    {
        $fragment = FragmentTestHelpers::createFragment(Hero::class);

        $this->mountComponent()
            ->set('insertAfterOrder', 1)
            ->call('attachFragment', $fragment->getFragmentId());

        $this->assertDatabaseHas('context_fragment_tree', [
            'context_id' => $this->context->id,
            'child_id' => $fragment->getFragmentId(),
            'order' => 1,
        ]);
    }

    public function test_it_can_show_create_form_and_save_fragment()
    {
        [, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($this->model, Hero::class, null, 0);

        $component = $this->mountComponent();
        $component->call('showCreateForm', Hero::resourceKey())
            ->assertSet('showCreate', true);

        $component->call('save')
            ->assertSet('isOpen', false);
    }

    public function test_it_updates_filters_and_toggles_existing_view()
    {
        $component = $this->mountComponent();

        $component->set('filters', ['types' => [null]]);

        $this->assertEmpty($component->get('filters'));

        $component->set('filters', ['types' => ['test-type']]);
        $this->assertTrue($component->instance()->showExisting());
    }

    public function test_it_returns_allowed_fragments()
    {
        $component = $this->mountComponent();

        $allowed = $component->instance()->getAllowedFragments();

        $this->assertInstanceOf(Collection::class, $allowed);
        $this->assertTrue($allowed->first() instanceof \Thinktomorrow\Chief\Fragments\Fragment);
    }

    public function test_it_returns_allowed_fragments_grouped()
    {
        $component = $this->mountComponent();

        $grouped = $component->instance()->getAllowedFragmentsGrouped();

        $this->assertInstanceOf(Collection::class, $grouped);
    }

    public function test_it_renders_view()
    {
        View::addNamespace('chief-fragments', __DIR__.'/../../resources/views');

        $this->mountComponent()->assertViewIs('chief-fragments::livewire.add-fragment');
    }
}
