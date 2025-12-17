<?php

namespace Thinktomorrow\Chief\Admin\Users;

class UserObserver
{
    public function updating(User $user): void
    {
        if ($user->isDirty('enabled') && ! $user->isEnabled()) {
            // HARD cutoff
            $user->remember_token = null;
        }
    }
}
