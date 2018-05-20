<?php

namespace Chief\Users;

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
        if($this->user->invitation) return '';

        return $this->user->isEnabled()
            ? ''
            : '<span class="label label--error">geblokkeerd</span><a class="" href="'.route('back.invites.resend', $this->user->id).'">Stuur nieuwe uitnodiging</a>';
    }
}
