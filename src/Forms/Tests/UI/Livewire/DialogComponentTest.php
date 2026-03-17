<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Tests\UI\Livewire;

use Livewire\Livewire;
use Thinktomorrow\Chief\Forms\Dialogs\Dialog;
use Thinktomorrow\Chief\Forms\Dialogs\Livewire\DialogComponent;
use Thinktomorrow\Chief\Forms\Dialogs\Livewire\DialogReference;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class DialogComponentTest extends ChiefTestCase
{
    public function test_it_injects_field_values_into_form_state_when_opening_dialog(): void
    {
        Livewire::test(DialogComponent::class, ['parentId' => 'table-component'])
            ->call('open', [
                'dialogReference' => (new TestDialogReference)->toLivewire(),
                'data' => [],
            ])
            ->assertSet('form.foobar', 'example value');
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
