<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Livewire;

use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Thinktomorrow\Chief\Fragments\App\Actions\MoveFragment;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Fragments\Tests\Stubs\MoveContainerStub;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Context\ContextDto;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Fragment\EditFragment;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Hero;
use Thinktomorrow\Chief\Tests\Shared\Fakes\OwnerAwareFragment;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

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
            'model' => $this->model,
        ]);
    }

    public function test_component_renders_for_localized_fragment()
    {
        [, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($this->model, Quote::class, null, 0, ['custom' => 'initial value']);

        $this->component
            ->call('open', [
                'fragmentId' => $fragment->getFragmentId(),
                'locales' => ['nl', 'en'],
                'scopedLocale' => 'nl',
            ])
            ->assertStatus(200)
            ->assertSet('locales', ['nl', 'en'])
            ->assertSet('scopedLocale', 'nl')
            ->assertSet('form.custom', 'initial value');
    }

    public function test_component_renders_for_non_localized_fragment()
    {
        $this->component
            ->call('open', [
                'fragmentId' => $this->fragment->getFragmentId(),
                'locales' => ['nl', 'en'],
                'scopedLocale' => 'nl',
            ])
            ->assertStatus(200)
            ->assertSet('locales', [])
            ->assertSet('scopedLocale', null)
            ->assertSet('form.title', 'initial value');
    }

    public function test_it_sets_context_owner_on_existing_fragment_fields(): void
    {
        chiefRegister()->fragment(OwnerAwareFragment::class);

        [, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($this->model, OwnerAwareFragment::class, null, 0, []);

        $this->component->call('open', [
            'fragmentId' => $fragment->getFragmentId(),
            'locales' => ['nl', 'en'],
            'scopedLocale' => 'nl',
        ]);

        $field = $this->component->instance()->getFields()->first();

        $this->assertCount(1, $field->getOptions());
        $this->assertEquals($this->model->modelReference()->get(), data_get($field->getOptions(), '0.value'));
    }

    public function test_can_update_fragment()
    {
        $this->component
            ->call('open', [
                'fragmentId' => $this->fragment->getFragmentId(),
                'locales' => ['nl', 'en'],
                'scopedLocale' => 'nl',
            ])
            ->set('form.title', 'Updated title')
            ->call('save');

        $this->assertDatabaseHas('context_fragments', [
            'id' => $this->fragment->getFragmentId(),
            'data->title' => 'Updated title',
        ]);
    }

    public function test_can_isolate_fragment()
    {
        $this->component
            ->call('open', [
                'fragmentId' => $this->fragment->getFragmentId(),
                'locales' => ['nl', 'en'],
                'scopedLocale' => 'nl',
            ])
            ->call('isolateFragment')
            ->assertDispatched('fragment-isolated-xxx');
    }

    public function test_can_delete_fragment()
    {
        $this->component
            ->call('open', [
                'fragmentId' => $this->fragment->getFragmentId(),
                'locales' => ['nl', 'en'],
                'scopedLocale' => 'nl',
            ])
            ->call('deleteFragment')
            ->assertDispatched('fragment-deleting-xxx');

        // Fragment is not yet deleted - because this is done via Context component
        $this->assertDatabaseHas('context_fragments', [
            'id' => $this->fragment->getFragmentId(),
        ]);
    }

    public function test_move_destinations_show_nested_options_and_reasons_for_invalid_parents(): void
    {
        chiefRegister()->fragment(MoveContainerStub::class);
        chiefRegister()->fragment(SnippetStub::class);

        $contextId = ContextModel::first()->id;
        $container = FragmentTestHelpers::createAndAttachFragment(MoveContainerStub::class, $contextId);
        $nested = FragmentTestHelpers::createAndAttachFragment(MoveContainerStub::class, $contextId, $container->getFragmentId());
        $invalidAncestor = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $contextId);
        $validDescendant = FragmentTestHelpers::createAndAttachFragment(MoveContainerStub::class, $contextId, $invalidAncestor->getFragmentId());
        $leaf = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $contextId);

        $this->component->call('open', [
            'fragmentId' => $leaf->getFragmentId(),
            'locales' => ['nl', 'en'],
            'scopedLocale' => 'nl',
        ]);

        $destinations = collect($this->component->instance()->getMoveDestinations())->keyBy('id');

        $this->assertSame('Het fragment staat al op deze locatie.', $destinations['root']['disabledReason']);
        $this->assertTrue($destinations['root']['isCurrentLocation']);
        $this->assertNull($destinations[$container->getFragmentId()]['disabledReason']);
        $this->assertSame(1, $destinations[$nested->getFragmentId()]['depth']);
        $this->assertNull($destinations[$nested->getFragmentId()]['disabledReason']);
        $this->assertSame('Dit fragmenttype is hier niet toegestaan.', $destinations[$invalidAncestor->getFragmentId()]['disabledReason']);
        $this->assertSame(1, $destinations[$validDescendant->getFragmentId()]['depth']);
        $this->assertNull($destinations[$validDescendant->getFragmentId()]['disabledReason']);
        $this->assertSame('Dit fragmenttype is hier niet toegestaan.', $destinations[$this->fragment->getFragmentId()]['disabledReason']);
        $this->component->assertSee('disabled', false);
        $this->component->assertSee('(huidige locatie)')
            ->assertSee('(niet mogelijk)');
    }

    public function test_move_destinations_use_the_active_admin_locale(): void
    {
        chiefRegister()->fragment(SnippetStub::class);

        $contextId = ContextModel::first()->id;
        $moving = FragmentTestHelpers::createAndAttachFragment(Quote::class, $contextId, null, 1, [
            'custom' => 'A quote',
        ]);
        $candidate = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $contextId, null, 2, [
            'title_trans' => ['nl' => 'Nederlandse titel', 'en' => 'English title'],
        ]);

        $this->component->call('open', [
            'fragmentId' => $moving->getFragmentId(),
            'locales' => ['nl', 'en'],
            'scopedLocale' => 'nl',
        ]);

        $dutch = collect($this->component->instance()->getMoveDestinations())->firstWhere('id', $candidate->getFragmentId());

        $this->component->set('scopedLocale', 'en');

        $english = collect($this->component->instance()->getMoveDestinations())->firstWhere('id', $candidate->getFragmentId());

        $this->assertSame('snippet stub — Nederlandse titel', $dutch['label']);
        $this->assertSame('snippet stub — English title', $english['label']);
    }

    public function test_moving_fragment_notifies_and_requests_context_refresh_only_after_success(): void
    {
        chiefRegister()->fragment(MoveContainerStub::class);
        chiefRegister()->fragment(SnippetStub::class);

        $contextId = ContextModel::first()->id;
        $container = FragmentTestHelpers::createAndAttachFragment(MoveContainerStub::class, $contextId);
        $leaf = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $contextId);
        $invalidParent = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $contextId);

        $this->component->call('open', [
            'fragmentId' => $leaf->getFragmentId(),
            'locales' => ['nl', 'en'],
            'scopedLocale' => 'nl',
        ])->call('moveFragment')
            ->assertHasErrors('moveParentId')
            ->assertNotDispatched('create-notification');

        $this->component
            ->set('moveParentId', $invalidParent->getFragmentId())
            ->call('moveFragment')
            ->assertHasErrors('moveParentId')
            ->assertSet('isOpen', true)
            ->assertNotDispatched('create-notification');

        $this->component
            ->set('moveParentId', $container->getFragmentId())
            ->call('moveFragment')
            ->assertHasNoErrors()
            ->assertSet('isOpen', false)
            ->assertDispatched('fragment-moved-'.$contextId)
            ->assertDispatched('create-notification', type: 'success', content: 'Verplaatst', duration: 2000);

        $this->assertDatabaseHas('context_fragment_tree', [
            'context_id' => $contextId,
            'child_id' => $leaf->getFragmentId(),
            'parent_id' => $container->getFragmentId(),
        ]);
    }

    public function test_moving_a_child_to_root_closes_its_original_parent_drawer(): void
    {
        chiefRegister()->fragment(MoveContainerStub::class);
        chiefRegister()->fragment(SnippetStub::class);

        $contextId = ContextModel::first()->id;
        $parent = FragmentTestHelpers::createAndAttachFragment(MoveContainerStub::class, $contextId);
        $child = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $contextId, $parent->getFragmentId());

        $this->component->call('open', [
            'fragmentId' => $parent->getFragmentId(),
            'locales' => ['nl', 'en'],
            'scopedLocale' => 'nl',
        ])->assertSet('isOpen', true);

        app(MoveFragment::class)->handle($contextId, $child->getFragmentId(), null);

        $this->component->dispatch('fragment-moved-'.$contextId, contextId: $contextId)
            ->assertSet('isOpen', false)
            ->assertSet('fragment', null);
    }

    public function test_can_put_fragment_online()
    {
        $this->component
            ->call('open', [
                'fragmentId' => $this->fragment->getFragmentId(),
                'locales' => ['nl', 'en'],
                'scopedLocale' => 'nl',
            ])
            ->call('putOnline')
            ->assertDispatched('fragment-updated-xxx');

        $this->assertTrue(FragmentModel::find($this->fragment->getFragmentId())->isOnline());
    }

    public function test_can_put_fragment_offline()
    {
        $this->fragment->getFragmentModel()->setOnline();
        $this->fragment->getFragmentModel()->save();

        $this->component
            ->call('open', [
                'fragmentId' => $this->fragment->getFragmentId(),
                'locales' => ['nl', 'en'],
                'scopedLocale' => 'nl',
            ])
            ->call('putOffline')
            ->assertDispatched('fragment-updated-xxx');

        $this->assertTrue(FragmentModel::find($this->fragment->getFragmentId())->isOffline());
    }
}
