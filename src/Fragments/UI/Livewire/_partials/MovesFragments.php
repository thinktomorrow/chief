<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\_partials;

use InvalidArgumentException;
use Thinktomorrow\Chief\Fragments\App\Actions\MoveFragment;
use Thinktomorrow\Chief\Fragments\App\Queries\GetMoveDestinations;

trait MovesFragments
{
    public ?string $moveParentId = null;

    private function getListenersForMovingFragments(): array
    {
        return ['fragment-moved-'.$this->context->id => 'onFragmentMoved'];
    }

    private function resetMoveSelection(): void
    {
        $this->reset('moveParentId');
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
                'current-location' => 'Het fragment staat al op deze locatie.',
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

    public function moveFragment(): void
    {
        if (! $this->fragment || $this->moveParentId === null || $this->moveParentId === '') {
            $this->addError('moveParentId', 'Kies een nieuwe locatie.');

            return;
        }

        $contextId = $this->context->id;
        $parentId = $this->moveParentId === 'root' ? null : $this->moveParentId;

        try {
            $moved = app(MoveFragment::class)->handle($contextId, $this->fragment->fragmentId, $parentId);
        } catch (InvalidArgumentException $exception) {
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
