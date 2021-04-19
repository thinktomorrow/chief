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

    public function enabledAsLabel(): string
    {
        // Avoid showing enabled state if there is an invitation pending
        if ($this->user->invitation->last()) {
            $state = $this->user->invitation->last()->stateOf(InvitationState::KEY);
            if ($state == 'pending') {
                return '<span class="label label-info">Uitgenodigd</span>';
            } elseif ($state == 'denied') {
                return '<span class="label label-error">Geblokkeerd</span>';
            }
        }

        return $this->user->isEnabled()
            ? ''
            : '<span class="label label-error">Geblokkeerd</span>';
    }
}
