<?php

namespace Thinktomorrow\Chief\Users;

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
        if ($this->user->invitation && $this->user->invitation->state() == 'pending') {
            return '<span class="label label-primary">Uitgenodigd</span>';
        }

        return $this->user->isEnabled()
            ? ''
            : '<span class="label label-error">Geblokkeerd</span>';
    }
}
