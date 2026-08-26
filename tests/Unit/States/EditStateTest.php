<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\States;

use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Thinktomorrow\Chief\Forms\Fields\Boolean;
use Thinktomorrow\Chief\ManagedModels\States\UI\Livewire\EditState;
use Thinktomorrow\Chief\ManagedModels\States\UI\Livewire\TransitionDto;

final class EditStateTest extends TestCase
{
    public function test_it_injects_confirmation_field_defaults_into_form_state(): void
    {
        $component = new EditStateWithConfirmationFields;
        $component->setFormData(['stale_value' => true]);

        $component->transition('default-on');

        $this->assertSame(['delete_registrations' => true], $component->getFormData());

        $component->closeConfirm();
        $component->transition('default-off');

        $this->assertSame(['delete_registrations' => false], $component->getFormData());
    }
}

final class EditStateWithConfirmationFields extends EditState
{
    /** @return Collection<int, TransitionDto> */
    public function getTransitions(): Collection
    {
        return collect([
            $this->transitionWithField('default-on', Boolean::make('delete_registrations')->defaultOn()),
            $this->transitionWithField('default-off', Boolean::make('delete_registrations')->defaultOff()),
        ]);
    }

    private function transitionWithField(string $key, Boolean $field): TransitionDto
    {
        return new TransitionDto(
            key: $key,
            label: $key,
            variant: 'grey',
            title: null,
            content: null,
            hasConfirmation: true,
            confirmationLabel: null,
            confirmationTitle: null,
            confirmationContent: null,
            confirmationFields: [$field],
            redirectTo: null,
            redirectNotification: null,
        );
    }
}
