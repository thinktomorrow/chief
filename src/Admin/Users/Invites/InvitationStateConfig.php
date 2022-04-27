<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users\Invites;

use Thinktomorrow\Chief\ManagedModels\States\State\State;
use Thinktomorrow\Chief\ManagedModels\States\State\StateConfig;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;

class InvitationStateConfig implements StateConfig
{
    public function getStateKey(): string
    {
        return InvitationState::KEY;
    }

    public function getStates(): array
    {
        return [
            InvitationState::none,
            InvitationState::pending,
            InvitationState::expired,
            InvitationState::revoked,
            InvitationState::accepted,
            InvitationState::denied,
        ];
    }

    public function getTransitions(): array
    {
        return [
            'invite' => [
                'from' => [InvitationState::none, InvitationState::expired, InvitationState::revoked],
                'to' => InvitationState::pending,
            ],
            'expire' => [
                'from' => [InvitationState::pending],
                'to' => InvitationState::expired,
            ],
            'revoke' => [
                'from' => [InvitationState::pending, InvitationState::expired],
                'to' => InvitationState::revoked,
            ],
            'accept' => [
                'from' => [InvitationState::pending],
                'to' => InvitationState::accepted,
            ],
            'deny' => [
                'from' => [InvitationState::pending],
                'to' => InvitationState::denied,
            ],
        ];
    }

    public function emitEvent(StatefulContract $statefulContract, string $transition, array $data): void
    {
        // TODO: Implement emitEvent() method.
    }
}
