<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users;

use Thinktomorrow\Chief\Admin\Users\Invites\InvitationState;

class UserPresenter
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getStateBadge(): array
    {
        // Avoid showing enabled state if there is an invitation pending
        if ($this->user->invitation->last()) {
            $state = $this->user->invitation->last()->getState(InvitationState::KEY);
            if ($state == InvitationState::pending) {
                return [
                    'label' => 'Uitgenodigd',
                    'variant' => 'blue',
                ];
            } elseif ($state == InvitationState::denied) {
                return [
                    'label' => 'Geblokkeerd',
                    'variant' => 'red',
                ];
            }
        }

        return $this->user->isEnabled()
            ? [
                'label' => 'Actief',
                'variant' => 'green',
            ]
            : [
                'label' => 'Geblokkeerd',
                'variant' => 'red',
            ];
    }
}
