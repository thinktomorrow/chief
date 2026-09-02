<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Tests\UI\Livewire;

use Livewire\Livewire;
use Thinktomorrow\Chief\Forms\Dialogs\Dialog;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\UI\Livewire\ActionDialog;
use Thinktomorrow\Chief\Forms\UI\Livewire\References\DialogReference;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class ActionDialogTest extends ChiefTestCase
{
    public function test_it_injects_field_values_into_form_state_when_opening_dialog(): void
    {
        Livewire::test(ActionDialog::class, ['parentId' => 'table-component'])
            ->call('open', [
                'dialogReference' => (new TestDialogReference)->toLivewire(),
                'data' => [],
            ])
            ->assertSet('form.foobar', 'example value');
    }

    public function test_it_keeps_dialog_open_while_save_is_handled_by_parent_component(): void
    {
        Livewire::test(ActionDialog::class, ['parentId' => 'table-component'])
            ->call('open', [
                'dialogReference' => (new TestDialogReference)->toLivewire(),
                'data' => [],
            ])
            ->call('save')
            ->assertSet('isOpen', true)
            ->assertSet('isSaving', true)
            ->assertDispatched('dialogSaved-table-component');
    }

    public function test_it_closes_dialog_after_parent_component_completes_save(): void
    {
        Livewire::test(ActionDialog::class, ['parentId' => 'table-component'])
            ->call('open', [
                'dialogReference' => (new TestDialogReference)->toLivewire(),
                'data' => [],
            ])
            ->call('save')
            ->dispatch('actionCompleted-table-component')
            ->assertSet('isOpen', false)
            ->assertSet('isSaving', false);
    }

    public function test_it_can_keep_dialog_open_after_parent_component_completes_save(): void
    {
        Livewire::test(ActionDialog::class, ['parentId' => 'table-component'])
            ->call('open', [
                'dialogReference' => (new TestDialogReference)->toLivewire(),
                'data' => [],
            ])
            ->call('save')
            ->dispatch('actionCompleted-table-component', close: false)
            ->assertSet('isOpen', true)
            ->assertSet('isSaving', false);
    }
}

final class TestDialogReference implements DialogReference
{
    public function toLivewire(): array
    {
        return [
            'class' => self::class,
        ];
    }

    public static function fromLivewire($value): self
    {
        return new self;
    }

    public function getDialog(): Dialog
    {
        return Dialog::make('foobar-modal')
            ->form([
                Text::make('foobar')->value('example value'),
            ]);
    }
}
