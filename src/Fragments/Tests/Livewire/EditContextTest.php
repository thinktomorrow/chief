<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Livewire;

use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\Domain\Context\Context;
use Thinktomorrow\Chief\Fragments\Exceptions\SafeContextDeleteException;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Context\EditContext;

class EditContextTest extends \Thinktomorrow\Chief\Tests\ChiefTestCase
{
    private ContextOwner $model;

    private ContextModel $context;

    private Testable $component;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = $this->setUpAndCreateArticle();

        $this->context = FragmentTestHelpers::createContext($this->model, ['nl'], ['site-nl'], 'Context title');

        $this->component = Livewire::test(EditContext::class, ['modelReference' => $this->model->modelReference()]);
    }

    public function test_it_can_open_the_edit_context_modal()
    {
        $this->component
            ->call('open', ['contextId' => $this->context->id])
            ->assertSet('isOpen', true)
            ->assertSet('context.id', $this->context->id)
            ->assertSet('form.title', $this->context->title)
            ->assertSet('form.locales', $this->context->getSiteLocales())
            ->assertSet('form.active_sites', $this->context->getActiveSites());
    }

    public function test_it_can_update_the_context()
    {
        $this->component
            ->call('open', ['contextId' => $this->context->id])
            ->set('form.title', 'Updated Title')
            ->set('form.locales', ['nl', 'en'])
            ->set('form.active_sites', ['en'])
            ->call('save')
            ->assertDispatched($this->model->modelReference()->get().'-contexts-updated', ...['contextId' => $this->context->id]);

        $this->context->refresh();
        $this->assertSame('Updated Title', $this->context->title);
        $this->assertSame(['nl', 'en'], $this->context->getSiteLocales());
        $this->assertSame(['en'], $this->context->getActiveSites());
    }

    public function test_it_can_delete_the_context()
    {
        // Add a second context to allow deletion
        FragmentTestHelpers::createContext($this->model);

        // Remove active sites to allow deletion
        $this->context->update(['active_sites' => []]);

        $this->component
            ->call('open', ['contextId' => $this->context->id])
            ->call('deleteContext')
            ->assertDispatched($this->model->modelReference()->get().'-context-deleted', ['contextId' => $this->context->id]);

        $this->assertNull(ContextModel::find($this->context->id));
    }

    public function test_it_cannot_delete_context_if_it_has_active_sites(): void
    {
        $this->expectException(SafeContextDeleteException::class);

        $this->context->update(['active_sites' => ['site-1']]);

        $this->component
            ->call('open', ['contextId' => $this->context->id])
            ->call('deleteContext')
            ->assertSet('cannotBeDeleted', true)
            ->assertSet('cannotBeDeletedBecauseOfConnectedToSite', true);

        $this->assertNotNull(ContextModel::find($this->context->id));
    }

    public function test_it_cannot_delete_context_if_it_is_the_only_context(): void
    {
        $this->expectException(SafeContextDeleteException::class);

        $this->component
            ->call('open', ['contextId' => $this->context->id])
            ->call('deleteContext')
            ->assertSet('cannotBeDeleted', true)
            ->assertSet('cannotBeDeletedBecauseOfLastLeft', true);

        $this->assertNotNull(ContextModel::find($this->context->id));
    }

    public function test_it_resets_state_on_close()
    {
        $this->component
            ->call('open', ['contextId' => $this->context->id])
            ->call('close')
            ->assertSet('isOpen', false)
            ->assertSet('form', [])
            ->assertSet('context', null);
    }
}
