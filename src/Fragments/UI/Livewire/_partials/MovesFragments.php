<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\_partials;

use InvalidArgumentException;
use Thinktomorrow\Chief\Fragments\App\Actions\MoveFragment;
use Thinktomorrow\Chief\Fragments\App\Queries\GetMoveDestinations;

trait MovesFragments
{
    /** @var list<string> */
    public array $moveParentId = [];

    /**
     * Loaded on demand when the move dialog opens, so the destination
     * tree is not walked on every render of the fragment drawer.
     *
     * @var list<array{value: string, label: string, disabled: bool}>|null
     */
    public ?array $moveDestinationOptions = null;

    /** @return array<string, string> */
    private function getListenersForMovingFragments(): array
    {
        return ['fragment-moved-'.$this->context->id => 'onFragmentMoved'];
    }

    private function resetMoveSelection(): void
    {
        $this->reset(['moveParentId', 'moveDestinationOptions']);
    }

    public function loadMoveDestinationOptions(): void
    {
        $this->moveDestinationOptions = $this->getMoveDestinationOptions();
    }

    /** @return list<array{id: string, label: string, depth: int, disabledReason: ?string, isCurrentLocation: bool}> */
    public function getMoveDestinations(): array
    {
        if (! $this->fragment) {
            return [];
        }

        return array_map(function (array $destination): array {
            $destination['isCurrentLocation'] = $destination['reason'] === 'current-location';
            $destination['disabledReason'] = match ($destination['reason']) {
                'not-allowed-at-root' => 'Dit fragmenttype is niet toegestaan op rootniveau.',
                'current-location' => 'Huidige locatie van dit fragment.',
                'self' => 'Een fragment kan niet onder zichzelf staan.',
                'descendant' => 'Een fragment kan niet onder een van zijn kinderen staan.',
                'not-allowed' => 'Dit fragmenttype is hier niet toegestaan.',
                default => null,
            };

            unset($destination['reason']);

            return $destination;
        }, app(GetMoveDestinations::class)->handle(
            $this->context->id,
            $this->fragment->fragmentId,
            $this->scopedLocale ?: app()->getLocale(),
        ));
    }

    /** @return list<array{value: string, label: string, disabled: bool}> */
    public function getMoveDestinationOptions(): array
    {
        return array_map(function (array $destination): array {
            $label = str_repeat('— ', $destination['depth']).ucfirst($destination['label']);

            if ($destination['isCurrentLocation']) {
                $label .= ' (huidige locatie)';
            } elseif ($destination['disabledReason']) {
                $label .= ' (niet mogelijk)';
            }

            return [
                'value' => $destination['id'],
                'label' => $label,
                'disabled' => $destination['disabledReason'] !== null,
            ];
        }, $this->getMoveDestinations());
    }

    public function moveFragment(): void
    {
        $selectedParentId = $this->moveParentId[0] ?? null;

        if (! $this->fragment || $selectedParentId === null || $selectedParentId === '') {
            $this->addError('moveParentId', 'Kies een nieuwe locatie.');

            return;
        }

        $contextId = $this->context->id;
        $parentId = $selectedParentId === 'root' ? null : $selectedParentId;

        try {
            $moved = app(MoveFragment::class)->handle($contextId, $this->fragment->fragmentId, $parentId);
        } catch (InvalidArgumentException) {
            $this->addError('moveParentId', 'Deze locatie is niet meer beschikbaar voor dit fragment.');

            return;
        }

        if (! $moved) {
            $this->addError('moveParentId', 'Het fragment staat al op deze locatie.');

            return;
        }

        $this->close();

        $this->dispatch('fragment-moved-'.$contextId, contextId: $contextId)
            ->to('chief-fragments::context');
        $this->dispatch('fragment-moved-'.$contextId, contextId: $contextId)
            ->to('chief-fragments::edit-fragment');

        $this->dispatch('create-notification', type: 'success', content: 'Verplaatst', duration: 2000);
    }

    public function onFragmentMoved(string $contextId): void
    {
        if ($this->isOpen && $contextId === $this->context->id && $this->fragment) {
            $this->close();
        }
    }
}
